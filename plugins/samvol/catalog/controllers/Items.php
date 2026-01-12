<?php namespace Samvol\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Models\Field;

class Items extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    protected $requiredPermissions = ['samvol.catalog.access_items'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samvol.Catalog', 'catalogs', 'items');
    }

    public function formExtendFields($form): void
    {
        if (!$form->model) {
            return;
        }

        $this->vars['catalogDynamicFieldMessage'] = null;
        $this->vars['catalogDynamicFieldMessageType'] = 'info';
        $this->vars['catalogDynamicFieldNames'] = [];

        $catalogId = $form->model->catalog_id;

        if (!$catalogId) {
            // Try to read from GET query parameters (e.g., when opening form via URL like ?catalog=123)
            $queryCatalog = (int) (request()->query('catalog') ?: request()->query('catalog_id') ?: 0);
            if ($queryCatalog) {
                $catalogId = $queryCatalog;
            }
        }

        if (!$catalogId) {
            // Try to extract catalog id from HTTP referer when opened from Catalogs->update page
            try {
                $referer = request()->headers->get('referer');
                if (is_string($referer) && preg_match('#/samvol/catalog/catalogs/update/(\d+)#', $referer, $m)) {
                    $catalogId = (int) $m[1];
                }
            } catch (\Throwable $_) {
                // ignore
            }
        }

        if (!$catalogId) {
            // Try to find catalog id in session keys (some backend widgets store selected id in session)
            try {
                $sess = request()->session()->all();
                foreach ($sess as $k => $v) {
                    if (is_scalar($v) && preg_match('/catalog/i', (string) $k) && is_numeric($v)) {
                        $catalogId = (int) $v;
                        \Log::info('Items::formExtendFields - found catalogId in session', ['key' => $k, 'value' => $catalogId]);
                        break;
                    }
                }
            } catch (\Throwable $_) {
                // ignore
            }
        }

        if (!$catalogId) {
            // Try to find catalog id in cookies
            try {
                $cookies = request()->cookies->all();
                foreach ($cookies as $k => $v) {
                    if (preg_match('/catalog/i', (string) $k) && is_numeric($v)) {
                        $catalogId = (int) $v;
                        \Log::info('Items::formExtendFields - found catalogId in cookie', ['key' => $k, 'value' => $catalogId]);
                        break;
                    }
                }
            } catch (\Throwable $_) {
                // ignore
            }
        }

        if (!$catalogId) {
            // If there is exactly one catalog in the system, assume it
            try {
                $catalogCount = \Samvol\Catalog\Models\Catalog::count();
                if ($catalogCount === 1) {
                    $only = \Samvol\Catalog\Models\Catalog::first();
                    if ($only) {
                        $catalogId = (int) $only->id;
                        \Log::info('Items::formExtendFields - using only-catalog fallback', ['catalogId' => $catalogId]);
                    }
                }
            } catch (\Throwable $_) {
                // ignore
            }
        }

        if (!$catalogId) {
            $catalogField = $form->getField('catalog_id');
            if ($catalogField) {
                $value = $catalogField->value;
                if (is_scalar($value) && $value !== '') {
                    $catalogId = (int) $value;
                }
            }
        }

        if (!$catalogId) {
            $catalogId = (int) post('Item.catalog_id', 0) ?: (int) post('catalog_id', 0);
        }

        if (!$catalogId) {
            $this->vars['catalogDynamicFieldMessage'] = 'Выберите каталог слева, чтобы загрузить связанные динамические поля.';
            $this->vars['catalogDynamicFieldMessageType'] = 'warning';
            try {
                \Log::info('Items::formExtendFields - catalogId not found', [
                    'model_catalog_id' => $form->model ? ($form->model->catalog_id ?? null) : null,
                    'field_value' => $catalogField ? ($catalogField->value ?? null) : null,
                    'post_Item_catalog_id' => post('Item.catalog_id', null),
                    'post_catalog_id' => post('catalog_id', null),
                    'request_all_keys' => array_keys(request()->all() ?: []),
                    'form_field_keys' => array_keys($form->getFields() ?: []),
                    'catalogField_exists' => (bool) ($catalogField !== null),
                    'catalogField_debug' => $catalogField ? (is_object($catalogField) ? get_class($catalogField) : gettype($catalogField)) : null,
                ]);
            } catch (\Throwable $_) {
                // ignore logging errors
            }
            return;
        }

        try {
            $catalog = $form->model->catalog ?: Catalog::with('fields')->find($catalogId);
            if (!$catalog) {
                $this->vars['catalogDynamicFieldMessage'] = 'Каталог не найден или был удалён.';
                $this->vars['catalogDynamicFieldMessageType'] = 'warning';
                return;
            }

            $enabledFields = $catalog->fields->filter(function (Field $field) {
                return (bool) $field->is_enabled;
            });
        } catch (\Throwable $e) {
            // Log and show a diagnostic message in admin to help debugging
            try { \Log::error('Catalog dynamic fields load error: ' . $e->getMessage(), ['exception' => $e]); } catch (\Throwable $_) {}
            $this->vars['catalogDynamicFieldMessage'] = 'Ошибка при загрузке динамических полей: ' . $e->getMessage();
            $this->vars['catalogDynamicFieldMessageType'] = 'danger';
            return;
        }

        if ($enabledFields->isEmpty()) {
            $this->vars['catalogDynamicFieldMessage'] = 'Для выбранного каталога пока не включено ни одного пользовательского поля.';
            $this->vars['catalogDynamicFieldMessageType'] = 'info';
            return;
        }

        $form->model->catalog_id = $catalogId;

        $fieldNames = [];

        // Очистить ранее добавленные динамические поля, чтобы не было дублей при повторном рендере
        foreach ($form->getFields() as $name => $fieldObj) {
            if ($fieldObj->tab === 'Dynamic Fields') {
                $form->removeField($name);
            }
        }

        $enabledFields->sortBy('sort_order')->each(function (Field $field) use ($form, &$fieldNames) {
            $config = [
                'label'     => $field->name,
                'tab'       => 'Dynamic Fields',
                'span'      => $this->resolveFieldSpan($field->type),
                'required'  => (bool) $field->is_required,
                'type'      => $this->resolveFieldWidget($field->type),
                'dependsOn' => ['catalog_id'],
            ];

            $options = is_array($field->options) ? $field->options : [];

            if ($field->type === 'select') {
                $config['options'] = $options;
                $config['emptyOption'] = '--';
            }

            if ($field->type === 'file') {
                $config['mode'] = 'file';
            }

            if ($field->type === 'slug') {
                $config['preset'] = $this->buildSlugPresetConfig($field);
            }

            if (in_array($field->type, ['file_single', 'file_multi'], true)) {
                $this->applyFileUploadConfig($config, $field, $options);
            }

            $fieldName = $this->resolveFieldInputName($field);
            $form->removeField($fieldName);
            $form->addTabFields([
                $fieldName => $config,
            ]);
            $fieldNames[] = $fieldName;
        });
        $this->vars['catalogDynamicFieldNames'] = $fieldNames;
    }

    /**
     * AJAX handler to load dynamic fields fragment when catalog is changed in form
     */
    public function onLoadDynamicFields()
    {
        $catalogId = 0;

        // try multiple ways to extract catalog id from POST/request
        try {
            $all = request()->all() ?: [];
        } catch (\Throwable $_) {
            $all = [];
        }

        // direct keys
        if (!empty($all['catalog_id'])) {
            $catalogId = (int) $all['catalog_id'];
        }

        // nested Item[...] style from form widgets
        if (!$catalogId && !empty($all['Item'])) {
            $item = $all['Item'];
            if (is_array($item) && isset($item['catalog_id']) && $item['catalog_id'] !== '') {
                $catalogId = (int) $item['catalog_id'];
            }
        }

        // fallback to top-level 'Item[catalog_id]' (jQuery sends as this key sometimes)
        if (!$catalogId && isset($all['Item[catalog_id]'])) {
            $catalogId = (int) $all['Item[catalog_id]'];
        }

        // lastly try other common keys
        if (!$catalogId && !empty($all['catalog'])) {
            $catalogId = (int) $all['catalog'];
        }

        // session fallback
        if (!$catalogId && request()->session()->has('samvol_selected_catalog')) {
            $catalogId = (int) request()->session()->get('samvol_selected_catalog');
        }

        try {
            \Log::info('Items::onLoadDynamicFields - request data', ['all' => $all, 'catalogId' => $catalogId]);
        } catch (\Throwable $_) {}

        // Ensure form widget exists; try to initialize one if missing (using Item model)
        $form = $this->formGetWidget();
        if (!$form) {
            try {
                $modelClass = \Samvol\Catalog\Models\Item::class;
                $model = new $modelClass();
                $this->initForm($model);
                $form = $this->formGetWidget();
                $this->vars['formWidget'] = $form;
            } catch (\Throwable $_) {
                return ['html' => '<p class="flash-message static warning">Форма не инициализирована.</p>'];
            }
        }

        // Populate form values from request so form fields have correct values
        try {
            $postItem = $all['Item'] ?? null;
            if ($postItem && is_array($postItem)) {
                $form->setFormValues($postItem);
            } else {
                // set minimal data so catalog_id is available to the form
                $form->setFormValues(['catalog_id' => $catalogId]);
            }

            // Ensure model has the catalog id as well
            if ($form->model) {
                $form->model->catalog_id = $catalogId;
            }
        } catch (\Throwable $_) {
            // continue even if setting values failed
        }

        // Reuse existing logic to populate dynamic fields
        $this->formExtendFields($form);

        try {
            $cf = $form->getField('catalog_id');
            \Log::info('Items::onLoadDynamicFields - after setFormValues catalogField value', ['value' => $cf ? ($cf->value ?? null) : null]);
        } catch (\Throwable $_) {}

        // Render inner partial and return HTML. Use direct file path lookup in controller folder
        $controllerDir = __DIR__ . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR;
        $candidates = [
            $controllerDir . 'dynamic_fields_inner.htm',
            $controllerDir . '_dynamic_fields_inner.htm',
        ];

        $html = '';
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                try {
                    $html = $this->makeFileContents($candidate);
                } catch (\Throwable $_) {
                    $html = '';
                }
                break;
            }
        }

        if ($html === '') {
            // fallback to makePartial which will throw a clear exception if not found
            $html = $this->makePartial('items/dynamic_fields_inner', [], false) ?: '<p class="flash-message static warning">Не удалось загрузить динамические поля.</p>';
        }

        return ['html' => $html];
    }

    protected function resolveFieldWidget(string $type): string
    {
        switch ($type) {
            case 'textarea':
                return 'textarea';
            case 'number':
                return 'number';
            case 'select':
                return 'dropdown';
            case 'checkbox':
                return 'checkbox';
            case 'file':
                return 'mediafinder';
            case 'file_single':
            case 'file_multi':
                return 'fileupload';
            case 'richeditor':
                return 'richeditor';
            case 'slug':
                return 'text';
            default:
                return 'text';
        }
    }

    protected function resolveFieldInputName(Field $field): string
    {
        if (in_array($field->type, ['file_single', 'file_multi'], true)) {
            return $field->code;
        }

        return 'data[' . $field->code . ']';
    }

    protected function resolveFieldSpan(string $type): string
    {
        return in_array($type, ['textarea', 'richeditor', 'file', 'file_single', 'file_multi'], true)
            ? 'full'
            : 'auto';
    }

    protected function applyFileUploadConfig(array &$config, Field $field, array $options): void
    {
        $config['type'] = 'fileupload';
        $config['span'] = 'full';
        $config['mode'] = $options['mode'] ?? ($field->type === 'file_multi' ? 'image' : 'file');
        $config['useCaption'] = $field->type === 'file_multi';

        if (!empty($options['file_types'])) {
            $config['fileTypes'] = $options['file_types'];
        }

        if ($field->type === 'file_multi' && !empty($options['max_files'])) {
            $config['maxFiles'] = (int) $options['max_files'];
        }
    }

    protected function buildSlugPresetConfig(Field $field): array
    {
        $options = $field->options;

        if (is_string($options)) {
            $decoded = json_decode($options, true);
            $options = is_array($decoded) ? $decoded : [];
        }

        $sourceCode = 'title';
        if (is_array($options) && !empty($options['slug_source']) && is_string($options['slug_source'])) {
            $sourceCode = $options['slug_source'];
        }

        $sourceFieldName = strpos($sourceCode, 'data[') === 0
            ? $sourceCode
            : 'data[' . $sourceCode . ']';

        return [
            'field' => $sourceFieldName,
            'type'  => 'slug',
        ];
    }
}
