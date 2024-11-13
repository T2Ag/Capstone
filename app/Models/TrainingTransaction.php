<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\TrainingTransactionFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'transaction_date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    // Helper method to check if the training is active
    public function isActive()
    {
        $today = now()->startOfDay();
        return $today->between($this->start_date, $this->end_date);
    }

    // Scope to get active training transactions
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Scope to get expired training transactions
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

}
