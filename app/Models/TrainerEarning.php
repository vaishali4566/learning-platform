<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerEarning extends Model
{
    use HasFactory;

    protected $fillable = ['trainer_id', 'course_id', 'amount', 'source'];

    public function trainer() {
        return $this->belongsTo(Trainer::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
