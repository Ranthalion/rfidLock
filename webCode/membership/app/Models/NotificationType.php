<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
	public $timestamps = false; 
	
    protected $fillable = ['description'];

    public function memberNotifications()
	{
		return $this->hasMany(MemberNotification::class);
	}
}
