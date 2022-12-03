<?php

namespace Acelle\Library\Traits;

trait HasUid
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (is_null($item->uid)) {
                $item->generateUid();
            }
        });
    }

    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    public function generateUid()
    {
        $this->uid = uniqid();
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }
}
