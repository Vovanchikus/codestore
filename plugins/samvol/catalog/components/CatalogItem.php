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
            'itemSlug' => [
                'title'   => 'Item slug',
                'type'    => 'string',
                'default' => '{{ :itemSlug }}'
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

        if ($this->item) {
            $this->incrementViews($this->item);
        }

        $this->page['catalog'] = $this->catalog;
        $this->page['item'] = $this->item;
        $this->page['fields'] = $this->catalog
            ? $this->catalog->fields()->enabled()->ordered()->get()
            : collect();
        $this->page['features'] = $this->catalog ? ($this->catalog->features ?: []) : [];
    }

    public function onDownload()
    {
        $this->catalog = $this->catalog ?: $this->loadCatalog();
        $this->item = $this->item ?: $this->loadItem();

        if (!$this->item) {
            throw new ApplicationException('Item not found.');
        }

        $file = $this->item->archive;
        if (!$file) {
            throw new ApplicationException('File not found.');
        }

        $this->item->increment('downloads_count');

        return [
            'link' => $file->getPath(),
        ];
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
        $slug = trim((string) $this->property('itemSlug'));

        if (!$id && $slug === '') {
            throw new ApplicationException('Missing item identifier.');
        }

        $query = Item::where('catalog_id', $this->catalog->id);
        if ($this->property('onlyPublished')) {
            $query->where('status', Item::STATUS_PUBLISHED);
        }

        if ($id) {
            return $query->find($id);
        }

        return $query
            ->where('data->slug', $slug)
            ->first();
    }

    private function incrementViews(Item $item): void
    {
        $item->increment('views_count');
    }
}
