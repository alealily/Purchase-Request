<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Database configuration
    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'badge',
        'role',
        'position',
        'department',
        'division',
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
        ];
    }

    /**
     * RBAC Helper Methods - Role Based
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isSuperior(): bool
    {
        return $this->role === 'superior';
    }

    public function isIT(): bool
    {
        return $this->role === 'it';
    }

    /**
     * Position Helper Methods - Approval Hierarchy
     */
    public function isHeadOfDepartment(): bool
    {
        return $this->position === 'head_of_department';
    }

    public function isHeadOfDivision(): bool
    {
        return $this->position === 'head_of_division';
    }

    public function isPresidentDirector(): bool
    {
        return $this->position === 'president_director';
    }

    /**
     * Division Helper Methods
     */
    public function isGeneralDivision(): bool
    {
        return $this->division === 'general';
    }

    public function isFactoryDivision(): bool
    {
        return in_array($this->division, ['PCBA', 'ASSY 1', 'ASSY 2', 'MOLDING']);
    }

    /**
     * Relationships
     */
    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class, 'id_user');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'id_user');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_atasan', 'id');
    }

    public function bawahan(): HasMany
    {
        return $this->hasMany(User::class, 'id_atasan');
    }
}