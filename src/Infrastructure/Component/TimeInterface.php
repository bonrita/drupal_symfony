<?php

namespace App\Infrastructure\Component;

/**
 * Defines an interface for obtaining system time.
 */
interface TimeInterface
{
    /**
     * Generate an array of time zones.
     *
     * @return array
     *  An array containing time zones.
     */
    public function getTimezones(): array;

    /**
     * Generate an array of grouped time zones.
     *
     * @param array $zones
     *   An array of time zones.
     *
     * @return array
     *   An array containing grouped timezones.
     */
    public function getGroupedTimezones(array $zones): array;

    /**
     * Returns the timestamp for the current request.
     *
     * @return int
     */
    public function getRequestTime():int;

}