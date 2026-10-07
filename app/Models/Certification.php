<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Certification extends Model
{
    protected $fillable = [
        'freelancer_profile_id', 'title', 'issuer', 'credential_id',
        'issued_on', 'expires_on', 'file_path', 'file_mime',
    ];

    protected function casts(): array
    {
        return [
            'issued_on'  => 'date',
            'expires_on' => 'date',
        ];
    }

    protected static function booted(): void
    {
        // keep storage tidy: removing a certification removes its file
        static::deleted(function (Certification $c) {
            Storage::disk('local')->delete($c->file_path);
        });
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(FreelancerProfile::class, 'freelancer_profile_id');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_on !== null && $this->expires_on->isPast();
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with((string) $this->file_mime, 'image/');
    }

    /** Login-protected link to the uploaded file. */
    public function getFileUrlAttribute(): string
    {
        return route('certifications.file', $this);
    }
}
