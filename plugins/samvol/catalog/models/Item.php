<?php namespace Samvol\Catalog\Models;

use Carbon\Carbon;
use Log;
use Model;
use Samvol\Catalog\Services\CatalogSorting;
use Samvol\Catalog\Models\Catalog;
use System\Models\File;
use Winter\Storm\Database\Traits\Validation;

class Item extends Model
{
    use Validation;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $table = 'samvol_catalog_items';

    protected $fillable = [
        'catalog_id',
        'category_id',
        'status',
        'data',
        'published_at',
        'manual_raise',
    ];

    protected $jsonable = ['data'];

    protected $dates = ['created_at', 'updated_at', 'published_at'];

    protected bool $manualRaiseFlag = false;

    public $belongsTo = [
        'catalog' => [Catalog::class],
        'category' => [Category::class]
    ];

    public $attachMany = [
        'screenshot' => [File::class],
    ];

    public $attachOne = [
        'archive' => [File::class],
    ];

    public $rules = [
        'catalog_id' => 'required',
        'status' => 'required'
    ];

    /**
     * Scope для опубликованных элементов
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Перед валидацией применяем динамические правила
     */
    public function beforeValidate(): void
    {
        $this->applyDynamicRules();
    }

    /**
     * Динамическая валидация полей каталога
     */
    public function applyDynamicRules(): void
    {
        $catalog = $this->catalog ?: Catalog::find($this->catalog_id);
        if (!$catalog) return;

        $rules = [
            'catalog_id' => 'required',
            'status' => 'required|in:' . implode(',', array_keys(self::statusOptions())),
            'category_id' => 'nullable|exists:samvol_catalog_categories,id'
        ];

        // Добавляем правила для обязательных полей
        $catalog->fields()->ordered()->get()->each(function (Field $field) use (&$rules) {
            if ($field->is_required) {
                $rules[$this->getValidationAttributeForField($field)] = 'required';
            }
        });

        $this->rules = $rules;
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public function getStatusOptions(): array
    {
        return self::statusOptions();
    }

    public function setManualRaiseAttribute($value): void
    {
        $this->manualRaiseFlag = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    public function getManualRaiseAttribute(): bool
    {
        return $this->manualRaiseFlag;
    }

    public function beforeSave(): void
    {
        // Логируем изменения
        if ($this->isDirty('data')) {
            Log::info('Catalog Item data updated', [
                'id' => $this->id,
                'data' => $this->data,
            ]);
        }

        $currentData = $this->normalizeDataArray($this->data);
        $originalData = $this->normalizeDataArray($this->getOriginal('data'));

        // При сохранении истории изменений используем оригинальную (сырую) историю
        // из базы, чтобы не потерять предыдущие записи, если форма админки не
        // передаёт служебные ключи `_update_history`/`update_log`.
        $history = $this->normalizeHistory($originalData['_update_history'] ?? $originalData['update_log'] ?? []);

        $catalog = $this->catalog ?: Catalog::find($this->catalog_id);
        $now = Carbon::now();

        // NOTE: не принудительно устанавливаем `published_at` при создании, чтобы
        // сохранить прежнее поведение: поднимаем материал только при ручном raise
        // или когда изменилось отслеживаемое поле (см. $shouldRaise).

        $trackedChanged = false;
        $trackedFieldUsed = null;

        if ($catalog) {
            // Получаем настройки трекинга изменений для каталога
            $tracking = CatalogSorting::getTrackUpdatesSettings($catalog);
            $trackEnabled = !empty($tracking['enabled']);
            $trackField = $tracking['field'] ?? null;
            $logField = $tracking['log_field'] ?? null;

            // ВАЖНО: при создании нового элемента мы НЕ считаем изменение отслеживаемого поля
            // — требование: бейдж и автоматическое поднятие должны срабатывать только при редактировании.
            if ($trackEnabled && is_string($trackField) && $trackField !== '' && $this->exists) {
                // Определяем, изменилось ли отслеживаемое поле (сравнение старых/новых значений)
                $trackedChanged = $this->didFieldChange($trackField, $currentData, $originalData);
                $trackedFieldUsed = $trackedChanged ? $trackField : null;



                // Если поле изменилось и задано поле для лога — добавляем запись в лог внутри данных
                if ($trackedChanged && $logField) {
                    // Берём существующий лог из оригинальных данных, чтобы не потерять
                    // предыдущие записи, и добавляем новую запись.
                    $log = $this->normalizeTrackLog($originalData[$logField] ?? []);
                    // Также учитываем возможный лог, пришедший из формы
                    $log = array_merge($log, $this->normalizeTrackLog($currentData[$logField] ?? []));
                    $log[] = [
                        'date' => $now->toDateTimeString(),
                        'text' => $this->buildTrackUpdateText($catalog, $trackField),
                        'field' => $trackField,
                        'manual' => false,
                    ];
                    $currentData[$logField] = $log;
                }
            }
        }

        $manualRaise = $this->manualRaiseFlag;
        $shouldRaise = $manualRaise || $trackedChanged;

        if ($shouldRaise) {
            $this->published_at = $now;
            $history[] = $this->makeHistoryEntry($now, $trackedFieldUsed, $manualRaise);
            $currentData['_update_history'] = $history;
        }

        // При создании нового материала по умолчанию устанавливаем версию, если пользователь не задал
        if (!$this->exists) {
            if (!isset($currentData['version']) || $currentData['version'] === '' || $currentData['version'] === null) {
                $currentData['version'] = '1.0.0';
            }
        }

        $this->data = $currentData;
        $this->manualRaiseFlag = false;
    }

    public function afterSave(): void
    {
        $this->manualRaiseFlag = false;
        unset($this->attributes['manual_raise']);
    }

    public function getCatalogIdOptions(): array
    {
        return Catalog::orderBy('name')->pluck('name', 'id')->all();
    }

    public function getDisplayNameAttribute(): string
    {
        if (is_array($this->data)) {
            if (!empty($this->data['title'])) return $this->data['title'];
            if (!empty($this->data['name'])) return $this->data['name'];
        }

        return 'Item #' . $this->id;
    }

    /**
     * Возвращает, должен ли отображаться бейдж "Обновлено" для данного элемента.
     * Логика: бейдж показывается только если для каталога включён флаг бейджей,
     * и элемент был обновлён в пределах последних N дней (настраивается в каталоге).
     */
    public function getIsRecentlyUpdatedAttribute(): bool
    {
        // Получаем каталог (если доступен)
        $catalog = $this->catalog ?: (isset($this->catalog_id) ? Catalog::find($this->catalog_id) : null);
        if (!$catalog) {
            return false;
        }

        // Если в каталоге фича бейджа отключена — не показываем
        if (!method_exists($catalog, 'getTrackUpdatesBadgeEnabledAttribute') || !$catalog->getTrackUpdatesBadgeEnabledAttribute()) {
            return false;
        }

        $days = method_exists($catalog, 'getTrackUpdatesBadgeDaysAttribute') ? (int) $catalog->getTrackUpdatesBadgeDaysAttribute() : 0;
        if ($days <= 0) {
            return false;
        }

        $trackedField = method_exists($catalog, 'getTrackUpdatesFieldAttribute') ? $catalog->getTrackUpdatesFieldAttribute() : null;
        if (!$trackedField) {
            return false;
        }

        // Бейдж показывается ТОЛЬКО для отредактированных записей
        if (!$this->exists) {
            return false;
        }

        $cutoff = Carbon::now()->subDays($days);

        // Если отслеживаемое поле — стандартный timestamp столбец, используем сравнение created_at/updated_at
        if (in_array($trackedField, ['updated_at', 'published_at', 'created_at'], true)) {
            $ts = $this->{$trackedField} ?? null;
            if (!$ts) {
                return false;
            }

            // Если обновление не отличалось от создания — считаем, что поле не редактировалось
            if ($this->created_at && $ts == $this->created_at) {
                return false;
            }

            try {
                $dt = $ts instanceof \DateTime ? Carbon::instance($ts) : new Carbon($ts);
            } catch (\Throwable $e) {
                return false;
            }

            return $dt->greaterThanOrEqualTo($cutoff);
        }

        // Читаем необработанные данные из колонки `data`, чтобы не полагаться на отфильтрованный
        // массив `$this->data`, который может быть очищен в `afterFetch()` и не содержать
        // служебные ключи вроде `_update_history`.
        $rawData = $this->normalizeDataArray($this->getOriginal('data') ?? $this->attributes['data'] ?? $this->data);

        // Для прочих полей — ищем в истории обновлений (`_update_history`) последнюю запись по этому полю
        $history = $this->normalizeHistory($rawData['_update_history'] ?? $rawData['update_log'] ?? []);
        if (!empty($history)) {
            // идём с конца, чтобы взять последнее изменение
            for ($i = count($history) - 1; $i >= 0; $i--) {
                $entry = $history[$i];
                if (!isset($entry['field'])) continue;
                if ($entry['field'] !== $trackedField) continue;

                $dateStr = $entry['date'] ?? null;
                if (!$dateStr) continue;

                try {
                    $dt = new Carbon($dateStr);
                } catch (\Throwable $e) {
                    continue;
                }

                return $dt->greaterThanOrEqualTo($cutoff);
            }
        }

        // Ничего не найдено — бейдж не показываем (строго: только при реальном изменении отслеживаемого поля)
        return false;
    }

    /**
     * Возвращает true, если элемент когда-либо поднимался/обновлялся в рамках механизма трекинга.
     * Используется для показа версии только после того, как материал был обновлён хотя бы однажды.
     */
    public function getHasEverBeenUpdatedAttribute(): bool
    {
        // Читаем сырые данные из колонки `data` — там хранится служебный `_update_history`.
        $rawData = $this->normalizeDataArray($this->getOriginal('data') ?? $this->attributes['data'] ?? $this->data);

        $historyRaw = $rawData['_update_history'] ?? $rawData['update_log'] ?? [];
        $history = $this->normalizeHistory($historyRaw);

        // Строго: версия считается существующей только если в истории есть запись
        // по конкретному отслеживаемому полю каталога. Игнорируем записи только
        // от ручного поднятия (когда поле `field` равно null).
        $catalog = $this->catalog ?: (isset($this->catalog_id) ? Catalog::find($this->catalog_id) : null);
        if (!$catalog) {
            return false;
        }

        $trackedField = method_exists($catalog, 'getTrackUpdatesFieldAttribute') ? $catalog->getTrackUpdatesFieldAttribute() : null;
        if (!$trackedField) {
            return false;
        }

        foreach ($history as $entry) {
            if (isset($entry['field']) && $entry['field'] === $trackedField) {
                return true;
            }
        }

        return false;
    }

    protected function getValidationAttributeForField(Field $field): string
    {
        if (in_array($field->type, ['file_single', 'file_multi'], true)) {
            return $field->code;
        }

        return 'data.' . $field->code;
    }

    /**
     * ------------------------------
     * ФИЛЬТР АКТИВНЫХ ПОЛЕЙ
     * ------------------------------
     * После загрузки элемента (afterFetch) оставляем в data только активные поля каталога
     * Таким образом в Twig item.data.FIELD будет содержать только активные поля
     */
    public function afterFetch(): void
    {
        $catalog = $this->catalog ?: Catalog::find($this->catalog_id);
        if (!$catalog || !is_array($this->data)) return;

        // Список активных полей
        $activeFields = $catalog->fields()->where('is_enabled', true)->pluck('code')->all();

        // Фильтруем data, оставляем только активные поля
        $this->data = array_filter($this->data, function($key) use ($activeFields) {
            return in_array($key, $activeFields, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function normalizeDataArray($value): array
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

    private function normalizeHistory($history): array
    {
        if (!is_array($history)) {
            return [];
        }

        $normalized = [];

        // Попытка получить каталог для формирования читаемого текста изменений
        $catalog = $this->catalog ?: (isset($this->catalog_id) ? Catalog::find($this->catalog_id) : null);

        foreach ($history as $entry) {
            // Если запись хранится как JSON-строка, пытаемся её декодировать
            if (is_string($entry)) {
                $decoded = json_decode($entry, true);
                if (is_array($decoded)) {
                    $entry = $decoded;
                } else {
                    $normalized[] = [
                        'date' => $entry,
                        'text' => '',
                        'field' => null,
                        'manual' => false,
                    ];
                    continue;
                }
            }

            if (is_array($entry)) {
                // Поддерживаем несколько формата записи: {date,text} или {ts,...}
                if (isset($entry['date']) && isset($entry['text'])) {
                    $normalized[] = [
                        'date' => $entry['date'],
                        'text' => $entry['text'],
                        'field' => $entry['field'] ?? null,
                        'manual' => (bool) ($entry['manual'] ?? false),
                    ];
                    continue;
                }

                if (isset($entry['ts'])) {
                    $text = $entry['text'] ?? '';
                    // Если текст не задан, попытаемся сформировать его по коду поля или флагу manual
                    if ($text === '' && isset($entry['field']) && $catalog) {
                        $f = $catalog->fields()->where('code', $entry['field'])->first();
                        if ($f) {
                            $text = 'Изменено поле: ' . $f->name;
                        }
                    }
                    if ($text === '' && !empty($entry['manual'])) {
                        $text = 'Ручное изменение';
                    }

                    $normalized[] = [
                        'date' => $entry['ts'],
                        'text' => $text,
                        'field' => $entry['field'] ?? null,
                        'manual' => (bool) ($entry['manual'] ?? false),
                    ];
                    continue;
                }
            }
        }

        return array_values(array_filter($normalized, static function ($item) {
            return isset($item['date']) && is_string($item['date']) && $item['date'] !== '';
        }));
    }

    private function makeHistoryEntry(Carbon $timestamp, ?string $field, bool $manual): array
    {
        return [
            'ts' => $timestamp->toDateTimeString(),
            'field' => $field,
            'manual' => $manual,
        ];
    }

    private function normalizeTrackLog($log): array
    {
        if (!is_array($log)) {
            return [];
        }

        $normalized = [];
        foreach ($log as $entry) {
            if (is_array($entry) && isset($entry['date']) && isset($entry['text'])) {
                $normalized[] = [
                    'date' => $entry['date'],
                    'text' => $entry['text'],
                ];
                continue;
            }

            if (is_string($entry)) {
                $normalized[] = [
                    'date' => $entry,
                    'text' => 'Изменение записи',
                ];
            }
        }

        return array_values(array_filter($normalized, static function ($item) {
            return isset($item['date']) && is_string($item['date']) && $item['date'] !== '' && isset($item['text']);
        }));
    }

    public function getNormalizedHistory(): array
    {
        // Начальное значение
        $history = [];

        // Поддержка поля журнала `update_log`.
        $logRaw = $this->data['update_log'] ?? null;

        if (is_string($logRaw)) {
            $text = trim($logRaw);
            if ($text !== '') {
                $date = $this->updated_at instanceof \DateTime
                    ? $this->updated_at->toDateTimeString()
                    : Carbon::now()->toDateTimeString();

                $history[] = [
                    'date' => $date,
                    'text' => $text,
                    'field' => null,
                    'manual' => false,
                ];
            }
        } elseif (is_array($logRaw)) {
            $logEntries = $this->normalizeTrackLog($logRaw);
            foreach ($logEntries as $le) {
                if (isset($le['date']) && isset($le['text'])) {
                    $history[] = [
                        'date' => $le['date'],
                        'text' => $le['text'],
                        'field' => null,
                        'manual' => false,
                    ];
                }
            }
        }

        // Сортируем по дате (последние первыми)
        if (!empty($history) && is_array($history)) {
            usort($history, static function ($a, $b) {
                return strtotime($b['date']) <=> strtotime($a['date']);
            });
        }

        return $history;
    }

    private function didFieldChange(string $fieldCode, array $currentData, array $originalData): bool
    {
        $isColumnField = array_key_exists($fieldCode, $this->attributes);

        [$oldValueRaw, $newValueRaw] = $isColumnField
            ? [$this->getOriginal($fieldCode), $this->{$fieldCode}]
            : [
                $originalData[$fieldCode] ?? null,
                $currentData[$fieldCode] ?? null,
            ];

        $normalize = static function ($value) {
            if (is_string($value)) {
                $trimmed = trim($value);
                return $trimmed === '' ? null : $trimmed;
            }
            if (is_array($value)) {
                return json_encode($value);
            }
            return $value;
        };

        $oldValue = $normalize($oldValueRaw);
        $newValue = $normalize($newValueRaw);

        return $oldValue !== $newValue;
    }

    private function buildTrackUpdateText(Catalog $catalog, string $fieldCode): string
    {
        $label = $fieldCode;

        // Стандартные поля
        $standard = [
            'updated_at' => 'Дата обновления',
            'published_at' => 'Дата публикации',
            'version' => 'Версия материала',
        ];

        if (isset($standard[$fieldCode])) {
            $label = $standard[$fieldCode];
        } else {
            $field = $catalog->fields()->where('code', $fieldCode)->first();
            if ($field) {
                $label = $field->name;
            }
        }

        return 'Изменено поле: ' . $label;
    }
}
