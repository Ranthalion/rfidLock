<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MemberTier;
use App\Models\MemberStatus;
use App\Models\PaymentProvider;

class Member extends Model
{

	protected $fillable = ['name', 'email', 'rfid', 'expire_date', 'member_tier_id', 'member_status_id', 'payment_provider_id'];

	public function memberTier()
	{
		return $this->belongsTo(MemberTier::class);
	}
	
	public function memberStatus()
	{
		return $this->belongsTo(MemberStatus::class);
	}
	
	public function paymentProvider()
	{
		return $this->belongsTo(PaymentProvider::class);
	}
	
	public function resources()
	{
		return $this->belongsToMany(Resource::class);
	}
}
