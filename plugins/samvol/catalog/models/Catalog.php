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
        $defaultFields = [
            [
                'name'        => 'Название материала',
                'code'        => 'title',
                'type'        => 'text',
                'is_required' => true,
                'sort_order'  => 10,
            ],
            [
                'name'        => 'URL',
                'code'        => 'slug',
                'type'        => 'slug',
                'is_required' => true,
                'sort_order'  => 20,
                'options'     => [
                    'slug_source' => 'title',
                ],
            ],
        ];

        $created = [];

        foreach ($defaultFields as $fieldData) {
            $query = $this->fields();
            if ($sessionKey) {
                $query = $query->withDeferred($sessionKey);
            }

            if ($query->where('code', $fieldData['code'])->exists()) {
                continue;
            }

            $fieldModel = new Field();
            $fieldModel->fill($fieldData);

            $fieldModel->save(null, $sessionKey);

            $this->fields()->add($fieldModel, $sessionKey);
            $created[] = $fieldModel;
        }

        return $created;
    }
}
