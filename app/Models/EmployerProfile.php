<?php

namespace App\Models;

use App\Support\Abuyog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerProfile extends Model
{
    protected $fillable = ['user_id', 'company_name', 'bio', 'phone', 'address', 'barangay', 'city'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullLocationAttribute(): string
    {
        return Abuyog::location($this->address, $this->barangay);
    }
}
