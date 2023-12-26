<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

//the trait can be used in any class to obtain the same functionality
trait CanLoadRelationships {
    public function loadRelationships(
        Model | EloquentBuilder | QueryBuilder | HasMany $for,
        ?array $relations = null
    ) : Model | EloquentBuilder | QueryBuilder | HasMany {

        //we may pass the parameter for relations or we need to give the values here as most of the relations used in the eventcontroller are same

        $relations = $relations ?? $this->relations ?? [];

        //check every relation whether it is present in the query string
        //only load relations if present using the when() funtion
        foreach ($relations as $relation) {
            $for->when( $this->shouldIncludeRelation($relation),
            //the arrow function below adds the relations only when they are present in the query
            //the condition checks whether the instace is a model and use load() accordingly
            fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation));
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation) : bool {
        //we took the value of include from the query
        $include = request()->query('include');

        if(!$include) return false;

        //we trimmed the elements of the array to reduce errors
        $relations = array_map('trim',explode(',', $include));
        
        //we return whether the relation is present in the array or not
        return in_array($relation, $relations);
    }

}