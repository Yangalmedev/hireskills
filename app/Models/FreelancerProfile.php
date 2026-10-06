<?php

namespace App\Models;

use App\Support\Abuyog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreelancerProfile extends Model
{
    /** Single source of truth for service categories. */
    public const CATEGORIES = [
        'Home and Repair',
        'Technology',
        'Design and Education',
        'Events',
        'Personal Services',
        'Transportation',
        'Construction',
        'Agriculture and Fishing',
    ];

    protected $fillable = [
        'user_id', 'title', 'category', 'bio', 'skills',
        'hourly_rate', 'phone', 'messenger', 'gmail', 'address', 'barangay', 'city',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getSkillsListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->skills))));
    }

    /** "Maria Santos" -> "Maria S." (what guests see) */
    public function getPublicNameAttribute(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->user?->name)) ?: [];
        $first = $parts[0] ?? 'Freelancer';
        $last = count($parts) > 1 ? ' '.mb_substr(end($parts), 0, 1).'.' : '';

        return $first.$last;
    }

    /** "Purok 3, Brgy. Bito, Abuyog, Leyte" */
    public function getFullLocationAttribute(): string
    {
        return Abuyog::location($this->address, $this->barangay);
    }

    /** Opens the person's Messenger chat: https://m.me/username */
    public function getMessengerUrlAttribute(): ?string
    {
        return filled($this->messenger) ? 'https://m.me/'.rawurlencode($this->messenger) : null;
    }

    /** Opens a new Gmail message addressed to the freelancer. */
    public function getGmailUrlAttribute(): ?string
    {
        return filled($this->gmail)
            ? 'https://mail.google.com/mail/?view=cm&fs=1&to='.rawurlencode($this->gmail)
            : null;
    }

    /** Starts a phone call (tel: link). */
    public function getPhoneUrlAttribute(): ?string
    {
        return filled($this->phone) ? 'tel:'.$this->phone : null;
    }
}
