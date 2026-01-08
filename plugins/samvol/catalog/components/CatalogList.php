<?php namespace Samvol\Catalog\Components;

use Cms\Classes\ComponentBase;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Models\Item;

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
        $items = $this->catalog ? $this->loadItems() : collect();

        $this->page['catalog'] = $this->catalog;
        $this->page['items'] = $items;
        $this->page['fields'] = $this->catalog ? $this->catalog->fields()->ordered()->get() : collect();
        $this->page['features'] = $this->catalog ? ($this->catalog->features ?: []) : [];
        $this->page['categories'] = $this->catalog
            ? $this->catalog->categories()->active()->get()
            : collect();
    }

    protected function loadCatalog(): ?Catalog
    {
        $code = $this->property('catalogCode');
        if (!$code) {
            return null;
        }

        return Catalog::active()->whereCode($code)->first();
    }

    protected function loadItems()
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

        $query->orderBy('created_at', 'desc');

        return $query->paginate(
            (int) $this->property('perPage'),
            ['*'],
            $this->property('pageParam')
        );
    }
}
