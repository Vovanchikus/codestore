<?php namespace Samvol\Catalog\Components;

use Cms\Classes\ComponentBase;
use Samvol\Catalog\Models\Catalog;

class CatalogsList extends ComponentBase
{
    public function componentDetails(): array
    {
        return [
            'name' => 'Catalogs List',
            'description' => 'Provides all active catalogs for navigation without PHP in Twig.'
        ];
    }

    public function defineProperties(): array
    {
        return [];
    }

    public function onRun(): void
    {
        // Fetch all active catalogs and expose to Twig
        $this->page['catalogs'] = Catalog::active()->get();
    }
}
