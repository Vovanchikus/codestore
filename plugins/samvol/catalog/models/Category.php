<?php namespace Samvol\Catalog\Models;

use Model;
use System\Models\File;
use Winter\Storm\Database\Traits\NestedTree;
use Winter\Storm\Database\Traits\Validation;
use Winter\Storm\Exception\ValidationException;
use Winter\Storm\Support\Arr;

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
        'icon_mode',
        'icon_svg',
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

    public $attachOne = [
        'icon' => File::class,
    ];

    public function beforeValidate(): void
    {
        // Treat icon as absent if user marked it for deletion in the form.
        $iconMarkedForDeletion = $this->iconMarkedForDeletion();

        $hasFile = $this->icon && $this->icon->exists && !$iconMarkedForDeletion;
        $hasSvg = trim((string) $this->icon_svg) !== '';

        // Determine mode from explicit choice or fallback to existing data.
        $this->icon_mode = $this->icon_mode ?: ($hasSvg ? 'svg' : 'file');

        if ($hasFile && $hasSvg) {
            throw new ValidationException([
                'icon' => 'Выберите либо загруженную иконку, либо SVG код, но не оба варианта.',
                'icon_svg' => 'Выберите либо загруженную иконку, либо SVG код, но не оба варианта.'
            ]);
        }
    }

    public function afterFetch(): void
    {
        // Preselect mode based on existing data so only the filled variant shows.
        if (!$this->icon_mode) {
            $this->icon_mode = ($this->icon && $this->icon->exists) ? 'file' : ((trim((string) $this->icon_svg) !== '') ? 'svg' : 'file');
        }
    }

    protected function iconMarkedForDeletion(): bool
    {
        $data = post();
        if (!$data) {
            return false;
        }

        $category = Arr::get($data, 'Category');
        if (!is_array($category)) {
            return false;
        }

        $icon = Arr::get($category, 'icon');
        if (!is_array($icon)) {
            return false;
        }

        return !empty($icon['_delete']);
    }

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
