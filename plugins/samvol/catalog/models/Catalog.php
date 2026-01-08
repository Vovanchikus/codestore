<?php namespace Samvol\Catalog\Models;

use Model;
use Winter\Storm\Database\Traits\Validation;

class Catalog extends Model
{
    use Validation;

    protected $table = 'samvol_catalogs';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'features',
        'settings',
    ];

    protected $jsonable = ['features', 'settings'];

    public $rules = [
        'name' => 'required',
        'code' => 'required|unique:samvol_catalogs'
    ];

    public $hasMany = [
        'fields' => [Field::class, 'order' => 'sort_order', 'delete' => true],
        'categories' => [Category::class, 'delete' => true],
        'items' => [Item::class, 'delete' => true],
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFeatureOptions(): array
    {
        return [
            'comments'   => 'Comments',
            'rating'     => 'Rating',
            'views'      => 'Views counter',
            'files'      => 'Files attachments',
            'moderation' => 'Moderation queue',
        ];
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
