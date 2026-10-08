<?php

namespace App\Models;

use App\Models\Concerns\HasContactLinks;
use App\Support\Abuyog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreelancerProfile extends Model
{
    use HasContactLinks;

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

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class);
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
}
