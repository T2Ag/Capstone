<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
