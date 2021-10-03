<?php

namespace Rosendito\Taxonomies;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Rosendito\Taxonomies\Helpers\EloquentHelper;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTaxonomies
{
    /**
     * Get terms
     *
     * @return MorphToMany
     */
    public function terms(): MorphToMany
    {
        return $this->morphToMany(Term::class, 'taggable');
    }

    /**
     * Get terms associated by taxonomy
     *
     * @param int|string|Taxonomy $taxonomy
     * @return Collection|null
     */
    public function getTerms($taxonomy): ?Collection
    {
        return $this->terms()->whereHas(
            'taxonomy',
            EloquentHelper::searchBy($taxonomy, Taxonomy::class)
        )->get();
    }

    /**
     * Get one term associated by taxonomy
     *
     * @param int|string|Taxonomy $taxonomy
     * @return Term|null
     */
    public function getTerm($taxonomy): ?Term
    {
        return $this->terms()->whereHas(
            'taxonomy',
            EloquentHelper::searchBy($taxonomy, Taxonomy::class)
        )->first();
    }

    /**
     * Get one term model from database by taxonomy
     *
     * @param int|string|Taxonomy $taxonomy
     * @param int|string|Term $term
     * @return Term|null
     */
    private function getTermModel($taxonomy, $term): ?Term
    {
        return EloquentHelper::searchByQuery(
            Term::query(),
            $term,
            Term::class
        )
            ->whereHas(
                'taxonomy',
                EloquentHelper::searchBy($taxonomy, Taxonomy::class)
            )
            ->first();
    }

    /**
     * Add multiples terms
     *
     * @param int|string|Taxonomy $taxonomy
     * @param array[int|string|Term]|Collection[int|string|Term] $terms
     */
    public function addTerms($taxonomy, $terms)
    {
        $taxonomy = EloquentHelper::searchByQuery(
            Taxonomy::query(),
            $taxonomy,
            Taxonomy::class
        )->first();

        if (!$terms instanceof BaseCollection) {
            $terms = collect($terms);
        }

        $terms = $terms->map(
            fn($term) => $this->getTermModel($taxonomy, $term)
        )->filter()->pluck('id');

        return $this->terms()->syncWithoutDetaching($terms);
    }

    /**
     * Add one term
     *
     * @param int|string|Taxonomy $taxonomy
     * @param int|string|Term $term
     */
    public function addTerm($taxonomy, $term)
    {
        return $this->addTerms($taxonomy, [$term]);
    }

    /**
     * Add one term and detaching older term (if exist term associated)
     *
     * @param int|string|Taxonomy $taxonomy
     * @param int|string|Term $term
     */
    public function addTermAndDetachingOlder($taxonomy, $term)
    {
        $oldTerm = $this->getTerm($taxonomy);

        if ($oldTerm) {
            $this->terms()->detach($oldTerm->id);
        }

        return $this->addTerm($taxonomy, $term);
    }

    /**
     * Remove terms by taxonomy, pass third param to true for removeAll
     *
     * @param int|string|Taxonomy $taxonomy
     * @param array[int|string|Term]|Collection[int|string|Term]|null $terms
     * @param boolean $removeAll
     */
    public function removeTerms($taxonomy, $terms, bool $removeAll = false)
    {
       if ($removeAll) {
           return $this->terms()->detach(
            $this->getTerms($taxonomy)->pluck('id')
           );
       }

       if (!$terms instanceof BaseCollection) {
           $terms = collect($terms);
       }

       $terms = $terms->map(
            fn($term) => $this->getTermModel($taxonomy, $term)
       )->filter()->pluck('id');

       return $this->terms()->detach($terms);
    }

    /**
     * Remove one term by taxonomy
     *
     * @param int|string|Taxonomy $taxonomy
     * @param int|string|Term $term
     */
    public function removeTerm($taxonomy, $term)
    {
        return $this->removeTerms($taxonomy, [$term]);
    }

    /**
     * Scope for get resources where term
     *
     * @param Builder $query
     * @param int|string|Taxonomy $taxonomy
     * @param @param int|string|Term $term
     * @return void
     */
    public function scopeHasTerm(Builder $query, $taxonomy, $term): void
    {
        $query->whereHas('terms', function (Builder $q) use ($taxonomy, $term) {
            $q->whereHas(
                'taxonomy',
                EloquentHelper::searchBy($taxonomy, Taxonomy::class)
            );
            $q->where('name', $term);
        });
    }

    /**
     * Scope for get resources where does'nt have term
     *
     * @param Builder $query
     * @param int|string|Taxonomy $taxonomy
     * @param int|string|Term $term
     * @return void
     */
    public function scopeDoesntHaveTerm(Builder $query, $taxonomy, $term): void
    {
        $query->whereDoesntHave('terms', function (Builder $q) use ($taxonomy, $term) {
            $q->whereHas(
                'taxonomy',
                EloquentHelper::searchBy($taxonomy, Taxonomy::class)
            );
            $q->where('name', $term);
        });
    }
}
