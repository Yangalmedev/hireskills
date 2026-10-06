<?php

namespace App\Models\Concerns;

/**
 * Clickable contact links for any profile with `messenger`, `gmail` and `phone` columns.
 * Used by FreelancerProfile and EmployerProfile.
 */
trait HasContactLinks
{
    /** Opens the person's Messenger chat: https://m.me/username */
    public function getMessengerUrlAttribute(): ?string
    {
        return filled($this->messenger) ? 'https://m.me/'.rawurlencode($this->messenger) : null;
    }

    /** Opens a new Gmail message addressed to the person. */
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
