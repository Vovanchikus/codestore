<?php namespace Samvol\UiCore\Classes;

class AjaxResponse
{
    /**
     * Build a standardized payload for all AJAX responses.
     */
    protected static function make(bool $success, string $message = '', $data = null, ?array $errors = null): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ];
    }

    public static function success($data = [], string $message = ''): array
    {
        return self::make(true, $message, $data ?? [], null);
    }

    public static function error(string $message = '', array $errors = []): array
    {
        return self::make(false, $message, null, empty($errors) ? null : $errors);
    }

    public static function validationError(array $errors): array
    {
        return self::make(false, 'Validation error', null, $errors);
    }

    public static function forbidden(string $message = 'Access denied'): array
    {
        return self::make(false, $message, null, null);
    }
}
