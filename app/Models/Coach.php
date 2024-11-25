<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coach extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

    public function scopeFilter($query, array $filters) 
    {

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
