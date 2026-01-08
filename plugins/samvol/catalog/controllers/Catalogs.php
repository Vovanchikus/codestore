<?php namespace Samvol\Catalog\Controllers;

use BackendMenu;
use Backend\Behaviors\FormController as FormControllerBehavior;
use Backend\Classes\Controller;
use Flash;
use Log;
use Samvol\Catalog\Models\Catalog;

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
    }

    public function onAddDefaultFields()
    {
        Log::debug('Catalogs: onAddDefaultFields called', [
            'post' => post(),
        ]);

        $model = $this->getCurrentFormModel();

        if (!$model) {
            Flash::warning('Не удалось определить каталог.');
            Log::warning('Catalogs: failed to resolve model in onAddDefaultFields');
            return;
        }

        $sessionKey = $this->getFormSessionKey();
        Log::debug('Catalogs: resolved session key for default fields', [
            'session_key' => $sessionKey,
            'model_exists' => $model->exists,
            'model_id' => $model->id,
        ]);

        $formContext = $model->exists
            ? FormControllerBehavior::CONTEXT_UPDATE
            : FormControllerBehavior::CONTEXT_CREATE;

        $this->initForm($model, $formContext);
        $this->initRelation($model, 'fields');

        $created = $model->createDefaultItemFields($sessionKey);

        if (empty($created)) {
            Flash::info('Стандартные поля уже добавлены.');
        } else {
            $titles = array_map(function ($field) {
                return $field->name;
            }, $created);
            Flash::success('Добавлены поля: ' . implode(', ', $titles));
        }

        return $this->relationRefresh('fields');
    }

    protected function getCurrentFormModel(): ?Catalog
    {
        $widget = $this->formGetWidget();
        if ($widget && $widget->model instanceof Catalog) {
            Log::debug('Catalogs: using model from form widget', [
                'model_exists' => $widget->model->exists,
                'model_id' => $widget->model->id,
            ]);
            return $widget->model;
        }

        $recordId = post('record_id') ?? post('id') ?? $this->params[0] ?? null;
        if ($recordId) {
            Log::debug('Catalogs: resolving model by record id', ['record_id' => $recordId]);
            return $this->formFindModelObject($recordId);
        }

        $model = $this->formCreateModelObject();
        $model->fill(post('Catalog', []));
        Log::debug('Catalogs: created new model instance for deferred fields', [
            'filled_attributes' => $model->getAttributes(),
        ]);
        return $model;
    }

    protected function getFormSessionKey(): ?string
    {
        $sessionKey = post('_session_key');
        if ($sessionKey) {
            return $sessionKey;
        }

        $widget = $this->formGetWidget();
        return $widget ? $widget->getSessionKey() : null;
    }
}
