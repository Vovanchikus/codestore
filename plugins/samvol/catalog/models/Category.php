<?php namespace Samvol\Catalog\Models;

use Model;
use Winter\Storm\Database\Traits\NestedTree;
use Winter\Storm\Database\Traits\Validation;

class Category extends Model
{
    use Validation;
    use NestedTree;

    protected $table = 'samvol_catalog_categories';

    protected $fillable = [
        'catalog_id',
        'name',
        'slug',
        'description',
        'is_active',
        'data'
    ];

    protected $jsonable = ['data'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required'
    ];

    public $belongsTo = [
        'catalog' => [Catalog::class],
        'parent' => [self::class, 'key' => 'parent_id'],
    ];

    public $hasMany = [
        'items' => [Item::class],
        'children' => [self::class, 'key' => 'parent_id'],
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCatalog($query, $model)
    {
        $catalogId = $model->catalog_id ?: post('Item.catalog_id') ?: post('Category.catalog_id');
        if ($catalogId) {
            $query->where('catalog_id', $catalogId);
        }
    }

    public function setDataAttribute($value): void
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '' || $value === null) {
            $this->attributes['data'] = null;
            return;
        }

        if (is_array($value)) {
            $this->attributes['data'] = json_encode($value);
            return;
        }

        $decoded = json_decode((string) $value, true);
        $this->attributes['data'] = $decoded ? json_encode($decoded) : null;
    }
}
