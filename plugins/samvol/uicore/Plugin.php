<?php namespace Samvol\UiCore;

use Illuminate\Support\Facades\App;
use Samvol\UiCore\Components\CoreBridge;
use Samvol\UiCore\Services\AjaxService;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function register(): void
    {
        App::singleton(AjaxService::class, function () {
            return new AjaxService();
        });
    }

    public function registerComponents(): array
    {
        return [
            CoreBridge::class => 'uiCore',
        ];
    }

    public function registerSettings(): array
    {
        return [];
    }
}
