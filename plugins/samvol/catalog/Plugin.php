<?php namespace Samvol\Catalog;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Catalog',
            'description' => 'Flexible multi-catalog engine with dynamic fields for Winter CMS.',
            'author'      => 'Samvol',
            'icon'        => 'icon-archive'
        ];
    }

    public function registerComponents(): array
    {
        return [
            '\\Samvol\\Catalog\\Components\\CatalogList' => 'catalogList',
            '\\Samvol\\Catalog\\Components\\CatalogItem' => 'catalogItem',
            '\\Samvol\\Catalog\\Components\\CatalogForm' => 'catalogForm',
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'samvol.catalog.access_catalogs' => [
                'tab'   => 'Catalog',
                'label' => 'Manage catalogs'
            ],
            'samvol.catalog.access_items' => [
                'tab'   => 'Catalog',
                'label' => 'Manage items'
            ],
            'samvol.catalog.access_categories' => [
                'tab'   => 'Catalog',
                'label' => 'Manage categories'
            ],
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'catalogs' => [
                'label'       => 'Catalogs',
                'url'         => Backend::url('samvol/catalog/catalogs'),
                'icon'        => 'icon-archive',
                'permissions' => ['samvol.catalog.*'],
                'order'       => 500,
                'sideMenu'    => [
                    'catalogs' => [
                        'label'       => 'Catalogs',
                        'icon'        => 'icon-archive',
                        'url'         => Backend::url('samvol/catalog/catalogs'),
                        'permissions' => ['samvol.catalog.access_catalogs'],
                    ],
                    'items' => [
                        'label'       => 'Items',
                        'icon'        => 'icon-th-list',
                        'url'         => Backend::url('samvol/catalog/items'),
                        'permissions' => ['samvol.catalog.access_items'],
                    ],
                    'categories' => [
                        'label'       => 'Categories',
                        'icon'        => 'icon-tags',
                        'url'         => Backend::url('samvol/catalog/categories'),
                        'permissions' => ['samvol.catalog.access_categories'],
                    ],
                ],
            ],
        ];
    }
}
