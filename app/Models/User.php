<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasUlids;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public const ROLE_KARYAWAN = 'karyawan';
    public const ROLE_ATASAN_DIVISI = 'atasan_divisi';
    public const ROLE_HRD = 'hrd';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public static array $roles = [
        self::ROLE_KARYAWAN,
        self::ROLE_ATASAN_DIVISI,
        self::ROLE_HRD,
    ];

    public static array $roleLabels = [
        self::ROLE_KARYAWAN => 'Karyawan',
        self::ROLE_ATASAN_DIVISI => 'Atasan Divisi',
        self::ROLE_HRD => 'HRD',
    ];

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'raw_password',
        'role',
        'status',
        'phone',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'city',
        'education_id',
        'division_id',
        'job_title_id',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'raw_password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'datetime:Y-m-d',
            'password' => 'hashed',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::$roleLabels[$this->role] ?? ucfirst((string) $this->role);
    }

    public function getIsKaryawanAttribute(): bool
    {
        return $this->role === self::ROLE_KARYAWAN;
    }

    public function getIsAtasanDivisiAttribute(): bool
    {
        return $this->role === self::ROLE_ATASAN_DIVISI;
    }

    public function getIsHrdAttribute(): bool
    {
        return $this->role === self::ROLE_HRD;
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->isHrd || $this->isAtasanDivisi;
    }

    public function getIsNotAdminAttribute(): bool
    {
        return !$this->isAdmin;
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function approvedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }
}
