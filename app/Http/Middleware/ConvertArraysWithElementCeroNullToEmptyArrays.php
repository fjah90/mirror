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
      $inputs = $this->convertArraysWithElementCeroNullToEmptyArrays($inputs);
      $request->replace($inputs);

      return $next($request);
    }

    private function convertArraysWithElementCeroNullToEmptyArrays($inputs){
      dd($inputs);
      foreach ($inputs as $key => $input) {
        if(is_array($input)){
          if(count($input)==1 && is_null($input[0])) $inputs[$key] = array();
          else $inputs[$key] = $this->convertArraysWithElementCeroNullToEmptyArrays($input);
        }
      }

      return $inputs;
    }
}
