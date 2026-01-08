<?php namespace Samvol\Catalog\Components;

use Cms\Classes\ComponentBase;
use ApplicationException;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Models\Item;

class CatalogItem extends ComponentBase
{
    protected ?Catalog $catalog = null;
    protected ?Item $item = null;

    public function componentDetails(): array
    {
        return [
            'name' => 'Catalog Item',
            'description' => 'Loads a single catalog item and exposes raw data.'
        ];
    }

    public function defineProperties(): array
    {
        return [
            'catalogCode' => [
                'title'   => 'Catalog code',
                'type'    => 'string',
                'default' => '{{ :catalog }}'
            ],
            'itemId' => [
                'title'   => 'Item ID',
                'type'    => 'string',
                'default' => '{{ :item }}'
            ],
            'onlyPublished' => [
                'title'   => 'Only published',
                'type'    => 'checkbox',
                'default' => true
            ],
        ];
    }

    public function onRun(): void
    {
        $this->catalog = $this->loadCatalog();
        $this->item = $this->loadItem();

        $this->page['catalog'] = $this->catalog;
        $this->page['item'] = $this->item;
        $this->page['fields'] = $this->catalog ? $this->catalog->fields()->ordered()->get() : collect();
        $this->page['features'] = $this->catalog ? ($this->catalog->features ?: []) : [];
    }

    protected function loadCatalog(): ?Catalog
    {
        $code = $this->property('catalogCode');
        if (!$code) {
            return null;
        }

        return Catalog::active()->whereCode($code)->first();
    }

    protected function loadItem(): ?Item
    {
        if (!$this->catalog) {
            return null;
        }

        $id = (int) $this->property('itemId');
        if (!$id) {
            throw new ApplicationException('Missing item identifier.');
        }

        $query = Item::where('catalog_id', $this->catalog->id);
        if ($this->property('onlyPublished')) {
            $query->where('status', Item::STATUS_PUBLISHED);
        }

        return $query->find($id);
    }
}
