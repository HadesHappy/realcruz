<?php

/**
 * SendingDomain class.
 *
 * Model class for sending domains
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\MtaSync;
use Acelle\Library\Traits\HasUid;
use Validator;
use DB;
use Exception;
use Mika56\SPFCheck\SPFCheck;
use function Acelle\Helpers\spfcheck;

class SendingDomain extends Model
{
    use HasUid;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    // The following global app settings are used by domain
    // + spf_host
    // + spf_record
    // + verification_hostname

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function admin()
    {
        return $this->belongsTo('Acelle\Model\Admin');
    }

    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeBySendingServer($query, $server)
    {
        return $query->where('sending_server_id', $server->id);
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('sending_domains.name', 'like', '%'.$keyword.'%');
        }

        return $query;
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'signing_enabled',
    ];

    /**
     * Get validation rules.
     *
     * @return object
     */
    public static function rules()
    {
        return [
            'name' => 'required|regex:/^([a-z0-9A-Z]+(-[a-z0-9A-Z]+)*\.)+[a-zA-Z]{2,}$/',
        ];
    }

    /**
     * Get the clean public key, strip out the Header and Footer.
     */
    public function getCleanPublicKey()
    {
        $publicKey = str_replace(array('-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----'), '', $this->dkim_public);
        $publicKey = trim(preg_replace('/\s+/', '', $publicKey));

        return $publicKey;
    }

    /**
     * Generate the Domain DNS configuration for DKIM.
     */
    public function getDnsDkimConfig()
    {
        return doublequote($this->getDnsDkimConfigWithoutQuote());
    }

    /**
     * Generate the Domain DNS configuration for DKIM.
     */
    public function getEscapedDnsDkimConfig()
    {
        return str_replace(';', '\;', $this->getDnsDkimConfig());
    }

    /**
     * Generate the Domain DNS configuration for DKIM.
     */
    public function getDnsDkimConfigWithoutQuote()
    {
        return sprintf('v=DKIM1; k=rsa; p=%s;', $this->getCleanPublicKey());
    }

    /**
     * Generate SPF: allow sending through the host's server (IP address)
     * See more at: http://www.openspf.org/SPF_Record_Syntax.
     */
    public function getSpf()
    {
        $spfRecord = Setting::get('spf_record');
        $spfHost = Setting::get('spf_host');

        if ($spfRecord && is_null($spfHost)) {
            throw new \Exception('`spf_record` is present but `spf_host` is empty');
        }

        if (empty($spfRecord)) {
            return null;
        }

        return $spfRecord;
    }

    /**
     * Get quoted SPF.
     */
    public function getQuotedSpf()
    {
        // If a "spf" setting is available, it will take precedence
        return Setting::get('spf') ?: sprintf('%s', doublequote($this->getSpf()));
    }

    /**
     * Retrieve the VERIFICATION_TXT_NAME value which is used as TXT name.
     */
    public function getVerificationHostName()
    {
        return Setting::get('verification_hostname');
    }

    /**
     * Retrieve the full verification hostname (including domain name).
     */
    public function getFullVerificationHostName()
    {
        return "{$this->getVerificationHostName()}.{$this->getDnsHostName()}";
    }

    /**
     * Get DNS host name.
     */
    public function getDnsHostName()
    {
        return "{$this->name}.";
    }

    /**
     * Generate the verification token.
     */
    public function generateIdentityToken()
    {
        return base64_encode(md5(trim('SALT!'.$this->name)));
    }

    /**
     * Create the private and public key.
     *
     * @var bool
     */
    public function generateDkimKeys()
    {
        $config = array(
            'digest_alg' => 'sha256',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        );

        // Create the private and public key
        $res = openssl_pkey_new($config);

        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privKey);

        // Extract the public key from $res to $pubKey
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey['key'];

        $this->dkim_private = $privKey;
        $this->dkim_public = $pubKey;
    }

    /**
     * Add customer action log.
     */
    public function log($name, $customer, $add_datas = [])
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        $data = array_merge($data, $add_datas);

        Log::create([
            'customer_id' => $customer->id,
            'type' => 'sending_domain',
            'name' => $name,
            'data' => json_encode($data),
        ]);
    }

    public function startVerifying()
    {
        if ($this->sendingServer) {
            // Verify against APP
            $tokens = $this->sendingServer->mapType()->verifyDomain($this->name);
        } else {
            // Below is a 'STANDARD' schema
            $tokens = [
                'identity' => [
                    'type' => 'TXT',
                    'name' => $this->getFullVerificationHostName(),
                    'value' => $this->generateIdentityToken(),
                ],

                'dkim' => [
                    [
                        'type' => 'TXT',
                        'name' => $this->getFullDkimHostName(),
                        'value' => $this->getDnsDkimConfig(),
                    ]
                ],

                'results' => [
                    'identity' => false,
                    'dkim' => false,
                ]
            ];

            $spfRecord = $this->getSpf();

            if ($spfRecord) {
                $tokens['spf'] = [
                    [
                        'type' => 'TXT',
                        'name' => $this->getDnsHostName(),
                        'value' => $this->getSpf(),
                    ]
                ];

                $tokens['results']['spf'] = false;
            }
        }

        $this->updateVerificationTokens($tokens);
    }

    /**
     * Verify domain DNS.
     */
    public function verify()
    {
        $tokens = $this->getVerificationTokens();

        if ($this->sendingServer) {
            // Verify Host, DKIM and SPF
            $server = $this->sendingServer->mapType();
            list($identity, $dkim, $spf, $finalStatus) = $server->checkDomainVerificationStatus($this);

            $tokens['results']['identity'] = $identity;
            $tokens['results']['dkim'] = $dkim;
            $tokens['results']['spf'] = $spf;
            $this->status = $finalStatus ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        } else {
            $tokens['results']['identity'] = $this->verifyIdentity();
            $tokens['results']['dkim'] = $this->verifyDkim();

            if (array_key_exists('spf', $tokens)) {
                $host = Setting::get('spf_host');

                if (empty($host)) {
                    throw new Exception('There is no value for `spf_host`');
                }
                $tokens['results']['spf'] = $this->verifySpf($host);
            }

            $finalStatus = $tokens['results']['dkim'] && $tokens['results']['identity']; // SPF is optional
            $this->status = $finalStatus ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        }

        $this->updateVerificationTokens($tokens);
    }

    /**
     * Verify TXT record, update domain status accordingly.
     *
     * @return mixed
     */
    public function verifyIdentity()
    {
        $tokens = $this->getVerificationTokens();
        $identityToken = $tokens['identity']['value'];
        $identityHostname = $tokens['identity']['name'];

        $results = collect(dns_get_record($identityHostname, DNS_TXT));
        $results = $results->where('type', 'TXT')
                           ->whereIn('txt', [$identityToken, doublequote($identityToken)]);

        return $results->isEmpty() ? false : true;
    }

    /**
     * Verify DKIM record, update domain status accordingly.
     *
     * @return mixed
     */
    public function verifyDkim()
    {
        $possibles = collect([$this->getDnsDkimConfigWithoutQuote(), $this->getDnsDkimConfig(), $this->getEscapedDnsDkimConfig()]);
        $possibles = $possibles->map(function ($item, $key) {
            return preg_replace('/\s+/', '', $item);
        });

        $fqdn = sprintf('%s.%s', $this->getDkimSelector(), $this->name);
        $results = collect(dns_get_record($fqdn, DNS_TXT))->where('type', 'TXT')->map(function ($item, $key) {
            return preg_replace('/\s+/', '', $item['txt']);
        });
        $results = $results->intersect($possibles);

        return $results->isEmpty() ? false : true;
    }

    /**
     * Verify DKIM record, update domain status accordingly.
     *
     * @return mixed
     */
    public function verifySpf(string $ipOrHostname)
    {
        $result = spfcheck($ipOrHostname, $this->name);
        return $result == SPFCheck::RESULT_PASS;
    }

    /**
     * Get DKIM selector.
     *
     * @return string
     */
    public function getDkimSelector()
    {
        if (!empty($this->dkim_selector)) {
            return $this->dkim_selector.'._domainkey';
        } else {
            return Setting::get('dkim_selector').'._domainkey';
        }
    }

    /**
     * Get DKIM selector parts.
     *
     * @return string
     */
    public function getDkimSelectorParts()
    {
        return explode('.', $this->getDkimSelector());
    }

    /**
     * Get the full DKIM host name (including domain name).
     *
     * @return string
     */
    public function getFullDkimHostName()
    {
        return "{$this->getDkimSelector()}.{$this->name}.";
    }

    /**
     * Set DKIM selector.
     *
     * @return string
     */
    public function setDkimSelector($dkim_selector)
    {
        if (preg_match('/^[a-z0-9]{1,24}$/', $dkim_selector)) {
            $this->dkim_selector = $dkim_selector;
            $this->save();

            return true;
        }

        return false;
    }

    // The second parameter is required
    // There are two types of domain verification
    // + Application verified
    // + Sending server verified (second parameter)
    public function createFromArray($attributes, $server)
    {
        // Default values for a NEW domain
        $this->status = self::STATUS_INACTIVE;

        // Get values from array
        $this->fill($attributes);

        // Validation
        $validator = Validator::make($this->getAttributes(), self::rules());

        // Additional unique validation
        $validator->after(function ($validator) {
            if (empty($this->name)) {
                return; // already catched by validation rules (name must be present!)
            }

            // Okay, if name is valid, then check if it is unique
            $notUniq = $this->customer->sendingDomains()->where('name', $this->name)->exists();
            if ($notUniq) {
                $validator->errors()->add('name', "A sending domain with name `{$this->name}` already exists");
            }
        });

        // IMPORTANT: do not call fails() again in the controller, use is_null() with the second parameter
        if ($validator->fails()) {
            return [ $validator, null ];
        }

        DB::transaction(function () use ($server) {
            // Generate dkim keys
            // Used for signing emails
            $this->generateDkimKeys();

            // Save the domain record itself
            $this->save();

            if (!is_null($server)) {
                $this->sendingServer()->associate($server);
            }

            // Verify against server if any
            $this->startVerifying();
        });

        return [ $validator, $this ];
    }

    public function isAllowedBy($server)
    {
        if (!$server->allowOtherSendingDomains() && $this->sending_server_id != $server->id) {
            return false;
        } else {
            return true;
        }
    }

    public function isIdentityVerified()
    {
        return $this->getVerificationTokens()['results']['identity'] == true;
    }

    public function isDkimVerified()
    {
        return $this->getVerificationTokens()['results']['dkim'] == true;
    }

    public function isSpfNeeded()
    {
        $tokens = $this->getVerificationTokens();
        return array_key_exists('spf', $tokens['results']);
    }

    public function isSpfVerified()
    {
        $tokens = $this->getVerificationTokens();
        if (!array_key_exists('spf', $tokens['results'])) {
            throw new Exception('SPF record does not exist');
        }
        return $tokens['results']['spf'] == true;
    }

    public function getVerificationTokens()
    {
        return json_decode($this->verification_token, true);
    }

    public function updateVerificationTokens($tokens)
    {
        $this->verification_token = json_encode($tokens);
        $this->save();
    }
}
