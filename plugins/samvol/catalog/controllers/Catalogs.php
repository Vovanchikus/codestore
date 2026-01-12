<?php namespace Samvol\Catalog\Controllers;

use ApplicationException;
use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Samvol\Catalog\Models\Catalog;
use Samvol\Catalog\Models\Field;

class Catalogs extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    protected $requiredPermissions = ['samvol.catalog.access_catalogs'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samvol.Catalog', 'catalogs', 'catalogs');
        $this->addJs('/plugins/samvol/catalog/assets/js/catalog-tabs-guard.js');
    }

    public function relationExtendViewWidget($widget, $field, $model)
    {
        if ($field !== 'fields') {
            return;
        }

        $widget->bindEvent('list.injectRowClass', function ($record) {
            return $record->is_enabled ? null : 'text-muted';
        });
    }

    public function onRelationButtonDisableFields()
    {
        return $this->toggleFieldsEnabled(false);
    }

    public function onRelationButtonEnableFields()
    {
        return $this->toggleFieldsEnabled(true);
    }

    protected function toggleFieldsEnabled(bool $enabled)
    {
        $checkedIds = array_filter((array) post('checked'));

        if (empty($checkedIds)) {
            throw new ApplicationException('Выберите хотя бы одно поле.');
        }

        $catalog = $this->model instanceof Catalog ? $this->model : null;

        if (!$catalog) {
            $formWidget = $this->formGetWidget();
            $catalog = $formWidget ? $formWidget->model : null;
        }

        if (!$catalog) {
            $catalogId = (int) (Field::whereIn('id', $checkedIds)->value('catalog_id') ?? 0);
            $catalog = $catalogId ? Catalog::find($catalogId) : null;
        }

        if (!$catalog instanceof Catalog || !$catalog->id) {
            throw new ApplicationException('Не удалось определить каталог для переключения полей. Сохраните каталог.');
        }

        $fields = Field::whereIn('id', $checkedIds)->get();
        if ($fields->isEmpty()) {
            throw new ApplicationException('Не удалось найти выбранные поля.');
        }

        $this->initRelation($catalog, 'fields');

        Field::where('catalog_id', $catalog->id)
            ->whereIn('id', $checkedIds)
            ->update(['is_enabled' => $enabled]);

        Flash::success($enabled ? 'Поля включены' : 'Поля отключены');

        return $this->relationRefresh('fields');
    }

    public function onRefreshTrackDropdowns()
    {
        $formWidget = $this->formGetWidget();
        $model = $formWidget ? $formWidget->model : null;

        if ($model instanceof Catalog) {
            // propagate session key for unsaved models to allow deferred fields
            if (!$model->exists && $formWidget->getSessionKey()) {
                $model->sessionKey = $formWidget->getSessionKey();
            }

            return [
                'options' => [
                    'track' => $model->getTrackUpdatesFieldOptions(),
                    'log'   => $model->getTrackUpdatesLogFieldOptions(),
                ],
            ];
        }

        return ['options' => ['track' => [], 'log' => []]];
    }

    /**
     * AJAX handler: set selected catalog id into session for other controllers to read
     */
    public function onSetSelectedCatalog()
    {
        $id = (int) post('id');
        if ($id > 0) {
            request()->session()->put('samvol_selected_catalog', $id);
            return ['status' => 'ok', 'catalog_id' => $id];
        }

        request()->session()->forget('samvol_selected_catalog');
        return ['status' => 'cleared'];
    }

}
