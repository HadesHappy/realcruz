<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class WpPost extends Model
{
    protected $connection= 'wordpress';

    public static function scopeProducts($query)
    {
        $query = $query->where('post_type', '=', 'product')
            ->where('post_status', '!=', 'auto-draft');
    }

    public function scopeSearch($query, $keyword)
    {
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $k) {
                $query = $query->where(function ($q) use ($k) {
                    $q->orwhere('wp_posts.post_title', 'like', '%'.strtolower($k).'%');
                });
            }
        }
    }
}
