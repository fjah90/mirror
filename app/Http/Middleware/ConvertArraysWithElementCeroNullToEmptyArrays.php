<?php

namespace App\Http\Middleware;

use Closure;

class ConvertArraysWithElementCeroNullToEmptyArrays
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $inputs = $request->all();
      $replace = false;
      foreach ($inputs as $key => $input) {
        if(is_array($input) && count($input)==1 && is_null($input[0])){
          $inputs[$key] = array();
          $replace = true;
        }
      }

      if($replace) $request->replace($inputs);
      
      return $next($request);
    }
}
