<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentProvider;
use App\Models\Payment;
use App\Models\Member;

class Customer extends Model
{

	protected $fillable = ['name', 'email', 'payment_provider_id', 'last_payment_date', 'last_payment_amount', 'last_payment_status', 'next_payment_date'];
	
	public function paymentProvider()
	{
		return $this->belongsTo(PaymentProvider::class);
	}

	public function members()
	{
		return $this->hasMany('App\Models\Member');
	}
	
	public function payments()
	{
		return $this->hasMany('App\Models\Payment');
	}
}
