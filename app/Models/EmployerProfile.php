<?php

namespace App\Models;

use App\Models\Concerns\HasContactLinks;
use App\Support\Abuyog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerProfile extends Model
{
    use HasContactLinks;

    protected $fillable = ['user_id', 'company_name', 'bio', 'phone', 'messenger', 'gmail', 'address', 'barangay', 'city'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullLocationAttribute(): string
    {
        return Abuyog::location($this->address, $this->barangay);
    }
}
