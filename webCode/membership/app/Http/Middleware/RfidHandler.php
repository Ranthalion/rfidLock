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
            
            $rfid = $input['rfid'];
            
            //Convert to hex
            $rfid = dechex($rfid);
            
            //Convert back to string and pad with '0' to 8 characters 
            $rfid = str_pad(strtoupper($rfid), 8, '0', STR_PAD_LEFT);

            //md5 hash and base64 encode it.
            $rfid = base64_encode(md5($rfid, true));

            $input['rfid'] = $rfid;
            $request->replace($input);
        }

        return $next($request);
    }
}
