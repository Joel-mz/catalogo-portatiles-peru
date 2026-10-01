<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'password',
        'must_change_password',
        'role_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return !is_null($this->two_factor_confirmed_at) && !empty($this->two_factor_secret);
    }

    public function isAdministradorGeneral(): bool
    {
        return $this->role?->name === 'Administrador General';
    }

    public function isAdministrador(): bool
    {
        return in_array($this->role?->name, ['Administrador General', 'Administrador', 'Admin']);
    }

    public function isVendedor(): bool
    {
        return $this->role?->name === 'Vendedor';
    }

    public function isPersonal(): bool
    {
        return $this->role?->name === 'Personal';
    }

    public function isSoporte(): bool
    {
        return $this->role?->name === 'Soporte Técnico';
    }

    public function hasElevatedPrivileges(): bool
    {
        return $this->isAdministradorGeneral() || in_array($this->role?->name, ['Administrador', 'Admin']);
    }

    public function requiresTwoFactor(): bool
    {
        return $this->hasElevatedPrivileges();
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['activo', null, 'active']);
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspendido';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'bloqueado';
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function securityOtps(): HasMany
    {
        return $this->hasMany(SecurityOtp::class);
    }
}
