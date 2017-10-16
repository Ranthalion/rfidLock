<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EventType;

class EventLog extends Model
{
	protected $guarded = [];

	protected $table = 'event_log';
    
    //
    public function eventType()
	{
		return $this->belongsTo(EventType::class);
	}
}
