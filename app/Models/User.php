<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; 
use App\Notifications\CustomResetPassword; 


class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable; // 

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_image',
        'city',
        'country',
        'email_verified_at',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

}










// Course Progress Tracking & Certificate System

// (Based on: 1 Lesson = 1 Content)

// 🔵 PHASE 0 — FOUNDATION (CLARITY)

// Nothing to code, only decisions

// Module 0.1 — Content Mapping ✅ DONE
// Course
//  └─ Lesson (video | text | quiz | practice-test)


// ✔ Lesson itself is the progress unit

// Module 0.2 — Completion Rules

// Define when a lesson is “completed”

// Lesson Type	Completion Condition
// Video	Watched ≥ 90%
// Text	User clicks “Mark as Completed”
// Quiz	Quiz submitted
// Practice Test	Test attempted

// 📌 Output: One rule per lesson type

// 🟢 PHASE 1 — DATABASE (CORE)
// Module 1.1 — user_lesson_progress Table

// Track lesson completion per user

// Output:

// One row = one lesson completion

// Module 1.2 — Completion Events

// Automatically mark lessons complete

// Triggers:

// Video finished

// Text marked done

// Quiz submitted

// Practice test completed

// 🟡 PHASE 2 — COURSE PROGRESS LOGIC
// Module 2.1 — Total Lessons Count

// How many lessons in a course

// Module 2.2 — Completed Lessons Count

// How many lessons user finished

// Module 2.3 — Progress Percentage
// progress = (completed / total) * 100

// Module 2.4 — Progress API
// GET /courses/{id}/progress

// 🟠 PHASE 3 — FRONTEND PROGRESS UI
// Module 3.1 — Course Progress Bar

// Dynamic

// Animated

// Module 3.2 — Lesson Status UI

// Completed ✔

// Pending ⏳

// 🔴 PHASE 4 — COURSE COMPLETION
// Module 4.1 — Completion Detection
// If progress == 100%

// Module 4.2 — Course Completion Record

// Prevent re-completion issues

// 🟣 PHASE 5 — CERTIFICATE SYSTEM
// Module 5.1 — Certificates Table

// Store issued certificates

// Module 5.2 — Certificate Number Logic

// Unique & verifiable

// Module 5.3 — Certificate Template

// PDF / HTML

// Module 5.4 — Generate Certificate

// Auto-generate on completion

// Module 5.5 — Download / View Certificate

// User access

// 🔵 PHASE 6 — POLISH & SCALE (Optional)
// Module 6.1 — Edge Cases

// Retake quiz

// Reset lesson

// Recalculate progress

// Module 6.2 — Performance

// Cache progress

// Reduce joins

// Module 6.3 — Admin Controls

// Reset progress

// Revoke certificate

// 🏁 HOW WE WILL EXECUTE

// ✔ One module at a time
// ✔ DB → Backend → Frontend
// ✔ Move next only when you say “next”