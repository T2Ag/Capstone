<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'gender', 'address', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
