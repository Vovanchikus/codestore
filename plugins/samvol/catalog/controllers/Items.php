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
            return;
        }

        $catalog = $form->model->catalog ?: Catalog::with('fields')->find($catalogId);
        if (!$catalog) {
            $this->vars['catalogDynamicFieldMessage'] = 'Каталог не найден или был удалён.';
            $this->vars['catalogDynamicFieldMessageType'] = 'warning';
            return;
        }

        $enabledFields = $catalog->fields->filter(function (Field $field) {
            return (bool) $field->is_enabled;
        });

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
