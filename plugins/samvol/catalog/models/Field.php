<?php namespace Samvol\Catalog\Models;

use Model;
use Winter\Storm\Database\Traits\Validation;
use Winter\Storm\Exception\ValidationException;
use Winter\Storm\Support\Str;

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
        'is_enabled',
        'options',
        'sort_order',
    ];

    protected $jsonable = ['options'];

    public $rules = [
        'name' => 'required',
        'code' => 'required',
        'type' => 'required|in:text,textarea,number,select,checkbox,file,slug,richeditor,file_single,file_multi'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function beforeValidate(): void
    {
        if ($this->code) {
            // Preserve leading underscore (e.g. for internal fields like _update_log)
            $leadingUnderscore = str_starts_with($this->code, '_');
            $slug = Str::slug(ltrim($this->code, '_'), '_');
            $this->code = $leadingUnderscore ? '_' . $slug : $slug;
        }

        $catalogId = $this->catalog_id ?: ($this->catalog ? $this->catalog->id : null);
        if ($catalogId) {
            $query = self::where('catalog_id', $catalogId)
                ->where('code', $this->code);

            if ($this->exists) {
                $query->where('id', '<>', $this->id);
            }

            if ($query->exists()) {
                throw new ValidationException([
                    'code' => 'Поле с таким кодом уже существует в этом каталоге.',
                ]);
            }
        }
    }

    public $belongsTo = [
        'catalog' => [Catalog::class]
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
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
