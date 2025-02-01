<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query)
    {
        $this->builder = $query;
        foreach ($this->filters() as $name => $value) {
            method_exists($this, $name) ?
                call_user_func_array([$this, $name], array_filter([$value])) : '';
        }
        return $this->builder;
    }
    
    public function filters()
    {
        return $this->request->all();
    }
}
