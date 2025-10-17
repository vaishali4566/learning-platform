<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AdminAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action_type',
        'description',
    ];

    // Relationship: AdminAction belongs to an admin (user)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
