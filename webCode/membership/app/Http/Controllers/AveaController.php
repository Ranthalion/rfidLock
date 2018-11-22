<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentProviders\QuickbooksService;
use App\Services\PaymentProviders\PayPalService;

use App\Services\PaymentImporter;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

use App\Models\Member;
use App\Models\MemberTier;
use App\Models\PaymentProvider;
use App\Models\Resource;
use App\Models\EventLog;
use App\Models\EventType;

use App\Events\MemberAdded;
use Session;
use DateTime;
use DateInterval;

use App\Services\MailChimp;
use App\Services\CustomerDAL;

class AveaController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {

  }

  public function verify(Request $request)
  {
    $command = Input::get('cmd');
    $mode = Input::get('mode');
    $rfid = Input::get('code');

    //Represents the IP address of the reader.
    $resourceId = Input::get('id');
    //32-bit unique ID of the reader.
    $deviceId = Input::get('deviceid');

    $now = time(); // stamp the current time
    $st = date('Y-m-d H:i:s',$now); // set the datetime string to correct format

    $payload = "";

    switch($command){

      //Power Up
      case "PU":
        $payload = "CK={$st}";
        $payload .= 'BEEP=0';

        $event = new EventLog;
        $event->rfid = $resourceId; //or should it be $deviceId?
        $event->data = "Power On";
        $event->event_type_id = EventType::find(4)->id;
        $event->save();
      break;

      //Card was read
      case "CO":

        $member = Member::where('rfid', $this->encode($rfid))
        ->whereHas('resources', function($q) use ($resourceId)
        {
          $q->where('device_identifier', $resourceId);
        })
        ->first();

        $event = new EventLog;
        $event->rfid = $rfid;
        $event->data = $resourceId;

        if ($member)
        {
          $duration  = config("app.grant_duration");
          $duration = str_pad($duration, 2, "0", STR_PAD_LEFT);
          $event->event_type_id = EventType::find(1)->id;
          $payload = "GRNT=" . $duration;
          $payload .= "BEEP=1";
        }
        else
        {
          $payload = "DENY";
          $payload .= "BEEP=0";
          $event->event_type_id = EventType::find(2)->id;
        }
        $event->save();
      break;

      //Heart Beat
      case "HB":
        $payload = "CK={$st}";
        $event = new EventLog;
        $event->rfid = $resourceId; //or should it be $deviceId?
        $event->data = mb_strimwidth($request->fullUrl(), 0, 254);
        $event->event_type_id = EventType::find(5)->id;
        $event->save();
      break;

      //Swich changed
      case "SW":
        //TODO: [ML] Determine if a switch on the device should mean something
        $event = new EventLog;
        $event->rfid = $resourceId; //or should it be $deviceId?
        $event->data = mb_strimwidth($request->fullUrl(), 0, 254);
        $event->event_type_id = EventType::find(6)->id;
        $event->save();
      break;

      //Ping
      case "PG":
        $payload="HB=0160";
        $event = new EventLog;
        $event->rfid = $resourceId; //or should it be $deviceId?
        $event->data = mb_strimwidth($request->fullUrl(), 0, 254);
        $event->event_type_id = EventType::find(7)->id;
        $event->save();
      break;

    }

    return "<html>\n<body>\n<AVEA>{$payload}</AVEA>\n</body>\n</html>\n";

  }

  private function encode($value)
  {
    $rfid = $value;
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
    return $rfid;
  }

}
