<?php namespace Samvol\Catalog\Components;

use Cms\Classes\ComponentBase;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Services\CatalogSorting as SortingService;

class CatalogSorting extends ComponentBase
{
    protected ?Catalog $catalog = null;

    public function componentDetails(): array
    {
        return [
            'name' => 'Catalog Sorting',
            'description' => 'Exposes sorting dropdown for catalog lists with clean markup and query support.'
        ];
    }

    public function defineProperties(): array
    {
        return [
            'catalogCode' => [
                'title'       => 'Catalog code',
                'type'        => 'string',
                'default'     => '{{ :catalog }}',
                'placeholder' => 'products',
                'comment'     => 'If empty, component will reuse the `catalog` object from the page context.'
            ],
            'defaultSort' => [
                'title'       => 'Default sort code',
                'type'        => 'string',
                'default'     => '',
                'placeholder' => 'date_desc',
                'comment'     => 'Applied when ?sort is absent; must be allowed in catalog settings.'
            ],
            'listSelector' => [
                'title'       => 'List selector',
                'type'        => 'string',
                'default'     => '#catalog-list',
                'comment'     => 'DOM selector for the list container to swap during AJAX refresh. Used by bundled JS.'
            ],
            'refreshMode' => [
                'title'   => 'Refresh mode',
                'type'    => 'dropdown',
                'default' => 'ajax',
                'options' => [
                    'ajax' => 'AJAX (fetch + replace container)',
                    'redirect' => 'Full redirect with ?sort=...'
                ],
            ],
        ];
    }

    public function onRun(): void
    {
        $this->addJs('/plugins/samvol/catalog/assets/js/catalog-sorting.js');

        $this->catalog = $this->resolveCatalog();

        $sortingEnabled = $this->catalog && SortingService::isEnabled($this->catalog);
        $availableSorts = $sortingEnabled ? SortingService::getAvailableSorts($this->catalog) : [];
        $currentSort = $sortingEnabled ? $this->resolveSortCode() : null;

        $this->page['availableSorts'] = $availableSorts;
        $this->page['currentSort'] = $currentSort;
        $this->page['sortParam'] = 'sort';
        $this->page['listSelector'] = $this->property('listSelector');
        $this->page['refreshMode'] = $this->property('refreshMode');
    }

    protected function resolveCatalog(): ?Catalog
    {
        if ($this->page && isset($this->page['catalog']) && $this->page['catalog'] instanceof Catalog) {
            return $this->page['catalog'];
        }

        $code = $this->property('catalogCode');
        if (!$code) {
            return null;
        }

        return Catalog::active()->whereCode($code)->first();
    }

    protected function resolveSortCode(): ?string
    {
        if (!$this->catalog) {
            return null;
        }

        $requested = $this->getRequestedSort();

        return SortingService::resolveSortCode($this->catalog, $requested);
    }

    protected function getRequestedSort(): ?string
    {
        $sort = request()->query('sort');
        if (is_string($sort) && $sort !== '') {
            return $sort;
        }

        $fallback = $this->property('defaultSort');

        return is_string($fallback) && $fallback !== '' ? $fallback : null;
    }
}
