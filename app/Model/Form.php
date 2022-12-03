<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Traits\HasTemplate;
use Validator;
use Acelle\Model\Template;

class Form extends Model
{
    use HasFactory;
    use HasTemplate;
    use HasUid;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function template()
    {
        return $this->belongsTo('Acelle\Model\Template');
    }

    public static function newDefault($customer)
    {
        $form = new self();
        $form->name = trans('messages.form.untitled');
        $form->status = self::STATUS_DRAFT;
        $form->customer_id = $customer->id;

        return $form;
    }

    public function createFromArray($params)
    {
        $validator = Validator::make($params, [
            'name' => 'required',
            'template_uid' => 'required',
            'mail_list_uid' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        $this->mail_list_id = \Acelle\Model\MailList::findByUid($params['mail_list_uid'])->id;
        $this->save();

        // set template
        $selectedTemplate = Template::findByUid($params['template_uid']);
        $this->changeTemplate($selectedTemplate);

        return $validator;
    }

    public function changeTemplate($selectedTemplate)
    {
        $this->setTemplate($selectedTemplate, trans('messages.form.template_name', ['name' => $this->name]));
    }

    public function scopeFilter($query, $params)
    {
        // list
        if (!empty($params['mail_list_uid'])) {
            $query = $query->where('mail_list_id', '=', \Acelle\Model\MailList::findByUid($params['mail_list_uid'])->id);
        }

        // list
        if (!empty($params['website_uid'])) {
            $query = $query->byWebsite(\Acelle\Model\Website::findByUid($params['website_uid']));
        }
    }

    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%'.trim($keyword).'%');
        }
    }

    public function updateMetadata($data)
    {
        $metadata = (object) array_merge((array) $this->getMetadata(), $data);
        $this['metadata'] = json_encode($metadata);

        $this->save();
    }

    public function getMetadata($name=null)
    {
        if (!$this['metadata']) {
            return json_decode('{}', true);
        }

        $data = json_decode($this['metadata'], true);

        if ($name != null) {
            if (isset($data[$name])) {
                return $data[$name];
            } else {
                return null;
            }
        } else {
            return $data;
        }
    }

    public function saveSettingsFromArray($params)
    {
        // if has list
        if ($params['mail_list_uid']) {
            $this->mail_list_id = \Acelle\Model\MailList::findByUid($params['mail_list_uid'])->id;
        }

        if ($params['name']) {
            $this->name = $params['name'];
        }

        //
        $this->updateMetadata([
            'overlay_opacity' => $params['overlay_opacity'],
            'display' => $params['display'],
            'wait_time' => $params['wait_time'],
            'element_id' => $params['element_id'],
        ]);
    }

    public function connect($site)
    {
        $this->updateMetadata([
            'website_uid' => $site->uid,
        ]);
    }

    public function getWebsite()
    {
        return $this->getMetadata('website_uid') ? \Acelle\Model\Website::findByUid($this->getMetadata('website_uid')) : null;
    }

    public function disconnect()
    {
        $this->updateMetadata([
            'website_uid' => null,
        ]);
    }

    public static function scopeByWebsite($query, $website)
    {
        $query = $query->where('metadata', 'like', '%'.$website->uid.'%');
    }

    public function getBuilderTags()
    {
        $result = [];

        $tags = [
            ['name' => 'LIST_NAME', 'required' => false],
            ['name' => 'CURRENT_YEAR', 'required' => false],
            ['name' => 'CURRENT_MONTH', 'required' => false],
            ['name' => 'CURRENT_DAY', 'required' => false],
        ];

        foreach ($tags as $tag) {
            $result[] = [
                'type' => 'label',
                'text' => '{'.$tag['name'].'}',
                'tag' => '{'.$tag['name'].'}',
                'required' => true,
            ];
        }

        return $result;
    }

    public function renderedContent($content=null)
    {
        if (!$content) {
            $content = $this->template->content;
        }

        // BAISC INFO
        $content = str_replace('{LIST_NAME}', $this->mailList->name, $content);
        $content = str_replace('{CURRENT_YEAR}', date('Y'), $content);
        $content = str_replace('{CURRENT_MONTH}', date('m'), $content);
        $content = str_replace('{CURRENT_DAY}', date('d'), $content);

        return $content;
    }

    public function publish()
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->save();
    }

    public function unpublish()
    {
        $this->status = self::STATUS_DRAFT;
        $this->save();
    }

    public static function scopePublished($query)
    {
        $query = $query->where('status', '=', self::STATUS_PUBLISHED);
    }

    public function isPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }
}
