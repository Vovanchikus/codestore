<?php namespace Samvol\Catalog\Models;

use Model;
use Samvol\Catalog\Services\CatalogSorting;
use Winter\Storm\Database\Traits\Validation;
use Winter\Storm\Exception\ValidationException;

class Catalog extends Model
{
    use Validation;

    /**
     * Виртуальные поля для UI сортировки (не хранятся в таблице напрямую).
     */
    protected ?bool $sortingEnabledVirtual = null;
    protected ?string $sortingDefaultVirtual = null;
    protected ?array $sortingAllowedVirtual = null;
    protected ?bool $trackUpdatesEnabledVirtual = null;
    protected ?string $trackUpdatesFieldVirtual = null;
    protected ?string $trackUpdatesLogFieldVirtual = null;
    protected ?bool $trackUpdatesBadgeEnabledVirtual = null;
    protected ?int $trackUpdatesBadgeDaysVirtual = null;

    protected $table = 'samvol_catalogs';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'features',
        'settings',
    ];

    protected $jsonable = ['settings'];

    public $rules = [
        'name' => 'required',
        'code' => 'required|unique:samvol_catalogs'
    ];

    public $hasMany = [
        'fields' => [Field::class, 'order' => 'sort_order', 'delete' => true],
        'categories' => [Category::class, 'delete' => true],
        'items' => [Item::class, 'delete' => true],
    ];

    public function beforeValidate(): void
    {
        $existing = CatalogSorting::getTrackUpdatesSettings($this);

        $enabled = $this->trackUpdatesEnabledVirtual !== null
            ? (bool) $this->trackUpdatesEnabledVirtual
            : (bool) ($existing['enabled'] ?? false);

        $logField = $this->trackUpdatesLogFieldVirtual !== null
            ? $this->trackUpdatesLogFieldVirtual
            : ($existing['log_field'] ?? null);

        if ($enabled && (!is_string($logField) || $logField === '')) {
            throw new ValidationException([
                'track_updates_log_field' => 'Укажите поле для истории обновлений.',
            ]);
        }
    }

    public function beforeSave(): void
    {
        $existingSorting = CatalogSorting::getSortingSettings($this);

        $enabled = $this->sortingEnabledVirtual !== null
            ? $this->sortingEnabledVirtual
            : ($existingSorting['enabled'] ?? null);

        $default = $this->sortingDefaultVirtual !== null
            ? $this->sortingDefaultVirtual
            : ($existingSorting['default'] ?? null);

        // Determine allowed selection taking into account form submission behaviour:
        // - If virtual value set (setter called) use it.
        // - Else, if HTTP form was submitted and the request contains the 'sorting_allowed'
        //   key (possibly nested) use its value (could be empty array when all unchecked).
        // - Otherwise fall back to existing saved value.
        $allowed = null;
        if ($this->sortingAllowedVirtual !== null) {
            $allowed = $this->sortingAllowedVirtual;
        } else {
            try {
                $postAll = request()->all();

                $containsKey = false;
                $foundValue = null;

                // First check top-level and one-level nested arrays for 'sorting_allowed'
                foreach ($postAll as $k => $v) {
                    if ($k === 'sorting_allowed') {
                        $containsKey = true;
                        $foundValue = $v;
                        break;
                    }
                    if (is_array($v) && array_key_exists('sorting_allowed', $v)) {
                        $containsKey = true;
                        $foundValue = $v['sorting_allowed'];
                        break;
                    }
                }

                if (!$containsKey) {
                    // Deep recursive search for key 'sorting_allowed'
                    $found = function ($arr) use (&$found, &$containsKey, &$foundValue) {
                        if (!is_array($arr)) return;
                        foreach ($arr as $k => $v) {
                            if ($k === 'sorting_allowed') {
                                $containsKey = true;
                                $foundValue = $v;
                                return;
                            }
                            if (is_array($v)) {
                                $found($v);
                                if ($containsKey) return;
                            }
                        }
                    };
                    $found($postAll);
                }

                if ($containsKey) {
                    $allowed = is_array($foundValue) ? $foundValue : (is_string($foundValue) ? [$foundValue] : []);
                } else {
                    // Form submitted but no sorting_allowed key -> treat as explicit empty selection
                    // Only treat as form-submitted if request method is POST
                    $allowed = request()->method() === 'POST' ? [] : ($existingSorting['allowed'] ?? null);
                }
            } catch (\Throwable $e) {
                $allowed = $existingSorting['allowed'] ?? null;
            }
        }

        // Expand group keys (like 'date'/'name') from admin UI into concrete codes
        $expandedAllowed = CatalogSorting::expandAllowedSelection($this, $allowed);
        $expandedDefault = CatalogSorting::expandDefaultSelection($this, is_string($default) ? $default : null);

        $logField = $this->resolveTrackUpdatesLogField();

        $settings = CatalogSorting::mergeSortingSettings(
            $this,
            $this->settings,
            $expandedDefault,
            $expandedAllowed,
            $enabled
        );

        $this->settings = CatalogSorting::mergeTrackUpdatesSettings(
            $this,
            $settings,
            $this->trackUpdatesEnabledVirtual,
            $this->trackUpdatesFieldVirtual,
            $logField,
            $this->trackUpdatesBadgeEnabledVirtual,
            $this->trackUpdatesBadgeDaysVirtual
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected function mandatoryFeatures(): array
    {
        return ['files', 'views'];
    }

    public function getFeatureOptions(): array
    {
        return [
            'comments'   => 'Comments',
            'rating'     => 'Rating',
            'moderation' => 'Moderation queue',
        ];
    }

    public function getFeaturesAttribute($value)
    {
        return $this->normalizeFeatures($value);
    }

    public function setFeaturesAttribute($value): void
    {
        $this->attributes['features'] = json_encode($this->normalizeFeatures($value));
    }

    public function getFeatureCommentsAttribute(): bool
    {
        return $this->hasFeature('comments');
    }

    public function setFeatureCommentsAttribute($value): void
    {
        $this->writeFeatureToggle('comments', $value);
    }

    public function getFeatureRatingAttribute(): bool
    {
        return $this->hasFeature('rating');
    }

    public function setFeatureRatingAttribute($value): void
    {
        $this->writeFeatureToggle('rating', $value);
    }

    public function getFeatureModerationAttribute(): bool
    {
        return $this->hasFeature('moderation');
    }

    public function setFeatureModerationAttribute($value): void
    {
        $this->writeFeatureToggle('moderation', $value);
    }

    private function normalizeFeatures($value): array
    {
        $features = $this->decodeFeatures($value);

        $features = array_values(array_unique(array_merge($features, $this->mandatoryFeatures())));

        return $features;
    }

    private function decodeFeatures($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        if (is_array($value)) {
            return $value;
        }

        return [];
    }

    private function hasFeature(string $code): bool
    {
        return in_array($code, $this->normalizeFeatures($this->attributes['features'] ?? null), true);
    }

    private function writeFeatureToggle(string $code, $value): void
    {
        $features = $this->normalizeFeatures($this->attributes['features'] ?? null);
        $enabled = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($enabled) {
            if (!in_array($code, $features, true)) {
                $features[] = $code;
            }
        } else {
            $features = array_values(array_filter($features, static function ($item) use ($code) {
                return $item !== $code;
            }));
        }

        $features = $this->normalizeFeatures($features);

        $this->attributes['features'] = json_encode($features);
    }

    public function getSortingEnabledAttribute(): bool
    {
        if ($this->sortingEnabledVirtual !== null) {
            return (bool) $this->sortingEnabledVirtual;
        }

        $settings = CatalogSorting::getSortingSettings($this);

        return isset($settings['enabled']) ? (bool) $settings['enabled'] : true;
    }

    public function setSortingEnabledAttribute($value): void
    {
        $this->sortingEnabledVirtual = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($this->sortingEnabledVirtual === null) {
            $this->sortingEnabledVirtual = false;
        }
    }

    public function getSettingsJsonAttribute(): string
    {
        $settings = $this->settings;

        if (is_string($settings)) {
            $decoded = json_decode($settings, true);
            if (is_array($decoded)) {
                $settings = $decoded;
            }
        }

        if (!is_array($settings)) {
            $settings = [];
        }

        return json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function setSettingsJsonAttribute($value): void
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $this->settings = $decoded;
                return;
            }
        }

        if (is_array($value)) {
            $this->settings = $value;
        }
    }

    public function getSortingDefaultOptions(): array
    {
        // Default select should list all concrete sort definitions (asc/desc states),
        // independent from the 'allowed' visibility list.
        return CatalogSorting::optionLabelsWithStatus($this);
    }

    public function getSortingAllowedOptions(): array
    {
        return CatalogSorting::adminOptions($this);
    }

    public function getSortingDefaultAttribute(): ?string
    {
        if ($this->sortingDefaultVirtual !== null) {
            return $this->sortingDefaultVirtual;
        }

        $settings = CatalogSorting::getSortingSettings($this);

        if (isset($settings['default']) && is_string($settings['default'])) {
            return $settings['default'];
        }

        return CatalogSorting::resolveSortCode($this, null);
    }

    public function setSortingDefaultAttribute($value): void
    {
        $this->sortingDefaultVirtual = is_string($value) ? $value : null;
    }

    public function getSortingAllowedAttribute(): array
    {
        if ($this->sortingAllowedVirtual !== null) {
            return $this->sortingAllowedVirtual;
        }
        // Need to detect whether admin explicitly saved an 'allowed' key (even if empty),
        // otherwise fall back to listing admin option keys.
        // Read raw settings to determine whether 'allowed' was explicitly saved.
        $rawSettings = $this->settings;
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

        $allowed = $explicitAllowedExists && is_array($settingsArr['sorting']['allowed'])
            ? $settingsArr['sorting']['allowed']
            : null;

        if ($allowed === null) {
            // No explicit saved allowed -> fallback to admin option keys
            return array_keys(CatalogSorting::adminOptions($this));
        }

        // Map concrete saved codes back to admin option keys (group keys when applicable)
        $result = [];
        foreach ($allowed as $code) {
            if (!is_string($code) || $code === '') {
                continue;
            }
            $group = CatalogSorting::findAdminKeyForCode($this, $code);
            if ($group !== null) {
                $result[] = $group;
                continue;
            }
            $result[] = $code;
        }

        // Deduplicate and preserve keys as list
        $result = array_values(array_unique($result));

        return $result;
    }

    public function setSortingAllowedAttribute($value): void
    {
        $this->sortingAllowedVirtual = is_array($value)
            ? array_values($value)
            : (is_string($value) ? [$value] : []);
    }
    public function createDefaultItemFields(?string $sessionKey = null): array
    {
        $effectiveSessionKey = $this->exists ? null : $sessionKey;

        $defaultFields = [
            [
                'name'        => 'Название материала',
                'code'        => 'title',
                'type'        => 'text',
                'is_required' => true,
                'is_enabled'  => true,
                'sort_order'  => 10,
            ],
            [
                'name'        => 'URL',
                'code'        => 'slug',
                'type'        => 'slug',
                'is_required' => true,
                'is_enabled'  => true,
                'sort_order'  => 20,
                'options'     => [
                    'slug_source' => 'title',
                ],
            ],
            [
                'name'        => 'Краткое описание материала',
                'code'        => 'brief',
                'type'        => 'textarea',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 30,
            ],
            [
                'name'        => 'Полный текст материала',
                'code'        => 'message',
                'type'        => 'richeditor',
                'is_required' => true,
                'is_enabled'  => true,
                'sort_order'  => 40,
            ],
            [
                'name'        => 'Версия материала',
                'code'        => 'version',
                'type'        => 'text',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 50,
            ],
            [
                'name'        => 'Скриншоты',
                'code'        => 'screenshot',
                'type'        => 'file_multi',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 60,
                'options'     => [
                    'mode' => 'image',
                    'max_files' => 10,
                ],
            ],
            [
                'name'        => 'Файл-архив',
                'code'        => 'archive',
                'type'        => 'file_single',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 70,
                'options'     => [
                    'mode' => 'file',
                    'file_types' => 'zip,rar,7z',
                ],
            ],
            [
                'name'        => 'Имя автора материала',
                'code'        => 'author_name',
                'type'        => 'text',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 80,
            ],
            [
                'name'        => 'Email автора материала',
                'code'        => 'author_email',
                'type'        => 'text',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 90,
            ],
            [
                'name'        => 'Ссылка на источник материала',
                'code'        => 'source',
                'type'        => 'text',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 100,
            ],
            [
                'name'        => 'Ссылка на документацию',
                'code'        => 'docpage_url',
                'type'        => 'text',
                'is_required' => false,
                'is_enabled'  => true,
                'sort_order'  => 110,
            ],
        ];

        $existingQuery = $this->fields();
        if (!$this->exists && $effectiveSessionKey) {
            $existingQuery = $existingQuery->withDeferred($effectiveSessionKey);
        }

        if ($existingQuery->count() > 0) {
            return [];
        }

        $created = [];

        foreach ($defaultFields as $fieldData) {
            $fieldModel = new Field();
            $fieldModel->fill($fieldData);
            $fieldModel->save(null, $effectiveSessionKey);
            $this->fields()->add($fieldModel, $effectiveSessionKey);
            $created[] = $fieldModel;
        }

        return $created;
    }


    public function getTrackUpdatesEnabledAttribute(): bool
    {
        if ($this->trackUpdatesEnabledVirtual !== null) {
            return (bool) $this->trackUpdatesEnabledVirtual;
        }

        $settings = CatalogSorting::getTrackUpdatesSettings($this);

        return isset($settings['enabled']) ? (bool) $settings['enabled'] : false;
    }

    public function setTrackUpdatesEnabledAttribute($value): void
    {
        $this->trackUpdatesEnabledVirtual = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    public function getTrackUpdatesFieldAttribute(): ?string
    {
        if ($this->trackUpdatesFieldVirtual !== null) {
            return $this->trackUpdatesFieldVirtual;
        }

        $settings = CatalogSorting::getTrackUpdatesSettings($this);

        return isset($settings['field']) && is_string($settings['field'])
            ? $settings['field']
            : null;
    }

    public function setTrackUpdatesFieldAttribute($value): void
    {
        $this->trackUpdatesFieldVirtual = is_string($value) && $value !== '' ? $value : null;
    }

    public function getTrackUpdatesLogFieldAttribute(): ?string
    {
        if ($this->trackUpdatesLogFieldVirtual !== null) {
            return $this->trackUpdatesLogFieldVirtual;
        }

        $settings = CatalogSorting::getTrackUpdatesSettings($this);

        return isset($settings['log_field']) && is_string($settings['log_field']) && $settings['log_field'] !== ''
            ? $settings['log_field']
            : null;
    }

    public function setTrackUpdatesLogFieldAttribute($value): void
    {
        $this->trackUpdatesLogFieldVirtual = is_string($value) && $value !== '' ? $value : null;
    }

    public function getTrackUpdatesFieldOptions(): array
    {
        $options = [
            'updated_at'    => 'Дата обновления',
            'published_at'  => 'Дата публикации',
            'version'       => 'Версия материала',
        ];

        $fieldsQuery = $this->fields();
        if (!$this->exists && $this->sessionKey ?? false) {
            $fieldsQuery = $fieldsQuery->withDeferred($this->sessionKey);
        }

        $fieldsQuery->get()->each(function (Field $field) use (&$options) {
            $options[$field->code] = $field->name;
        });

        return $options;
    }

    public function getTrackUpdatesBadgeEnabledAttribute(): bool
    {
        if ($this->trackUpdatesBadgeEnabledVirtual !== null) {
            return (bool) $this->trackUpdatesBadgeEnabledVirtual;
        }

        $settings = CatalogSorting::getTrackUpdatesSettings($this);

        return isset($settings['badge_enabled']) ? (bool) $settings['badge_enabled'] : false;
    }

    public function setTrackUpdatesBadgeEnabledAttribute($value): void
    {
        $this->trackUpdatesBadgeEnabledVirtual = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    public function getTrackUpdatesBadgeDaysAttribute(): int
    {
        if ($this->trackUpdatesBadgeDaysVirtual !== null) {
            return (int) $this->trackUpdatesBadgeDaysVirtual;
        }

        $settings = CatalogSorting::getTrackUpdatesSettings($this);

        return isset($settings['badge_days']) ? (int) $settings['badge_days'] : 7;
    }

    public function setTrackUpdatesBadgeDaysAttribute($value): void
    {
        $this->trackUpdatesBadgeDaysVirtual = is_numeric($value) ? (int) $value : null;
    }

    public function getTrackUpdatesLogFieldOptions(): array
    {
        $options = [];

        $fieldsQuery = $this->fields();
        if (!$this->exists && $this->sessionKey ?? false) {
            $fieldsQuery = $fieldsQuery->withDeferred($this->sessionKey);
        }

        $fieldsQuery->get()->each(function (Field $field) use (&$options) {
            $options[$field->code] = $field->name;
        });

        return $options;
    }

    private function resolveTrackUpdatesLogField(): ?string
    {
        $selection = $this->trackUpdatesLogFieldVirtual;

        if (is_string($selection) && $selection !== '') {
            return $selection;
        }

        $existing = CatalogSorting::getTrackUpdatesSettings($this);
        return isset($existing['log_field']) && is_string($existing['log_field']) && $existing['log_field'] !== ''
            ? $existing['log_field']
            : null;
    }

    private function ensureLogFieldExists(string $code): void
    {
        $fieldsQuery = $this->fields();
        $sessionKey = $this->sessionKey ?? null;
        if (!$this->exists && $sessionKey) {
            $fieldsQuery = $fieldsQuery->withDeferred($sessionKey);
        }

        if ($fieldsQuery->where('code', $code)->first()) {
            return;
        }

        $field = new Field();
        $field->name = 'История обновлений';
        $field->code = $code;
        $field->type = 'textarea';
        $field->is_required = false;
        $field->is_enabled = false; // скрываем в формах, лог пишется автоматически
        $field->sort_order = 999;
        $field->save(null, $sessionKey);
        $this->fields()->add($field, $sessionKey);
    }
}
