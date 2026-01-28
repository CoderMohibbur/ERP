<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role as SpatieRole;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    // ✅ This enables syncRoles(), assignRole(), hasRole(), can(), etc.
    use HasRoles;

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
     * Optional legacy role relationship.
     *
     * NOTE:
     * - Spatie roles are attached via model_has_roles pivot (HasRoles trait),
     *   NOT via users.role_id.
     * - If your database has users.role_id and you still need it, keep this relation.
     * - If you don't use role_id anymore, you can remove this method later.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(SpatieRole::class, 'role_id');
    }

    /**
     * Created by (admin)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updated by (admin)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active users
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
