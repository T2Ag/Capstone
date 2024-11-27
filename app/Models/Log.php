<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function scopeFilter($query, array $filters) 
    {
        if (isset($filters['year_filter']) && $filters['year_filter'] !== 'all') {
            $query->whereYear('created_at', $filters['year_filter']);
        }
    
        if (isset($filters['month_filter']) && $filters['month_filter'] !== 'all') {
            $query->whereMonth('created_at', $filters['month_filter']);
        }

        if (isset($filters['registration_type']) && $filters['registration_type'] !== 'all') {
            $query->whereHas('client.registration', function($q) use ($filters) {
                $q->where('type', $filters['registration_type']);
            });
        }

        if (isset($filters['payment_method']) && $filters['payment_method'] !== 'all') {
            $query->whereHas('payment_method', function($q) use ($filters) {
                $q->where('type', $filters['payment_method']);
            });
        }

        // if (isset($filters['member_filter']) && $filters['member_filter']) {
        //     $query->whereNotNull('user_id');
        // }

        // if (isset($filters['date_filter']) && $filters['date_filter'] !== '') {
        //     $query->whereDate('created_at', $filters['date_filter']);
        // }

        return $query;
    }

}
