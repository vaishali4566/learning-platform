<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;

class CourseFeedback extends Model
{
    use HasFactory;

    protected $table = 'course_feedback';

    protected $fillable = [
        'course_id',
        'user_id',
        'rating',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
