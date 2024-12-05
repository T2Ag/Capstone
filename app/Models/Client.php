<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function toDoLists()
    {
        return $this->hasMany(TodoList::class);
    }

    public function isMonthlyActive()
    {
        // Find the latest monthly transaction for this client
        $latestMonthlyTransaction = $this->transactions()
            ->whereHas('payment_method', function($query) {
                $query->where('type', 'monthly');
            })
            ->latest('start_date')
            ->first();

        // If no monthly transaction exists, return false
        if (!$latestMonthlyTransaction) {
            return false;
        }

        // Check if today is between the start and end dates of the latest monthly transaction
        $today = now()->startOfDay();
        
        // Ensure start_date and end_date are not null before using between()
        if ($latestMonthlyTransaction->start_date === null || $latestMonthlyTransaction->end_date === null) {
            return false;
        }

        return $today->between(
            Carbon::parse($latestMonthlyTransaction->start_date),
            Carbon::parse($latestMonthlyTransaction->end_date)
        );
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

        if (isset($filters['payment_method']) && $filters['payment_method'] !== 'all') {
            $query->whereHas('payment_method', function($q) use ($filters) {
                $q->where('type', $filters['payment_method']);
            });
        }

        if (isset($filters['member_filter']) && $filters['member_filter'] !== 'all') {
            if ($filters['member_filter'] === 'member') {

                $query->whereNotNull('user_id');
            } elseif ($filters['member_filter'] === 'non-member') {

                $query->whereNull('user_id');
            }
        }

        if (isset($filters['active_filter']) && $filters['active_filter'] !== 'all') {
            $today = now()->toDateString();
        
            // Filter for "expired" clients
            if ($filters['active_filter'] === 'expired') {
                $query->whereHas('payment_method', function ($q) {
                    $q->where('type', 'monthly'); 
                })->where(function ($q) use ($today) {
                    $q->doesntHave('transactions')
                        ->orWhereHas('transactions', function ($q) use ($today) {
                            $q->whereHas('payment_method', function ($q) {
                                $q->where('type', 'monthly'); 
                            })
                            ->orderBy('transaction_date') 
                            ->where('end_date', '<', $today);
                        });
                });
            }
            // Filter for "active" clients
            if ($filters['active_filter'] === 'active') {
                $query->whereHas('payment_method', function ($q) {
                    $q->where('type', 'monthly'); 
                })->where(function ($q) use ($today) {
                    $q->whereHas('transactions', function ($q) use ($today) {
                        $q->whereHas('payment_method', function ($q) {
                            $q->where('type', 'monthly'); 
                        })
                        ->orderBy('transaction_date') 
                        ->where('start_date', '<=', $today) 
                        ->where('end_date', '>=', $today);
                    });
                });
            }
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
