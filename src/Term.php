<?php

namespace Rosendito\Taxonomies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Rosendito\Taxonomies\Helpers\EloquentHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Term extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'taxonomy_id',
        'parent_id',
        'name'
    ];

    /**
     * Get the taxonomy associated
     */
    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    /**
     * Get the term parent
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the term childs
     */
    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Scope for get terms by taxonomy
     *
     * @param Builder $query
     * @param int|string|Model $taxonomy
     *
     * @return void
     */
    public function scopeByTaxonomy(Builder $query, $taxonomy): void
    {
        $query->whereHas('taxonomy', EloquentHelper::searchBy($taxonomy, Term::class));
    }

    /**
     * Scope for get terms by parent
     *
     * @param Builder $query
     * @param int|string|Model $parent
     * @return void
     */
    public function scopeByParent(Builder $query, $parent): void
    {
        $query->whereHas('parent', EloquentHelper::searchBy($parent, Term::class));
    }
}
