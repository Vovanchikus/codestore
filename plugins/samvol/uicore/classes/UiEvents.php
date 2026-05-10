<?php namespace Samvol\UiCore\Classes;

use Event;

final class UiEvents
{
    public const AJAX_BEFORE = 'samvol.uicore.ajax.before';
    public const AJAX_AFTER = 'samvol.uicore.ajax.after';
    public const AJAX_ERROR = 'samvol.uicore.ajax.error';

    /**
     * Dispatch a UiCore event and return listeners responses.
     */
    public static function fire(string $eventName, ...$payload): array
    {
        return Event::fire($eventName, $payload);
    }
}
