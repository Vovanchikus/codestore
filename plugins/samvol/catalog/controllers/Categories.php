<?php namespace Samvol\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Categories extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    protected $requiredPermissions = ['samvol.catalog.access_categories'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samvol.Catalog', 'catalogs', 'categories');
    }
}
