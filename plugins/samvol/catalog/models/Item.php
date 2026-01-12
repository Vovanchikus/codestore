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

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function beforeValidate(): void
    {
        $this->applyDynamicRules();
    }

    public function applyDynamicRules(): void
    {
        $catalog = $this->catalog ?: Catalog::find($this->catalog_id);
        if (!$catalog) {
            return;
        }

        $rules = [
            'catalog_id' => 'required',
            'status' => 'required|in:' . implode(',', array_keys(self::statusOptions())),
            'category_id' => 'nullable|exists:samvol_catalog_categories,id'
        ];

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
        // Фиксируем изменения для логов
        if ($this->isDirty('data')) {
            Log::info('Catalog Item data updated', [
                'id' => $this->id,
                'data' => $this->data,
            ]);
        }
        $currentData = $this->normalizeDataArray($this->data);
        $originalData = $this->normalizeDataArray($this->getOriginal('data'));
        $history = $this->normalizeHistory($currentData['_update_history'] ?? []);

        $catalog = $this->catalog ?: Catalog::find($this->catalog_id);
        $now = Carbon::now();

        // Отслеживание обновлений: фиксируем изменение конкретного поля в отдельном журнале
        $trackedChanged = false;
        $trackedFieldUsed = null;

        if ($catalog) {
            $tracking = CatalogSorting::getTrackUpdatesSettings($catalog);
            $trackEnabled = !empty($tracking['enabled']);
            $trackField = $tracking['field'] ?? null;
            $logField = $tracking['log_field'] ?? null;

            if ($trackEnabled && is_string($trackField) && $trackField !== '') {
                $trackedChanged = $this->didFieldChange($trackField, $currentData, $originalData);
                $trackedFieldUsed = $trackedChanged ? $trackField : null;

                if ($trackedChanged && $logField) {
                    $log = $this->normalizeTrackLog($currentData[$logField] ?? []);
                    $log[] = [
                        'date' => $now->toDateTimeString(),
                        'text' => $this->buildTrackUpdateText($catalog, $trackField),
                    ];
                    $currentData[$logField] = $log;
                }
            }
        }

        $manualRaise = $this->manualRaiseFlag;
        // Поднимаем: ручной флаг или изменение отслеживаемого поля (tracking).
        $shouldRaise = $manualRaise || $trackedChanged;

        if ($shouldRaise) {
            $this->published_at = $now;
            $history[] = $this->makeHistoryEntry($now, $trackedFieldUsed, $manualRaise);
            $currentData['_update_history'] = $history;
        }

        $this->data = $currentData;

        $this->manualRaiseFlag = false;
    }

    public function afterSave(): void
    {
        // Чекбокс «Поднять материал» всегда сбрасывается после сохранения
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
            if (!empty($this->data['title'])) {
                return $this->data['title'];
            }
            if (!empty($this->data['name'])) {
                return $this->data['name'];
            }
        }

        return 'Item #' . $this->id;
    }

    protected function getValidationAttributeForField(Field $field): string
    {
        if (in_array($field->type, ['file_single', 'file_multi'], true)) {
            return $field->code;
        }

        return 'data.' . $field->code;
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
