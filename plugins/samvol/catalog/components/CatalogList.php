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
        // Keep the raw requested sort for UI highlighting; resolve sort for actual query
        $requestedSort = $this->getRequestedSort();
        $resolvedSort = $this->catalog
            ? CatalogSorting::resolveSortCode($this->catalog, $requestedSort)
            : null;

        // determine current category if categorySlug property provided
        $currentCategory = null;
        if ($this->catalog && $this->property('categorySlug')) {
            $currentCategory = $this->catalog->categories()->where('slug', $this->property('categorySlug'))->first();
        }

        $items = $this->catalog
            ? $this->loadItems($resolvedSort)
            : collect();

        $this->page['catalog'] = $this->catalog;
        $this->page['currentCategory'] = $currentCategory;
        $this->page['items'] = $items;
        $this->page['fields'] = $this->catalog
            ? $this->catalog->fields()->enabled()->ordered()->get()
            : collect();
        $this->page['features'] = $this->catalog ? ($this->catalog->features ?: []) : [];
        $status = $this->property('status');

        $this->page['categories'] = $this->catalog
            ? $this->catalog->categories()->active()->withCount(['items' => function($q) use ($status) {
                if ($status !== 'all') {
                    $q->where('status', Item::STATUS_PUBLISHED);
                }
            }])->get()
            : collect();
        $this->page['currentSort'] = ($this->catalog && CatalogSorting::isEnabled($this->catalog)) ? $resolvedSort : null;
        $this->page['requestedSort'] = $requestedSort;
        $this->page['availableSorts'] = ($this->catalog && CatalogSorting::isEnabled($this->catalog))
            ? CatalogSorting::getAvailableSorts($this->catalog)
            : [];
        $this->page['groupedSorts'] = ($this->catalog && CatalogSorting::isEnabled($this->catalog))
            ? CatalogSorting::getGroupedSorts($this->catalog)
            : ['groups' => [], 'others' => []];
        // Provide default sort information and labels for frontend display.
        $allLabels = $this->catalog ? CatalogSorting::optionLabelsWithStatus($this->catalog) : [];
        $this->page['allSortLabels'] = $allLabels;
        $this->page['defaultSort'] = $resolvedSort;
        $this->page['defaultSortLabel'] = $resolvedSort && isset($allLabels[$resolvedSort]) ? $allLabels[$resolvedSort] : null;

        // Determine whether the default sort (resolved) is already present in the rendered lists
        $defaultIsRendered = false;
        if ($resolvedSort) {
            $gs = $this->page['groupedSorts'];
            if (isset($gs['groups']) && is_array($gs['groups'])) {
                foreach ($gs['groups'] as $g) {
                    if (isset($g['asc']) && $g['asc'] === $resolvedSort) {
                        $defaultIsRendered = true;
                        break;
                    }
                    if (isset($g['desc']) && $g['desc'] === $resolvedSort) {
                        $defaultIsRendered = true;
                        break;
                    }
                }
            }
            if (!$defaultIsRendered && isset($gs['others']) && is_array($gs['others']) && array_key_exists($resolvedSort, $gs['others'])) {
                $defaultIsRendered = true;
            }
            if (!$defaultIsRendered && isset($this->page['availableSorts']) && array_key_exists($resolvedSort, $this->page['availableSorts'])) {
                $defaultIsRendered = true;
            }
        }
        $this->page['defaultIsRendered'] = $defaultIsRendered;

        // Prepare minimal structure for template rendering to keep Twig simple.
        $sortingItems = [];
        $used = [];

        // label normalizer: remove leading "По " and uppercase first letter (multibyte-safe)
        $normalizeLabel = function ($label) {
            if (!is_string($label) || $label === '') {
                return $label;
            }
            // remove leading "По " (case-insensitive, unicode)
            $label = preg_replace('/^По\s+/u', '', $label);
            $first = mb_substr($label, 0, 1, 'UTF-8');
            $rest = mb_substr($label, 1, null, 'UTF-8');
            return mb_strtoupper($first, 'UTF-8') . $rest;
        };

        // Groups
        // Use UI sort (requested if present) to determine active state so toggling asc/desc stays highlighted
        $uiSort = $requestedSort ?: $resolvedSort;
        if (isset($gs['groups']) && is_array($gs['groups'])) {
            foreach ($gs['groups'] as $g) {
                $label = $g['label'] ?? ($g['key'] ?? '');
                $item = [
                    'type' => 'group',
                    'key' => $g['key'] ?? null,
                    'label' => $normalizeLabel($label),
                    'asc' => $g['asc'] ?? null,
                    'desc' => $g['desc'] ?? null,
                    'isActive' => ($uiSort && ($uiSort === ($g['asc'] ?? '') || $uiSort === ($g['desc'] ?? ''))),
                    'isDefault' => ($uiSort && ($uiSort === ($g['asc'] ?? '') || $uiSort === ($g['desc'] ?? '')))
                ];
                $sortingItems[] = $item;
                if (!empty($g['asc'])) $used[] = $g['asc'];
                if (!empty($g['desc'])) $used[] = $g['desc'];
            }
        }

        // Others from groupedSorts
        if (isset($gs['others']) && is_array($gs['others'])) {
            foreach ($gs['others'] as $code => $label) {
                if (in_array($code, $used, true)) continue;
                $sortingItems[] = [
                    'type' => 'single',
                    'code' => $code,
                    'label' => $normalizeLabel($label),
                    'isActive' => $uiSort && $uiSort === $code,
                    'isDefault' => $uiSort && $uiSort === $code,
                ];
                $used[] = $code;
            }
        }
            $this->page['currentSort'] = $resolvedSort; // resolved sort applied to query
            $this->page['requestedSort'] = $requestedSort; // raw requested sort from URL or component

        // Fallback: availableSorts
        if (is_array($this->page['availableSorts'])) {
            foreach ($this->page['availableSorts'] as $code => $label) {
                if (in_array($code, $used, true)) continue;
                $sortingItems[] = [
                    'type' => 'single',
                    'code' => $code,
                    'label' => $normalizeLabel($label),
                    'isActive' => $uiSort && $uiSort === $code,
                    'isDefault' => $uiSort && $uiSort === $code,
                ];
                $used[] = $code;
            }
        }

        $this->page['sortingItems'] = $sortingItems;

        // Expose simple sortItems and currentDir for legacy/simple theme partials
        $currentDir = request()->query('direction', 'desc');
        $this->page['currentDir'] = is_string($currentDir) ? $currentDir : 'desc';

        $simpleItems = [];
        // Prefer a logical order: groups first, then others, then available
        if (isset($gs['groups']) && is_array($gs['groups'])) {
            foreach ($gs['groups'] as $g) {
                $label = $g['label'] ?? ($g['key'] ?? '');
                // push a group marker using desc code by default
                $simpleItems[] = ['label' => $normalizeLabel($label), 'field' => $g['desc'] ?? ($g['asc'] ?? null)];
            }
        }

        if (isset($gs['others']) && is_array($gs['others'])) {
            foreach ($gs['others'] as $code => $label) {
                $simpleItems[] = ['label' => $label, 'field' => $code];
            }
        }

        // Do not append full `availableSorts` here — keep simple list focused on groups and others

        $this->page['sortItems'] = $simpleItems;
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
