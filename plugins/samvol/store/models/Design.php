<?php namespace Samvol\Store\Models;

use Model;

/**
 * Model
 */
class Design extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    use \Winter\Storm\Database\Traits\SoftDelete;

    use \Winter\Storm\Database\Traits\Sluggable;

    protected $slugs = ['slug' => 'title'];

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samvol_store_designs';

    public $belongsTo = [
        'category' => [
            'Samvol\Store\Models\DesignCategory',
            'key' => 'category_id'
        ],
        'added_by_user' => [
            'Rainlab\User\Models\User',
            'key' => 'added_by_user_id'
        ]
    ];

    public $attachMany = [
        'screenshots' => 'System\Models\File'
    ];

    public $attachOne = [
        'archive_path' => 'System\Models\File'
    ];


    protected $fillable = [
        'category_id',
        'added_by_user_id',
        'title',
        'slug',
        'original_price',
        'price',
        'desc',
        'author_name',
        'source'
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
