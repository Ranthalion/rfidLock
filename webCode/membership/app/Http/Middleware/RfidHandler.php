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

            //Convert to 4 bytes
            $byte[0] = substr($rfid, 0, 2);
            $byte[1] = substr($rfid, 2, 2);
            $byte[2] = substr($rfid, 4, 2);
            $byte[3] = substr($rfid, 6, 2);
            
            $str = "";
            $str .= chr(hexdec($byte[0]));
            $str .= chr(hexdec($byte[1]));
            $str .= chr(hexdec($byte[2]));
            $str .= chr(hexdec($byte[3]));

            //md5 hash and base64 encode it.
            $rfid = base64_encode(md5($str, true));

            $input['rfid'] = $rfid;
            $request->replace($input);
        }

        return $next($request);
    }
}
