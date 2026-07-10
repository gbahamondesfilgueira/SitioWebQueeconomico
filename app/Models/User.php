<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'role_id',
        'name',
        'email',
        'google_id',
        'auth_provider',
        'phone',
        'password',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'two_factor_verified_at' => 'datetime',
            'failed_login_attempts' => 'integer',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function customerProfile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return $this->role !== null && in_array($this->role->slug, $roles, true);
    }

    public function hasPermission(string|array $permissions): bool
    {
        $permissions = (array) $permissions;

        return $this->role?->permissions()->whereIn('slug', $permissions)->exists() ?? false;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }
}
