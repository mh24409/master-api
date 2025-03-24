<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $builder;
    protected $request;
    protected $sortable=[];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function filter($arr)
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    public function sort($value)
    {
        $direction = 'asc';
        $attributes = explode(',', $value);
        foreach ($attributes as $attribute) {
            if (substr($attribute, 0, 1) === '-') {
                $direction = 'desc';
                $attribute = substr($attribute, 1);
            }
            if (!in_array($attribute, $this->sortable) && !array_key_exists($attribute, $this->sortable)) {
                continue;
            }
            $columnName = $this->sortable[$attribute] ?? $attribute;
            $this->builder->orderBy($columnName, $direction);
        }
    }
}
