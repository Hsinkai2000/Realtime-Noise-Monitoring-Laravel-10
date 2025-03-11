<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $casts = [
        'id' => 'integer',
        'project_id' => 'integer', // Ensure project_id is cast as integer
        'sign_in_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reset_password_sent_at' => 'datetime',
        'remember_created_at' => 'datetime',
        'current_sign_in_at' => 'datetime',
        'last_sign_in_at' => 'datetime',
    ];

    protected $fillable = [
        'user_type',
        'project_id', // Ensure project_id is fillable
        'username',
        'password',
        'reset_password_token',
        'reset_password_sent_at',
        'remember_created_at',
        'sign_in_count',
        'current_sign_in_at',
        'last_sign_in_at',
        'current_sign_in_ip',
        'last_sign_in_ip',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function isAdmin()
    {
        return $this->user_type == 'admin' ? true : false;
    }
}
