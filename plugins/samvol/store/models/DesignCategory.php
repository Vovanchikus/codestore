<?php namespace Samvol\Store\Models;

use Model;

/**
 * Model
 */
class DesignCategory extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    use \Winter\Storm\Database\Traits\Sluggable;

    protected $slugs = ['slug' => 'name'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samvol_store_design_categories';

    public $hasMany = [
        'designs' => 'Samvol\Store\Models\Design'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Attribute names to encode and decode using JSON.
     */
    public $jsonable = [];
}
