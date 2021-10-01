<?php

namespace Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Rosendito\Taxonomies\HasTaxonomies;

class Post extends Model
{
    use HasTaxonomies;

    /**
     * Attributes can be fillable
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'body'
    ];
}
