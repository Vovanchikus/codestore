<?php namespace Samvol\Catalog\Models;

use Model;
use Winter\Storm\Database\Traits\Validation;

class Field extends Model
{
    use Validation;

    protected $table = 'samvol_catalog_fields';

    protected $fillable = [
        'catalog_id',
        'name',
        'code',
        'type',
        'is_required',
        'options',
        'sort_order',
    ];

    protected $jsonable = ['options'];

    public $rules = [
        'name' => 'required',
        'code' => 'required',
        'type' => 'required|in:text,textarea,number,select,checkbox,file,slug'
    ];

    public $belongsTo = [
        'catalog' => [Catalog::class]
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function setOptionsAttribute($value): void
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        if (empty($value)) {
            $this->attributes['options'] = null;
            return;
        }

        $this->attributes['options'] = json_encode($value);
    }

    public function setSortOrderAttribute($value): void
    {
        $this->attributes['sort_order'] = is_numeric($value) ? (int) $value : 0;
    }
}
