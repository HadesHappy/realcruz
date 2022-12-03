<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class TemplateCategory extends Model
{
    use HasUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The template that belong to the categories.
     */
    public function templates()
    {
        return $this->belongsToMany('Acelle\Model\Template', 'templates_categories', 'category_id', 'template_id');
    }
}
