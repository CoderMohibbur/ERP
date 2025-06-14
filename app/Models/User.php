<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // Jetstream
        'name',
        'email',
        'password',
        'profile_photo_path',
        'current_team_id',

        // Custom fields
        'role_id',
        'is_active',
        'last_login_at',
        'timezone',
        'language',
        'ip_address',
        'login_device',
        'user_agent',
        'profile_completed',
        'force_password_reset',
        'last_password_change_at',
        'api_limit',
        'session_token',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'session_token',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'last_login_at'           => 'datetime',
            'last_password_change_at' => 'datetime',
            'is_active'               => 'boolean',
            'profile_completed'       => 'boolean',
            'force_password_reset'    => 'boolean',
            'api_limit'               => 'integer',
            'password'                => 'hashed',
        ];
    }

    /**
     * Role relationship (optional)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Created by (admin)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updated by (admin)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
