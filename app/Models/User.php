<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isPartner(): bool
    {
        return in_array($this->role, ['super_admin', 'partner']);
    }

    public function isLawyer(): bool
    {
        return in_array($this->role, ['super_admin', 'partner', 'lawyer']);
    }

    public function isParalegal(): bool
    {
        return $this->role === 'paralegal';
    }

    public function isAccountant(): bool
    {
        return in_array($this->role, ['super_admin', 'accountant']);
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function lawyer(): HasOne
    {
        return $this->hasOne(Lawyer::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'lawyer_user_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function toDos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
