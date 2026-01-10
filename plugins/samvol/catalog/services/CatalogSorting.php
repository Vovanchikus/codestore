<?php namespace Samvol\Catalog\Services;

use Samvol\Catalog\Models\Catalog;
use Winter\Storm\Database\Builder;

class CatalogSorting
{
    private const SAFE_FALLBACK = 'date_desc';

    private const DEFINITIONS = [
        'date_desc' => [
            'label' => 'По дате (новые)',
            'type' => 'column',
            'column' => 'published_at',
            'direction' => 'desc',
        ],
        'date_asc' => [
            'label' => 'По дате (старые)',
            'type' => 'column',
            'column' => 'published_at',
            'direction' => 'asc',
        ],
        'title_asc' => [
            'label' => 'По названию A–Z',
            'type' => 'json',
            'path' => '$.title',
            'direction' => 'asc',
        ],
        'title_desc' => [
            'label' => 'По названию Z–A',
            'type' => 'json',
            'path' => '$.title',
            'direction' => 'desc',
        ],
        'downloads_desc' => [
            'label' => 'По загрузкам',
            'type' => 'column',
            'column' => 'downloads_count',
            'direction' => 'desc',
        ],
        'views_desc' => [
            'label' => 'По просмотрам',
            'type' => 'column',
            'column' => 'views_count',
            'direction' => 'desc',
        ],
    ];

    public static function resolveSortCode(Catalog $catalog, ?string $requestedSort): string
    {
        if (!self::isEnabled($catalog)) {
            return self::SAFE_FALLBACK;
        }

        $allowedCodes = self::getAllowedCodes($catalog);
        $definitions = self::enabledDefinitions($catalog);
        $defaultCode = self::getDefaultCode($catalog, $allowedCodes, $definitions);

        if ($requestedSort && in_array($requestedSort, $allowedCodes, true)) {
            return $requestedSort;
        }

        return $defaultCode;
    }

    public static function getAvailableSorts(Catalog $catalog): array
    {
        if (!self::isEnabled($catalog)) {
            return [];
        }
        $definitions = self::enabledDefinitions($catalog);
        $allowedCodes = self::getAllowedCodes($catalog);

        $options = [];
        foreach ($allowedCodes as $code) {
            if (isset($definitions[$code])) {
                $options[$code] = $definitions[$code]['label'];
            }
        }

        return $options;
    }

    /**
     * Возвращает метаданные по сортировкам с информацией о доступности (для форм админки).
     * ['code' => ['label' => '...', 'enabled' => bool, 'reason' => string|null]]
     */
    public static function describeOptions(Catalog $catalog): array
    {
        $result = [];
        foreach (self::DEFINITIONS as $code => $definition) {
            $result[$code] = [
                'label' => $definition['label'],
                'enabled' => true,
                'reason' => null,
            ];
        }

        return $result;
    }

    public static function optionLabelsWithStatus(Catalog $catalog): array
    {
        $described = self::describeOptions($catalog);
        $options = [];

        foreach ($described as $code => $meta) {
            $options[$code] = $meta['label'];
        }

        return $options;
    }

    public static function mergeSortingSettings(Catalog $catalog, $settings, ?string $default, ?array $allowed, ?bool $enabled = null): array
    {
        $settingsArray = self::normalizeArray($settings);

        $existing = self::getSortingSettings($catalog);
        $isEnabled = $enabled !== null
            ? (bool) $enabled
            : ($existing['enabled'] ?? true);

        if (!$isEnabled) {
            $settingsArray['sorting'] = [
                'enabled' => false,
                'default' => self::SAFE_FALLBACK,
                'allowed' => [],
            ];
            return $settingsArray;
        }

        $sorting = self::sanitizeSortingValues($catalog, $default, $allowed);
        $sorting['enabled'] = true;

        $settingsArray['sorting'] = $sorting;

        return $settingsArray;
    }

    public static function applySorting(Builder $query, Catalog $catalog, string $sortCode): Builder
    {
        if (!self::isEnabled($catalog)) {
            return $query->orderBy('published_at', 'desc');
        }
        $definitions = self::enabledDefinitions($catalog);

        if (!isset($definitions[$sortCode])) {
            $sortCode = self::resolveSortCode($catalog, null);
        }

        $definition = $definitions[$sortCode] ?? null;
        if (!$definition) {
            return $query;
        }

        $direction = strtoupper($definition['direction'] ?? 'DESC');

        if (($definition['type'] ?? 'column') === 'json') {
            $path = $definition['path'] ?? '$.title';
            $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '" . addslashes($path) . "')) " . $direction);
            return $query;
        }

        return $query->orderBy($definition['column'], $direction);
    }

    private static function getAllowedCodes(Catalog $catalog): array
    {
        $definitions = self::enabledDefinitions($catalog);
        $sortingSettings = self::getSortingSettings($catalog);

        $allowed = isset($sortingSettings['allowed']) && is_array($sortingSettings['allowed'])
            ? $sortingSettings['allowed']
            : [self::SAFE_FALLBACK];

        $allowed = array_values(array_filter($allowed, static function ($code) use ($definitions) {
            return is_string($code) && isset($definitions[$code]);
        }));

        if (!$allowed) {
            $firstAvailable = array_key_first($definitions);
            $allowed[] = $firstAvailable ?: self::SAFE_FALLBACK;
        }

        if (!in_array(self::SAFE_FALLBACK, $allowed, true) && isset($definitions[self::SAFE_FALLBACK])) {
            array_unshift($allowed, self::SAFE_FALLBACK);
            $allowed = array_values(array_unique($allowed));
        }

        return $allowed;
    }

    private static function getDefaultCode(Catalog $catalog, array $allowedCodes, array $definitions): string
    {
        $sortingSettings = self::getSortingSettings($catalog);
        $candidate = $sortingSettings['default'] ?? self::SAFE_FALLBACK;

        if (isset($definitions[$candidate])) {
            return $candidate;
        }

        if ($allowedCodes) {
            return $allowedCodes[0];
        }

        $first = array_key_first($definitions);
        return $first ?: self::SAFE_FALLBACK;
    }

    private static function sanitizeSortingValues(Catalog $catalog, ?string $default, ?array $allowed): array
    {
        $definitions = self::enabledDefinitions($catalog);

        $allowedFiltered = array_values(array_filter(
            is_array($allowed) ? $allowed : [],
            static function ($code) use ($definitions) {
                return is_string($code) && isset($definitions[$code]);
            }
        ));

        if (!$allowedFiltered) {
            $first = array_key_first($definitions);
            $allowedFiltered[] = $first ?: self::SAFE_FALLBACK;
        }

        if (!in_array(self::SAFE_FALLBACK, $allowedFiltered, true) && isset($definitions[self::SAFE_FALLBACK])) {
            array_unshift($allowedFiltered, self::SAFE_FALLBACK);
            $allowedFiltered = array_values(array_unique($allowedFiltered));
        }

        $defaultCandidate = is_string($default) && isset($definitions[$default])
            ? $default
            : null;
        if (!$defaultCandidate) {
            $defaultCandidate = $allowedFiltered[0] ?? (array_key_first($definitions) ?: self::SAFE_FALLBACK);
        }

        return [
            'default' => $defaultCandidate,
            'allowed' => $allowedFiltered,
        ];
    }

    public static function isEnabled(Catalog $catalog): bool
    {
        $settings = self::getSortingSettings($catalog);

        if (isset($settings['enabled'])) {
            return (bool) $settings['enabled'];
        }

        return true;
    }

    private static function enabledDefinitions(Catalog $catalog): array
    {
        return self::DEFINITIONS;
    }

    public static function getSortingSettings(Catalog $catalog): array
    {
        $settings = self::normalizeArray($catalog->settings ?? []);
        $sorting = $settings['sorting'] ?? null;

        return self::normalizeArray($sorting);
    }

    private static function normalizeArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}
