<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['freelancer_profile_id', 'user_id', 'rating', 'comment'];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(FreelancerProfile::class, 'freelancer_profile_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** "Maria Santos" -> "Maria S." */
    public function getReviewerNameAttribute(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->employer?->name)) ?: [];
        $first = $parts[0] ?? 'Employer';
        $last = count($parts) > 1 ? ' '.mb_substr(end($parts), 0, 1).'.' : '';

        return $first.$last;
    }
}
