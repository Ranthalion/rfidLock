<?php

namespace App\Http\Middleware;

use Closure;
//use /DateTime;

class DateHandler
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
        
        if ($request->has('expire_date')) {
            $input = $request->all();
            $d = \DateTime::createFromFormat('m/d/Y', $input['expire_date']);
            if ($d != false)
            {
                $input['expire_date'] = $d->format('Y-m-d');    
            }            
            $request->replace($input);
        }
                
        return $next($request);
    }
}
