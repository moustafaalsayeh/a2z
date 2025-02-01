<?php

namespace App\Helpers;

Trait Filterable
{
   public function scopeFilter($query, QueryFilter $filters)
   {
       return $filters->apply($query);
   }
}


