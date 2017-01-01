<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentProvider;

class Payment extends Model
{

	protected $fillable = ['date', 'amount', 'status', 'payment_provider_id'];
	
	public function paymentProvider()
	{
		return $this->belongsTo(PaymentProvider::class);
	}

	public function member()
	{
		return $this->belongsTo('App\Models\Member');
	}
}
