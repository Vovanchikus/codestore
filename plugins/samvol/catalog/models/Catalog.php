<?php namespace Samvol\Catalog\Models;

use Model;
use Samvol\Catalog\Services\CatalogSorting;
use Winter\Storm\Database\Traits\Validation;

class Catalog extends Model
{
    use Validation;

    /**
     * Виртуальные поля для UI сортировки (не хранятся в таблице напрямую).
     */
    protected ?bool $sortingEnabledVirtual = null;
    protected ?string $sortingDefaultVirtual = null;
    protected ?array $sortingAllowedVirtual = null;

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

    public function beforeSave(): void
    {
        $existingSorting = CatalogSorting::getSortingSettings($this);

        $enabled = $this->sortingEnabledVirtual !== null
            ? $this->sortingEnabledVirtual
            : ($existingSorting['enabled'] ?? null);

        $default = $this->sortingDefaultVirtual !== null
            ? $this->sortingDefaultVirtual
            : ($existingSorting['default'] ?? null);

        $allowed = $this->sortingAllowedVirtual !== null
            ? $this->sortingAllowedVirtual
            : ($existingSorting['allowed'] ?? null);

        $this->settings = CatalogSorting::mergeSortingSettings(
            $this,
            $this->settings,
            $default,
            $allowed,
            $enabled
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
        return CatalogSorting::optionLabelsWithStatus($this);
    }

    public function getSortingAllowedOptions(): array
    {
        return CatalogSorting::optionLabelsWithStatus($this);
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

        $settings = CatalogSorting::getSortingSettings($this);

        return isset($settings['allowed']) && is_array($settings['allowed'])
            ? $settings['allowed']
            : [];
    }

    public function setSortingAllowedAttribute($value): void
    {
        $this->sortingAllowedVirtual = is_array($value)
            ? array_values($value)
            : (is_string($value) ? [$value] : []);
    }

    public function setSettingsAttribute($value): void
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '' || $value === null) {
            $this->attributes['settings'] = null;
            return;
        }

        $this->attributes['settings'] = is_array($value)
            ? json_encode($value)
            : $value;
    }

    public function createDefaultItemFields(?string $sessionKey = null): array
    {
        // If the catalog is already saved, avoid deferred binding to ensure rows persist immediately.
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
}
