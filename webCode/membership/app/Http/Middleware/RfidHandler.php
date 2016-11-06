<?php

namespace App\Http\Middleware;

use Closure;

class RfidHandler
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
        if ($request->has('rfid')) {
            $input = $request->all();
            $input['rfid'] = base64_encode(md5($input['rfid'], true));
            $request->replace($input);
        }

        return $next($request);
    }
}
