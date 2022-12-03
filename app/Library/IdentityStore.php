<?php

namespace Acelle\Library;

class IdentityStore
{
    private $identities;
    private $defaultIdentityAttributes = [
        'Selected' => true,
        'VerificationStatus' => 'Pending',
        'UserId' => null,
        'UserName' => null,
    ];

    public const VERIFICATION_STATUS_SUCCESS = 'Success';

    /* *
     * Identity data coming in form of php array
     * { 'key1': { attributes }, 'key2': { attributes }.... }
     * The required attributes per key include:
     *     1 Selected
     *     2 VerificationStatus
     *     3 UserId
     *     4 UserName
     */
    public function __construct($data)
    {
        $this->identities = $this->normalize($data);
    }

    private function normalize($data)
    {
        if (is_string($data)) {
            $data = [ $data => [] ];
        }

        foreach ($data as $key => $attributes) {
            $attributes = array_merge($this->defaultIdentityAttributes, $attributes);
            $attributes['VerificationStatus'] = $this->mapVerificationStatus($attributes['VerificationStatus']);
            $data[$key] = $attributes;
        }

        return $data;
    }

    private function mapVerificationStatus($status)
    {
        if ($status === true) {
            return 'Success';
        } elseif ($status === false) {
            return 'Pending';
        }

        $map = [
            'Success' => 'Success',
            'Pending' => 'Pending',
            'Failed' => 'Pending',
            'TemporaryFailure' => 'Pending',
            'NotStarted' => 'Pending',
        ];

        if (!array_key_exists($status, $map)) {
            throw new \Exception("Status '{$status}' of verification status not recognized by Acelle");
        }

        return $map[$status];
    }

    public function update($data, $replace = true)
    {
        // IMPORTANT:
        // + $replace=true means replace
        // + replace=false means only update, and merge, keep existing identites that are not present in the new set

        // $data should come as [ 'key1' => [ attributes], 'key2' => ... ]
        $newIdentities = $this->normalize($data);

        // Note: this method will take new identities only
        // Any existing identity that is not present in the new identities set will be deleted
        foreach ($newIdentities as $key => $attributes) {
            if ($this->identityExists($key)) {
                // Restore old Selected
                $attributes['Selected'] = $this->getAttributesByKey($key)['Selected'];

                // TRUST new values for other keys
                // $attributes['UserId'] = TRUST
                // $attributes['UserName'] = TRUST
                // $attributes['VerificationStatus'] = TRUST

                $attributes = array_merge($this->getAttributesByKey($key), $attributes);

                $newIdentities[$key] = $attributes;
            }
        }


        if ($replace) {
            // replace this object data
            $this->identities = $newIdentities;
        } else {
            // merge this object data
            $this->identities = array_merge($this->identities, $newIdentities);
        }

        return $this;
    }

    public function select($array)
    {
        // IMPORTANT any existing identiy that is not present in $array will be set Selected = false
        foreach ($this->get() as $key => $attributes) {
            if (in_array($key, $array)) {
                $this->identities[$key]['Selected'] = true;
            } else {
                $this->identities[$key]['Selected'] = false;
            }
        }

        return $this;
    }

    public function identityExists($key)
    {
        return array_key_exists($key, $this->identities);
    }

    public function getAttributesByKey($key)
    {
        return $this->identities[$key];
    }

    public function get($conditions = null)
    {
        if (is_null($conditions)) {
            return $this->identities;
        }

        $filtered = array_filter($this->identities, function ($attributes, $key) use ($conditions) {
            return empty(array_diff_assoc($conditions, $attributes)); // if attributes satisfy conditions
        }, ARRAY_FILTER_USE_BOTH);

        return $filtered;
    }

    public function add($data)
    {
        // $identity must be an array [ 'key' => [ attributes ]]
        $normalized = $this->normalize($data);
        $this->identities = array_merge($this->identities, $normalized);
        return $this;
    }

    public function remove($key)
    {
        // $identity must be an array [ 'key' => [ attributes ]]
        if (array_key_exists($key, $this->identities)) {
            unset($this->identities[$key]);
        }
        return $this;
    }
}
