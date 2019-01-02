<?php

namespace App\Infrastructure\Component\Utility;

/**
 * Class Crypt
 * @package App\Infrastructure\Component\Utility
 *
 * Utility class for cryptographically-secure string handling routines.
 */
class Crypt
{

    /**
     * Calculates a base-64 encoded, URL-safe sha-256 hmac.
     *
     * @param mixed $data
     *   Scalar value to be validated with the hmac.
     * @param mixed $key
     *   A secret key, this can be any scalar value.
     *
     * @return string
     *   A base-64 encoded sha-256 hmac, with + replaced with -, / with _ and
     *   any = padding characters removed.
     */
    public function hmacBase64($data, $key): string
    {
        // $data and $key being strings here is necessary to avoid empty string
        // results of the hash function if they are not scalar values. As this
        // function is used in security-critical contexts like token validation it
        // is important that it never returns an empty string.
        if (!is_scalar($data) || !is_scalar($key)) {
            throw new \InvalidArgumentException(
                'Both parameters passed to \App\Component\Utility\Crypt::hmacBase64 must be scalar values.'
            );
        }

        $hmac = base64_encode(hash_hmac('sha256', $data, $key, true));

        // Modify the hmac so it's safe to use in URLs.
        return str_replace(['+', '/', '='], ['-', '_', ''], $hmac);
    }

    /**
     * Compares strings in constant time.
     *
     * @param string $known_string
     *   The expected string.
     * @param string $user_string
     *   The user supplied string to check.
     *
     * @return bool
     *   Returns TRUE when the two strings are equal, FALSE otherwise.
     */
    public static function hashEquals($known_string, $user_string): bool
    {
        if (\function_exists('hash_equals')) {
            return hash_equals($known_string, $user_string);
        } else {

        }
    }

}
