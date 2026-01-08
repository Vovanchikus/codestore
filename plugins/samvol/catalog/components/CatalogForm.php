<?php namespace Samvol\Catalog\Components;

use ApplicationException;
use Cms\Classes\ComponentBase;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Models\Item;

class CatalogForm extends ComponentBase
{
    protected ?Catalog $catalog = null;
    protected ?Item $item = null;

    public function componentDetails(): array
    {
        return [
            'name' => 'Catalog Form',
            'description' => 'Headless form helper that prepares catalog meta and persists items.'
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
                'title'   => 'Item ID (for edit mode)',
                'type'    => 'string',
                'default' => '{{ :item }}'
            ],
            'autoPublish' => [
                'title'   => 'Publish automatically',
                'type'    => 'checkbox',
                'default' => false
            ],
        ];
    }

    public function onRun(): void
    {
        $this->catalog = $this->loadCatalog();
        $this->item = $this->loadItem();
        $this->prepareVars();
    }

    public function onSave()
    {
        if (!$this->catalog) {
            throw new ApplicationException('Catalog not resolved.');
        }

        $payload = post('Item', []);
        $item = $this->item ?: new Item();
        $item->catalog_id = $this->catalog->id;
        $item->category_id = array_get($payload, 'category_id');
        $item->status = $this->resolveStatus(array_get($payload, 'status'));
        $item->published_at = array_get($payload, 'published_at');

        $dynamic = array_get($payload, 'data', post('data', []));
        $item->data = is_array($dynamic) ? $dynamic : [];

        if ($this->property('autoPublish')) {
            $item->status = Item::STATUS_PUBLISHED;
        }

        if ($item->status === Item::STATUS_PUBLISHED && !$item->published_at) {
            $item->published_at = now();
        }

        $item->save();
        $this->item = $item;

        $this->prepareVars();

        return [
            'item' => $item->fresh()
        ];
    }

    protected function resolveStatus(?string $status): string
    {
        return array_key_exists($status, Item::statusOptions())
            ? $status
            : Item::STATUS_DRAFT;
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
        $itemId = (int) $this->property('itemId');
        if (!$itemId || !$this->catalog) {
            return null;
        }

        return Item::where('catalog_id', $this->catalog->id)->find($itemId);
    }

    protected function prepareVars(): void
    {
        $this->page['catalog'] = $this->catalog;
        $this->page['item'] = $this->item;
        $this->page['fields'] = $this->catalog ? $this->catalog->fields()->ordered()->get() : collect();
        $this->page['features'] = $this->catalog ? ($this->catalog->features ?: []) : [];
    }
}
