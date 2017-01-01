<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NotificationType;
use App\Models\Mmeber;

class MemberNotification extends Model
{
	public $timestamps = false; 
	
	protected $fillable = ['member_id', 'notification_type_id', 'notification_date'];

    public function notificationType()
	{
		return $this->belongsTo(NotificationType::class);
	}
	
	public function member()
	{
		return $this->belongsTo(Member::class);
	}
}
