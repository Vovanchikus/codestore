<?php namespace Samvol\Catalog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
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
        // Prepare pagination sequence in PHP (list of pages with 'ellipsis' tokens)
        if (is_object($items) && method_exists($items, 'lastPage')) {
            $sequence = $this->buildPaginationSequence($items, 3, 3, 1);
            $this->page['paginationPages'] = $sequence;
            $this->page['currentPage'] = (int) $items->currentPage();
            $this->page['lastPage'] = (int) $items->lastPage();

            // Debugging: log pagination values so we can inspect server-side generation
            try {
                Log::info('Catalog pagination generated', [
                    'catalog_id' => $this->catalog ? $this->catalog->id : null,
                    'current' => $this->page['currentPage'],
                    'last' => $this->page['lastPage'],
                    'sequence' => $sequence,
                ]);
            } catch (\Throwable $e) {
                // ignore logging errors
            }
        } else {
            $this->page['paginationPages'] = [];
            $this->page['currentPage'] = 1;
            $this->page['lastPage'] = 1;
        }
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


        // Prepare sorting items for Twig using centralised service method.
        $currentDir = request()->query('direction', 'desc');
        $this->page['currentDir'] = is_string($currentDir) ? $currentDir : 'desc';
        $this->page['sortingItems'] = ($this->catalog && CatalogSorting::isEnabled($this->catalog))
            ? CatalogSorting::prepareSortingItems($this->catalog, $requestedSort, $resolvedSort, $this->page['currentDir'])
            : [];

        // Expose simple sortItems for legacy/simple theme partials

        $simpleItems = [];
        // Build simpleItems from prepared sortingItems (service returns normalized labels)
        $prepared = $this->page['sortingItems'] ?? [];
        if (is_array($prepared)) {
            foreach ($prepared as $it) {
                if (isset($it['type']) && $it['type'] === 'group') {
                    $simpleItems[] = [
                        'label' => $it['label'] ?? ($it['key'] ?? ''),
                        'field' => $it['desc'] ?? ($it['asc'] ?? null),
                    ];
                } else {
                    $simpleItems[] = [
                        'label' => $it['label'] ?? ($it['code'] ?? ''),
                        'field' => $it['target'] ?? ($it['code'] ?? null),
                    ];
                }
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

        // Determine per-page: prefer catalog setting (from settings) when available, fall back to component property
        $effectivePerPage = (int) $this->property('perPage');
        if ($this->catalog && (int) $this->catalog->items_per_page > 0) {
            $effectivePerPage = (int) $this->catalog->items_per_page;
        }

        $paginator = $query->paginate(
            $effectivePerPage,
            null,
            ['*'],
            $this->property('pageParam')
        );

        // Preserve current query parameters (filters, sort etc.) except the page parameter
        $paginator->appends(request()->except($this->property('pageParam')));

        return $paginator;
    }


    protected function getRequestedSort(): ?string
    {
        $sort = request()->query('sort');
        return is_string($sort) ? $sort : null;
    }

    /**
     * Build a pagination sequence: numbers and 'ellipsis' tokens.
     *
     * @param \Illuminate\Contracts\Pagination\Paginator|mixed $paginator
     * @param int $leadingCount how many pages to always show at start
     * @param int $trailingCount how many pages to always show at end
     * @param int $adjacents how many pages to show around current
     * @return array sequence of ints and the string 'ellipsis'
     */
    protected function buildPaginationSequence($paginator, int $leadingCount = 3, int $trailingCount = 3, int $adjacents = 1, int $maxVisible = 6): array
    {
        $last = (int) $paginator->lastPage();
        $current = (int) $paginator->currentPage();

        if ($last <= 1) {
            return [1];
        }
        // If total pages is small, show all pages
        if ($last <= $maxVisible) {
            return range(1, $last);
        }

        $pages = [];

        // Reserve first and last
        $pages[] = 1;

        // Number of numeric slots for middle pages
        $middleSlots = max(1, $maxVisible - 2);

        // Compute middle window centered on current
        $half = (int) floor($middleSlots / 2);
        $start = $current - $half;
        $end = $start + $middleSlots - 1;

        // Clamp within [2, last-1]
        if ($start < 2) {
            $start = 2;
            $end = $start + $middleSlots - 1;
        }
        if ($end > $last - 1) {
            $end = $last - 1;
            $start = $end - $middleSlots + 1;
        }

        // Leading ellipsis
        if ($start > 2) {
            $pages[] = 'ellipsis';
        }

        // Middle pages
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        // Trailing ellipsis
        if ($end < $last - 1) {
            $pages[] = 'ellipsis';
        }

        // Last page
        $pages[] = $last;

        return $pages;
    }
}
