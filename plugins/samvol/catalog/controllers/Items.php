<?php namespace Samvol\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Log;
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

        if ($catalog->fields->isEmpty()) {
            $this->vars['catalogDynamicFieldMessage'] = 'Для выбранного каталога пока не создано ни одного пользовательского поля.';
            $this->vars['catalogDynamicFieldMessageType'] = 'info';
            return;
        }

        $form->model->catalog_id = $catalogId;

        $fieldNames = [];

        $catalog->fields->sortBy('sort_order')->each(function (Field $field) use ($form, &$fieldNames) {
            $config = [
                'label'    => $field->name,
                'tab'      => 'Dynamic Fields',
                'span'     => $field->type === 'textarea' ? 'full' : 'auto',
                'required' => (bool) $field->is_required,
                'type'     => $this->resolveFieldWidget($field->type),
                'dependsOn' => ['catalog_id'],
            ];

            if ($field->type === 'select') {
                $config['options'] = $field->options ?: [];
                $config['emptyOption'] = '--';
            }

            if ($field->type === 'file') {
                $config['mode'] = 'file';
            }

            if ($field->type === 'slug') {
                $config['preset'] = $this->buildSlugPresetConfig($field);
            }

            $fieldName = 'data[' . $field->code . ']';
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
            case 'slug':
                return 'text';
            default:
                return 'text';
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
