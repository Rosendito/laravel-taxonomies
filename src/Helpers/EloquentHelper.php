<?php

namespace Rosendito\Taxonomies\Helpers;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EloquentHelper
{
    /**
     * Search by multiples cases
     *
     * @param int|string|Model $searchable
     * @param $model
     * @return Closure
     */
    public static function searchBy($searchable, $model): Closure
    {
        if (is_int($searchable)) {
            return fn(Builder $q) => $q->where('id', $searchable);
        } elseif ($searchable instanceof $model) {
            return fn(Builder $q) => $q->where('id', $searchable->id);
        } else {
            return fn(Builder $q) => $q->where('name', $searchable);
        }
    }

    /**
     * Search by multiples cases passing the query builder
     *
     * @param Builder $query
     * @param int|string|Model $searchable
     * @param $model
     * @return Builder
     */
    public static function searchByQuery(Builder $query, $searchable, $model): Builder
    {
        return self::searchBy($searchable, $model)($query);
    }
}
