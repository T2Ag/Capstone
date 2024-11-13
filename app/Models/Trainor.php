<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainor extends Model
{
    /** @use HasFactory<\Database\Factories\TrainorFactory> */
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingTransaction()
    {
        return $this->hasMany(TrainingTransaction::class);
    }

}
