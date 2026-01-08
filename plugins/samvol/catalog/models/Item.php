<?php namespace Samvol\Catalog\Models;

use Model;
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
    ];

    protected $jsonable = ['data'];

    protected $dates = ['created_at', 'updated_at', 'published_at'];

    public $belongsTo = [
        'catalog' => [Catalog::class],
        'category' => [Category::class]
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
                $rules['data.' . $field->code] = 'required';
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
}
