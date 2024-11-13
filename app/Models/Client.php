<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function trainingTransaction()
    {
        return $this->hasMany(TrainingTransaction::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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
            $query->whereHas('registration', function($q) use ($filters) {
                $q->where('type', $filters['registration_type']);
            });
        }

        if (isset($filters['member_filter']) && $filters['member_filter']) {
            $query->whereNotNull('user_id');
        }

        if (isset($filters['date_filter']) && $filters['date_filter'] !== '') {
            $query->whereDate('created_at', $filters['date_filter']);
        }

        if ($filters['search'] ?? false) {
            $query->where(function ($query) {
                $search = request('search');
                $query->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere(DB::raw("first_name || ' ' || last_name"), 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

}
