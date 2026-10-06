<?php

namespace App\Support;

/**
 * Cleans and validates the Messenger and Gmail links freelancers add to their profile.
 *
 * Only a plain Messenger username / numeric ID is stored (never a raw URL), so the
 * link we build, https://m.me/<username>, can never point anywhere unexpected.
 */
class ContactLinks
{
    /** Facebook / Messenger usernames: letters, numbers and periods, 5 to 50 characters. */
    public const MESSENGER_PATTERN = '/^[A-Za-z0-9.]{5,50}$/';

    public const MESSENGER_MESSAGE = 'Enter your Facebook username or paste your Facebook profile link (example: facebook.com/juan.delacruz).';

    public const GMAIL_PATTERN = '/^[A-Za-z0-9._%+\-]+@gmail\.com$/i';

    public const GMAIL_MESSAGE = 'Enter a valid Gmail address ending in @gmail.com.';

    /**
     * Accepts a username, "@username", or a facebook.com / fb.com / m.me / messenger.com link
     * and returns just the username or numeric ID. Anything unrecognised is returned as typed
     * so validation can reject it.
     */
    public static function normalizeMessenger(?string $value): ?string
    {
        $v = trim((string) $value);

        if ($v === '') {
            return null;
        }

        $pattern = '~^(?:https?://)?(?:www\.|m\.|web\.|mobile\.)?(facebook\.com|fb\.com|fb\.me|m\.me|messenger\.com)/(.+)$~i';

        if (preg_match($pattern, $v, $m)) {
            $host = strtolower($m[1]);
            $rest = $m[2];

            // facebook.com/profile.php?id=1000123456789
            if (preg_match('~^profile\.php\?(?:[^#]*&)?id=(\d+)~i', $rest, $id)) {
                return $id[1];
            }

            $path = preg_split('/[?#]/', $rest)[0];
            $segments = array_values(array_filter(explode('/', $path), 'strlen'));

            if (! $segments) {
                return $v;
            }

            // messenger.com/t/username
            if ($host === 'messenger.com' && strtolower($segments[0]) === 't' && isset($segments[1])) {
                return $segments[1];
            }

            // facebook.com/people/Name/1000123456789
            if (strtolower($segments[0]) === 'people' && count($segments) >= 3) {
                return end($segments);
            }

            return $segments[0];
        }

        return ltrim($v, '@');
    }

    public static function normalizeGmail(?string $value): ?string
    {
        $v = strtolower(trim((string) $value));

        return $v === '' ? null : $v;
    }
}
