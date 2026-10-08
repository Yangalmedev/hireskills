<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PortfolioItem extends Model
{
    protected $fillable = ['freelancer_profile_id', 'title', 'description', 'file_path', 'file_mime'];

    protected static function booted(): void
    {
        // removing a sample also removes its image file
        static::deleted(function (PortfolioItem $item) {
            Storage::disk('local')->delete($item->file_path);
        });
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(FreelancerProfile::class, 'freelancer_profile_id');
    }

    /** Login-protected link to the image. */
    public function getFileUrlAttribute(): string
    {
        return route('portfolio.file', $this);
    }
}
