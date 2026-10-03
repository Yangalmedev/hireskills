<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreelancerProfile extends Model
{
    protected $fillable = ['user_id', 'title', 'bio', 'skills', 'hourly_rate', 'phone', 'address', 'city'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSkillsListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->skills))));
    }
}
