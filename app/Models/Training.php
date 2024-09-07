<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = ['coach_id', 'name', 'price'];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
