<?php namespace Samvol\Catalog\Components;

use Cms\Classes\ComponentBase;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Models\Item;
use Samvol\Catalog\Services\CatalogSorting;

class CatalogList extends ComponentBase
{
    protected ?Catalog $catalog = null;

    public function componentDetails(): array
    {
        return [
            'name' => 'Catalog List',
            'description' => 'Provides catalog meta and paginated items without markup.'
        ];
    }

    public function defineProperties(): array
    {
        return [
            'catalogCode' => [
                'title'       => 'Catalog code',
                'type'        => 'string',
                'default'     => '{{ :catalog }}',
                'placeholder' => 'products'
            ],
            'categorySlug' => [
                'title'   => 'Category slug parameter',
                'type'    => 'string',
                'default' => '{{ :category }}'
            ],
            'status' => [
                'title'   => 'Status filter',
                'type'    => 'dropdown',
                'default' => Item::STATUS_PUBLISHED,
                'options' => [
                    Item::STATUS_PUBLISHED => 'Published only',
                    'all' => 'All statuses',
                ]
            ],
            'perPage' => [
                'title'   => 'Per page',
                'type'    => 'string',
                'default' => '20'
            ],
            'pageParam' => [
                'title'   => 'Page parameter',
                'type'    => 'string',
                'default' => 'page'
            ],
        ];
    }

    public function onRun(): void
    {
        $this->catalog = $this->loadCatalog();
        $currentSort = $this->catalog
            ? CatalogSorting::resolveSortCode($this->catalog, $this->getRequestedSort())
            : null;

        $items = $this->catalog
            ? $this->loadItems($currentSort)
            : collect();

        $this->page['catalog'] = $this->catalog;
        $this->page['items'] = $items;
        $this->page['fields'] = $this->catalog
            ? $this->catalog->fields()->enabled()->ordered()->get()
            : collect();
        $this->page['features'] = $this->catalog ? ($this->catalog->features ?: []) : [];
        $this->page['categories'] = $this->catalog
            ? $this->catalog->categories()->active()->get()
            : collect();
        $this->page['currentSort'] = CatalogSorting::isEnabled($this->catalog) ? $currentSort : null;
        $this->page['availableSorts'] = $this->catalog && CatalogSorting::isEnabled($this->catalog)
            ? CatalogSorting::getAvailableSorts($this->catalog)
            : [];
    }

    protected function loadCatalog(): ?Catalog
    {
        $code = $this->property('catalogCode');
        if (!$code) {
            return null;
        }

        return Catalog::active()->whereCode($code)->first();
    }

    protected function loadItems(?string $sortCode)
    {
        if (!$this->catalog) {
            return collect();
        }

        $query = Item::with('category')->where('catalog_id', $this->catalog->id);

        if ($this->property('status') !== 'all') {
            $query->where('status', Item::STATUS_PUBLISHED);
        }

        if ($this->property('categorySlug')) {
            $category = $this->catalog->categories()->where('slug', $this->property('categorySlug'))->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($sortCode) {
            if (CatalogSorting::isEnabled($this->catalog)) {
                CatalogSorting::applySorting($query, $this->catalog, $sortCode);
            }
        } else {
            $query->orderBy('published_at', 'desc');
        }

        return $query->paginate(
            (int) $this->property('perPage'),
            ['*'],
            $this->property('pageParam')
        );
    }


    protected function getRequestedSort(): ?string
    {
        $sort = request()->query('sort');
        return is_string($sort) ? $sort : null;
    }
}
