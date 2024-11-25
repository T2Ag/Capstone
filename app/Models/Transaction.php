<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function isActive()
    {
        $today = now()->startOfDay();
        return $today->between($this->start_date, $this->end_date);
    }


    public function scopeFilter($query, array $filters) 
    {
        if (isset($filters['year_filter']) && $filters['year_filter'] !== 'all') {
            $query->whereYear('transaction_date', $filters['year_filter']);
        }
    
        if (isset($filters['month_filter']) && $filters['month_filter'] !== 'all') {
            $query->whereMonth('transaction_date', $filters['month_filter']);
        }

        if (isset($filters['registration_type']) && $filters['registration_type'] !== 'all') {
            $query->whereHas('client.registration', function($q) use ($filters) {
                $q->where('type', $filters['registration_type']);
            });
        }

        if (isset($filters['payment_method']) && $filters['payment_method'] !== 'all') {
            $query->whereHas('client.payment_method', function($q) use ($filters) {
                $q->where('type', $filters['payment_method']);
            });
        }

        if ($filters['search'] ?? false) {
            $query->whereHas('client', function ($query) use ($filters) {
                $search = $filters['search']; // Get search from filters instead of request()
                $query->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ['%' . $search . '%']);
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
