<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class LeaveRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const TYPE_IZIN = 'izin';
    public const TYPE_CUTI = 'cuti';
    public const TYPE_SAKIT = 'sakit';

    public static array $typeLabels = [
        self::TYPE_IZIN => 'Izin',
        self::TYPE_CUTI => 'Cuti',
        self::TYPE_SAKIT => 'Sakit',
    ];

    public static array $statusLabels = [
        self::STATUS_PENDING => 'Menunggu',
        self::STATUS_APPROVED => 'Disetujui',
        self::STATUS_REJECTED => 'Ditolak',
    ];

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approved_at',
        'approver_note',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->leave_type] ?? ucfirst((string) $this->leave_type);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function attachmentUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (!$this->attachment) {
                return null;
            }
            if (str_starts_with($this->attachment, 'http')) {
                return $this->attachment;
            }
            return Storage::disk(config('jetstream.attachment_disk', 'public'))->url($this->attachment);
        });
    }
}
