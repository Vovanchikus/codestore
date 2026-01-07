<?php namespace Samvol\UiCore\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Samvol\UiCore\Services\AjaxService;

class CoreBridge extends ComponentBase
{
    public function componentDetails(): array
    {
        return [
            'name' => 'UI Core Bridge',
            'description' => 'Infrastructure-only component that provides baseline AJAX handlers.',
        ];
    }

    protected function service(): AjaxService
    {
        return app(AjaxService::class);
    }

    public function onPing(): array
    {
        return $this->service()->handle(function () {
            return [
                'timestamp' => Carbon::now()->toIso8601String(),
                'environment' => app()->environment(),
            ];
        }, [
            'successMessage' => 'Pong received',
        ]);
    }

    public function onTest(): array
    {
        return $this->service()->handle(function () {
            return [
                'status' => 'ok',
                'meta' => [
                    'executed_at' => Carbon::now()->toIso8601String(),
                ],
            ];
        }, [
            'permission' => 'samvol.uicore.access_core',
            'successMessage' => 'Diagnostic handler executed',
        ]);
    }

    public function onRun()
    {
        // Подключаем CSS
        $this->addCss('/plugins/samvol/uicore/assets/css/ui-core.css');

        // Подключаем JS
        $this->addJs('/plugins/samvol/uicore/assets/js/request.js');
        $this->addJs('/plugins/samvol/uicore/assets/js/toast.js');
        $this->addJs('/plugins/samvol/uicore/assets/js/modal.js');
        $this->addJs('/plugins/samvol/uicore/assets/js/loader.js');
        $this->addJs('/plugins/samvol/uicore/assets/js/ui-core.js');
    }
}
