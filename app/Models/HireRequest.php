<?php

namespace App\Models;

use App\Support\Abuyog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HireRequest extends Model
{
    public const PENDING   = 'pending';
    public const ACCEPTED  = 'accepted';
    public const DECLINED  = 'declined';
    public const CANCELLED = 'cancelled';
    public const COMPLETED = 'completed';

    public const STATUSES = [self::PENDING, self::ACCEPTED, self::DECLINED, self::CANCELLED, self::COMPLETED];

    public const BUDGET_TYPES = [
        'per_hour' => 'per hour',
        'per_day'  => 'per day',
        'per_job'  => 'per job',
    ];

    protected $fillable = [
        'freelancer_profile_id', 'employer_id', 'title', 'description', 'barangay', 'address',
        'preferred_date', 'budget', 'budget_type', 'status', 'freelancer_reply',
        'responded_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'budget'         => 'decimal:2',
            'responded_at'   => 'datetime',
            'completed_at'   => 'datetime',
        ];
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(FreelancerProfile::class, 'freelancer_profile_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    /** "₱500 per day" or "Budget not set" */
    public function getBudgetTextAttribute(): string
    {
        if ($this->budget === null) {
            return 'Budget not set';
        }

        $amount = (float) $this->budget;
        $formatted = $amount == floor($amount) ? number_format($amount, 0) : number_format($amount, 2);

        return '₱'.$formatted.' '.(self::BUDGET_TYPES[$this->budget_type] ?? '');
    }

    /** "Purok 3, Brgy. Bito, Abuyog, Leyte" */
    public function getLocationTextAttribute(): string
    {
        return Abuyog::location($this->address, $this->barangay);
    }
}
