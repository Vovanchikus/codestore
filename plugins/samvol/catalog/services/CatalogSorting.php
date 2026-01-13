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
            'label' => 'По загрузкам (больше)',
            'type' => 'column',
            'column' => 'downloads_count',
            'direction' => 'desc',
        ],
        'downloads_asc' => [
            'label' => 'По загрузкам (меньше)',
            'type' => 'column',
            'column' => 'downloads_count',
            'direction' => 'asc',
        ],
        'views_desc' => [
            'label' => 'По просмотрам (больше)',
            'type' => 'column',
            'column' => 'views_count',
            'direction' => 'desc',
        ],
        'views_asc' => [
            'label' => 'По просмотрам (меньше)',
            'type' => 'column',
            'column' => 'views_count',
            'direction' => 'asc',
        ],
    ];

    /**
     * Logical groups -> concrete sort codes mapping.
     * Backwards-compatibility helper for admin values that may contain group keys.
     */
    private const GROUPS = [
        'date' => ['date_desc', 'date_asc'],
        'name' => ['title_asc', 'title_desc'],
        'downloads' => ['downloads_asc', 'downloads_desc'],
        'views' => ['views_asc', 'views_desc'],
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

        // Сохраняем то, что уже лежит в настройках сортировки, чтобы не потерять доп. поля
        $existingSortingRaw = $settingsArray['sorting'] ?? [];
        $existingSorting = self::normalizeArray($existingSortingRaw);

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
        // 1) Если сортировка выключена — откатываемся к безопасному порядку по дате публикации.
        if (!self::isEnabled($catalog)) {
            return $query->orderBy('published_at', 'desc');
        }

        $definitions = self::enabledDefinitions($catalog);

        // 2) Валидация кода сортировки и выбор определения.
        if (!isset($definitions[$sortCode])) {
            $sortCode = self::resolveSortCode($catalog, null);
        }

        $definition = $definitions[$sortCode] ?? null;
        if (!$definition) {
            return $query;
        }

        // 3) Основная сортировка — сохраняем прежнее поведение (column/json).
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
        // Inspect raw settings to determine whether admin explicitly saved 'allowed'.
        $rawSettings = $catalog->settings ?? null;
        $settingsArr = [];
        if (is_string($rawSettings)) {
            $decoded = json_decode($rawSettings, true);
            if (is_array($decoded)) {
                $settingsArr = $decoded;
            }
        } elseif (is_array($rawSettings)) {
            $settingsArr = $rawSettings;
        }

        $explicitAllowedExists = array_key_exists('sorting', $settingsArr)
            && is_array($settingsArr['sorting'])
            && array_key_exists('allowed', $settingsArr['sorting']);

        $rawAllowed = $explicitAllowedExists ? $settingsArr['sorting']['allowed'] : null;

        // Build allowed list from rawAllowed (supports group keys), but DO NOT force a fallback
        // when the admin explicitly saved an empty array. Only when rawAllowed is null
        // (no explicit admin setting) we fall back to safe defaults.
        $allowed = [];
        if (is_array($rawAllowed)) {
            foreach ($rawAllowed as $code) {
                if (!is_string($code) || $code === '') {
                    continue;
                }

                if (isset($definitions[$code])) {
                    $allowed[] = $code;
                    continue;
                }

                if (isset(self::GROUPS[$code]) && is_array(self::GROUPS[$code])) {
                    foreach (self::GROUPS[$code] as $c) {
                        if (isset($definitions[$c])) {
                            $allowed[] = $c;
                        }
                    }
                    continue;
                }
            }
        }

        // If admin did not provide explicit allowed list -> provide sensible fallback.
        if ($rawAllowed === null && empty($allowed)) {
            $allowed = [self::SAFE_FALLBACK];
        }

        // Keep only entries that exist in definitions and normalize ordering/uniqueness
        $allowed = array_values(array_filter($allowed, static function ($code) use ($definitions) {
            return is_string($code) && isset($definitions[$code]);
        }));

        // If admin explicitly saved an empty list, return empty array (honour admin choice).
        if ($explicitAllowedExists) {
            return $allowed;
        }

        // No explicit admin choice -> ensure at least one available option for backward compatibility
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

        // Default selection should not depend on allowed codes.
        // Prefer SAFE_FALLBACK if present, otherwise return the first available definition.
        if (isset($definitions[self::SAFE_FALLBACK])) {
            return self::SAFE_FALLBACK;
        }

        $first = array_key_first($definitions);
        return $first ?: self::SAFE_FALLBACK;
    }

    private static function sanitizeSortingValues(Catalog $catalog, ?string $default, ?array $allowed): array
    {
        $definitions = self::enabledDefinitions($catalog);
        // If admin didn't provide explicit allowed (null) — treat as "no explicit restriction"
        // and default to all definitions; if admin provided an explicit array (possibly empty),
        // honour it (do not inject fallbacks).
        if ($allowed === null) {
            $allowedFiltered = array_keys($definitions);
        } else {
            $allowedFiltered = array_values(array_filter(
                is_array($allowed) ? $allowed : [],
                static function ($code) use ($definitions) {
                    return is_string($code) && isset($definitions[$code]);
                }
            ));
        }

        // When there are no allowed filters (explicit empty array), keep it empty.
        // Only when allowed was null (no explicit admin choice) should we ensure
        // at least one fallback option exists for backward compatibility.
        if (empty($allowedFiltered) && $allowed === null) {
            $first = array_key_first($definitions);
            $allowedFiltered[] = $first ?: self::SAFE_FALLBACK;
        }

        // Only inject SAFE_FALLBACK automatically when admin did not provide an explicit
        // allowed list (allowed === null). If admin explicitly provided allowed codes
        // (even non-empty), respect them and do not prepend SAFE_FALLBACK.
        if ($allowed === null && !empty($allowedFiltered) && !in_array(self::SAFE_FALLBACK, $allowedFiltered, true) && isset($definitions[self::SAFE_FALLBACK])) {
            array_unshift($allowedFiltered, self::SAFE_FALLBACK);
            $allowedFiltered = array_values(array_unique($allowedFiltered));
        }

        // Default should not implicitly depend on allowed list. Use explicit default if valid,
        // otherwise fall back to SAFE_FALLBACK or the first available definition.
        $defaultCandidate = is_string($default) && isset($definitions[$default])
            ? $default
            : (isset($definitions[self::SAFE_FALLBACK]) ? self::SAFE_FALLBACK : (array_key_first($definitions) ?: self::SAFE_FALLBACK));

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

    /**
     * Возвращает сгруппированные варианты сортировки для фронтенда.
     * Формат: ['groups' => [ ['key'=>'date','label'=>'По дате','asc'=>..., 'desc'=>...], ... ], 'others' => [code=>label,...] ]
     */
    public static function getGroupedSorts(Catalog $catalog): array
    {
        $definitions = self::enabledDefinitions($catalog);
        $allowed = self::getAllowedCodes($catalog);

        $groupResult = [];
        $used = [];

        $labels = [
            'date' => 'По дате',
            'name' => 'По названию',
            'downloads' => 'По загрузкам',
            'views' => 'По просмотрам',
        ];

        foreach (self::GROUPS as $groupKey => $codes) {
            $asc = null;
            $desc = null;
            foreach ($codes as $code) {
                if (!in_array($code, $allowed, true)) {
                    continue;
                }
                if (!isset($definitions[$code])) {
                    continue;
                }
                $dir = strtolower($definitions[$code]['direction'] ?? 'desc');
                if ($dir === 'asc') {
                    $asc = $code;
                } else {
                    $desc = $code;
                }
                $used[] = $code;
            }

            if ($asc === null && $desc === null) {
                continue;
            }

            $groupResult[] = [
                'key' => $groupKey,
                'label' => $labels[$groupKey] ?? $groupKey,
                'asc' => $asc,
                'desc' => $desc,
            ];
        }

        // Others: allowed codes not covered by groups
        $others = [];
        foreach ($allowed as $code) {
            if (in_array($code, $used, true)) {
                continue;
            }
            if (isset($definitions[$code])) {
                $others[$code] = $definitions[$code]['label'];
            }
        }

        return [
            'groups' => $groupResult,
            'others' => $others,
        ];
    }

    /**
     * Возвращает опции для админки — сгруппированные ключи + остальные коды.
     * Формат: ['date' => 'По дате', 'name' => 'По названию', 'downloads_desc' => 'По загрузкам', ...]
     */
    public static function adminOptions(Catalog $catalog): array
    {
        $definitions = self::enabledDefinitions($catalog);
        $allowed = self::getAllowedCodes($catalog);

        $result = [];
        // Add groups if any of their codes are present in definitions
        $groupLabels = [
            'date' => 'По дате',
            'name' => 'По названию',
            'downloads' => 'По загрузкам',
            'views' => 'По просмотрам',
        ];

        foreach (self::GROUPS as $groupKey => $codes) {
            $has = false;
            foreach ($codes as $c) {
                if (isset($definitions[$c])) {
                    $has = true;
                    break;
                }
            }
            if ($has) {
                $result[$groupKey] = $groupLabels[$groupKey] ?? $groupKey;
            }
        }

        // Add other concrete options
        foreach ($definitions as $code => $def) {
            // skip codes already represented by groups
            $inGroup = false;
            foreach (self::GROUPS as $grp) {
                if (in_array($code, $grp, true)) {
                    $inGroup = true;
                    break;
                }
            }
            if ($inGroup) {
                continue;
            }
            $result[$code] = $def['label'];
        }

        return $result;
    }

    /**
     * Expand admin-provided allowed selection: group keys -> concrete codes.
     * Accepts null/array/string and returns array of concrete codes.
     */
    public static function expandAllowedSelection(Catalog $catalog, $rawAllowed): ?array
    {
        $definitions = self::enabledDefinitions($catalog);
        // If admin didn't provide any explicit allowed setting (null), return null
        // to indicate "no explicit allowed list". If admin provided an explicit
        // value (array or string) we expand it to concrete codes and return an
        // array (possibly empty) — we won't force SAFE_FALLBACK for explicit emptiness.
        $explicit = false;
        $allowedArr = [];
        if (is_string($rawAllowed) && $rawAllowed !== '') {
            $allowedArr = [$rawAllowed];
            $explicit = true;
        } elseif (is_array($rawAllowed)) {
            $allowedArr = $rawAllowed;
            $explicit = true;
        } elseif ($rawAllowed === null) {
            return null;
        }

        $result = [];
        foreach ($allowedArr as $code) {
            if (!is_string($code) || $code === '') {
                continue;
            }
            if (isset($definitions[$code])) {
                $result[] = $code;
                continue;
            }
            if (isset(self::GROUPS[$code]) && is_array(self::GROUPS[$code])) {
                foreach (self::GROUPS[$code] as $c) {
                    if (isset($definitions[$c])) {
                        $result[] = $c;
                    }
                }
                continue;
            }
        }

        $result = array_values(array_unique($result));

        // If admin explicitly provided an empty array, return empty array (honour choice).
        if ($explicit) {
            return $result;
        }

        // Shouldn't reach here because we return early for null, but keep fallback for safety.
        if (empty($result)) {
            $first = array_key_first($definitions);
            $result[] = $first ?: self::SAFE_FALLBACK;
        }

        if (!in_array(self::SAFE_FALLBACK, $result, true) && isset($definitions[self::SAFE_FALLBACK])) {
            array_unshift($result, self::SAFE_FALLBACK);
            $result = array_values(array_unique($result));
        }

        return $result;
    }

    /**
     * Expand default selection: if group key provided, pick preferred concrete code.
     */
    public static function expandDefaultSelection(Catalog $catalog, ?string $default): ?string
    {
        $definitions = self::enabledDefinitions($catalog);
        if (!is_string($default) || $default === '') {
            return null;
        }

        if (isset($definitions[$default])) {
            return $default;
        }

        if (isset(self::GROUPS[$default])) {
            // prefer desc if available
            foreach (self::GROUPS[$default] as $c) {
                if (isset($definitions[$c]) && strtolower($definitions[$c]['direction'] ?? 'desc') === 'desc') {
                    return $c;
                }
            }
            // else return first available
            foreach (self::GROUPS[$default] as $c) {
                if (isset($definitions[$c])) {
                    return $c;
                }
            }
        }

        return null;
    }

    /**
     * If a concrete code belongs to a group, return that group's key for admin UI.
     */
    public static function findAdminKeyForCode(Catalog $catalog, string $code): ?string
    {
        foreach (self::GROUPS as $groupKey => $codes) {
            if (in_array($code, $codes, true)) {
                return $groupKey;
            }
        }

        return null;
    }

    public static function getSortingSettings(Catalog $catalog): array
    {
        $settings = self::normalizeArray($catalog->settings ?? []);
        $sorting = $settings['sorting'] ?? null;

        return self::normalizeArray($sorting);
    }

    /**
     * Настройки отслеживания обновлений каталога.
     */
    public static function getTrackUpdatesSettings(Catalog $catalog): array
    {
        $settings = self::normalizeArray($catalog->settings ?? []);
        $tracking = $settings['track_updates'] ?? null;

        $tracking = self::normalizeArray($tracking);

        return [
            'enabled' => (bool) ($tracking['enabled'] ?? false),
            'field' => isset($tracking['field']) && is_string($tracking['field']) ? $tracking['field'] : null,
            'log_field' => isset($tracking['log_field']) && is_string($tracking['log_field']) && $tracking['log_field'] !== ''
                ? $tracking['log_field']
                : null,
            'badge_enabled' => isset($tracking['badge_enabled']) ? (bool) $tracking['badge_enabled'] : false,
            'badge_days' => isset($tracking['badge_days']) ? (int) $tracking['badge_days'] : 7,
        ];
    }

    /**
     * Объединяет настройки трекинга обновлений с текущим settings каталога.
     */
    public static function mergeTrackUpdatesSettings(Catalog $catalog, $settings, ?bool $enabled, ?string $field, ?string $logField, ?bool $badgeEnabled = null, ?int $badgeDays = null): array
    {
        $settingsArray = self::normalizeArray($settings);

        $existing = self::getTrackUpdatesSettings($catalog);

        $merged = [
            'enabled' => $enabled !== null ? (bool) $enabled : ($existing['enabled'] ?? false),
            'field' => $field !== null ? $field : ($existing['field'] ?? null),
            'log_field' => $logField !== null && $logField !== '' ? $logField : ($existing['log_field'] ?? null),
            'badge_enabled' => $badgeEnabled !== null ? (bool) $badgeEnabled : ($existing['badge_enabled'] ?? false),
            'badge_days' => $badgeDays !== null ? (int) $badgeDays : (isset($existing['badge_days']) ? (int)$existing['badge_days'] : 7),
        ];

        $settingsArray['track_updates'] = $merged;

        return $settingsArray;
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

    /**
     * Подготавливает массив элементов сортировки для фронтенда.
     * Возвращает уже готовые поля для прямого рендеринга в Twig:
     * [ ['label'=>..., 'target'=>..., 'href'=>..., 'isActive'=>bool, 'dirClass'=>'asc'|'desc', 'class'=>string, 'iconHtml'=>string, 'arrowHtml'=>string, ...], ... ]
     */
    public static function prepareSortingItems(Catalog $catalog, ?string $requestedSort, ?string $currentSort, string $currentDir = 'desc'): array
    {
        if (!self::isEnabled($catalog)) {
            return [];
        }

        $gs = self::getGroupedSorts($catalog);
        $available = self::getAvailableSorts($catalog);
        $definitions = self::enabledDefinitions($catalog);

        $uiSort = $requestedSort ?: $currentSort;

        $normalizeLabel = function ($label) {
            if (!is_string($label) || $label === '') return $label;
            $label = preg_replace('/^По\s+/u', '', $label);
            $first = mb_substr($label, 0, 1, 'UTF-8');
            $rest = mb_substr($label, 1, null, 'UTF-8');
            return mb_strtoupper($first, 'UTF-8') . $rest;
        };

        $iconMap = [
            'date'      => '<svg width="8" height="8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 5.81294 5.81294 1 11.75 1C17.6871 1 22.5 5.81294 22.5 11.75Z" fill="currentColor"/></svg>',
            'name'      => '<svg width="8" height="8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 5.81294 5.81294 1 11.75 1C17.6871 1 22.5 5.81294 22.5 11.75Z" fill="currentColor"/></svg>',
            'downloads' => '<svg width="8" height="8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 5.81294 5.81294 1 11.75 1C17.6871 1 22.5 5.81294 22.5 11.75Z" fill="currentColor"/></svg>',
            'view'      => '<svg width="8" height="8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 5.81294 5.81294 1 11.75 1C17.6871 1 22.5 5.81294 22.5 11.75Z" fill="currentColor"/></svg>',
        ];

        // map logical keys to simple icon names used in Twig conditionals
        $iconKeyMap = [
            'date'      => 'calendar',
            'name'      => 'font-case',
            'downloads' => 'download-arc',
            'views'     => 'eye',
        ];

        $arrowHtml = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.28033 15.2197C6.98744 14.9268 6.51256 14.9268 6.21967 15.2197C5.92678 15.5126 5.92678 15.9874 6.21967 16.2803L11.2197 21.2803C11.5126 21.5732 11.9874 21.5732 12.2803 21.2803L17.2803 16.2803C17.5732 15.9874 17.5732 15.5126 17.2803 15.2197C16.9874 14.9268 16.5126 14.9268 16.2197 15.2197L12.5 18.9393V2.75C12.5 2.33579 12.1642 2 11.75 2C11.3358 2 11 2.33579 11 2.75V18.9393L7.28033 15.2197Z" fill="currentColor"/></svg>';

        $result = [];
        $used = [];

        // Groups first
        if (isset($gs['groups']) && is_array($gs['groups'])) {
            foreach ($gs['groups'] as $g) {
                $asc = $g['asc'] ?? null;
                $desc = $g['desc'] ?? null;
                $label = $normalizeLabel($g['label'] ?? ($g['key'] ?? ''));
                $isActive = $uiSort && ($uiSort === ($asc ?? '') || $uiSort === ($desc ?? ''));

                // determine target and dirClass
                if ($isActive) {
                    if (preg_match('/_desc$/', $uiSort)) {
                        $target = preg_replace('/_desc$/', '_asc', $uiSort);
                        $dirClass = 'desc';
                    } elseif (preg_match('/_asc$/', $uiSort)) {
                        $target = preg_replace('/_asc$/', '_desc', $uiSort);
                        $dirClass = 'asc';
                    } else {
                        $target = $desc ?: $asc;
                        $dirClass = in_array($currentDir, ['asc','desc'], true) ? $currentDir : 'desc';
                    }
                } else {
                    $target = $desc ?: $asc;
                    $dirClass = 'desc';
                    if ($asc && preg_match('/_asc$/', $asc)) $dirClass = 'asc';
                    if ($desc && preg_match('/_desc$/', $desc)) $dirClass = 'desc';
                }

                $iconHtml = $iconMap[$g['key']] ?? '';
                $iconKey = $iconKeyMap[$g['key']] ?? null;
                $class = trim(($isActive ? 'active' : '') . ' ' . $dirClass);

                $result[] = [
                    'type' => 'group',
                    'key' => $g['key'] ?? null,
                    'label' => $label,
                    'asc' => $asc,
                    'desc' => $desc,
                    'target' => $target,
                    'href' => '?sort=' . ($target ?? ''),
                    'isActive' => (bool) $isActive,
                    'dirClass' => $dirClass,
                    'class' => $class,
                    'iconHtml' => $iconHtml,
                    'icon' => $iconKey,
                    'arrowHtml' => $arrowHtml,
                ];

                if (!empty($asc)) $used[] = $asc;
                if (!empty($desc)) $used[] = $desc;
            }
        }

        // Others from groupedSorts
        if (isset($gs['others']) && is_array($gs['others'])) {
            foreach ($gs['others'] as $code => $labelRaw) {
                if (in_array($code, $used, true)) continue;
                $label = $normalizeLabel($labelRaw);
                $isActive = $uiSort && $uiSort === $code;
                $dirClass = preg_match('/_asc$/', $code) ? 'asc' : (preg_match('/_desc$/', $code) ? 'desc' : $currentDir);
                $class = trim(($isActive ? 'active' : '') . ' ' . $dirClass);
                $iconKey = null;
                if (strpos($code, 'date') !== false) $iconKey = 'calendar';
                elseif (strpos($code, 'title') !== false || strpos($code, 'name') !== false) $iconKey = 'star';
                elseif (strpos($code, 'download') !== false) $iconKey = 'download';
                elseif (strpos($code, 'view') !== false) $iconKey = 'eye';

                $result[] = [
                    'type' => 'single',
                    'code' => $code,
                    'label' => $label,
                    'target' => $code,
                    'href' => '?sort=' . $code,
                    'isActive' => (bool)$isActive,
                    'dirClass' => $dirClass,
                    'class' => $class,
                    'iconHtml' => '',
                    'icon' => $iconKey,
                    'arrowHtml' => $arrowHtml,
                ];
                $used[] = $code;
            }
        }

        // Fallback: availableSorts
        if (is_array($available)) {
            foreach ($available as $code => $labelRaw) {
                if (in_array($code, $used, true)) continue;
                $label = $normalizeLabel($labelRaw);
                $isActive = $uiSort && $uiSort === $code;
                $dirClass = preg_match('/_asc$/', $code) ? 'asc' : (preg_match('/_desc$/', $code) ? 'desc' : $currentDir);
                $class = trim(($isActive ? 'active' : '') . ' ' . $dirClass);
                $iconKey = null;
                if (strpos($code, 'date') !== false) $iconKey = 'calendar';
                elseif (strpos($code, 'title') !== false || strpos($code, 'name') !== false) $iconKey = 'star';
                elseif (strpos($code, 'download') !== false) $iconKey = 'download';
                elseif (strpos($code, 'view') !== false) $iconKey = 'eye';

                $result[] = [
                    'type' => 'single',
                    'code' => $code,
                    'label' => $label,
                    'target' => $code,
                    'href' => '?sort=' . $code,
                    'isActive' => (bool)$isActive,
                    'dirClass' => $dirClass,
                    'class' => $class,
                    'iconHtml' => '',
                    'icon' => $iconKey,
                    'arrowHtml' => $arrowHtml,
                ];
                $used[] = $code;
            }
        }

        return $result;
    }
}
