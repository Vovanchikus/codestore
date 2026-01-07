# UiCore Plugin

Инфраструктурный плагин для Winter CMS, который объединяет фронтенд (Snowboard, UI helpers) и серверный слой для единых AJAX-ответов.

## Установка

1. Скопировать папку `plugins/samvol/uicore` в проект Winter CMS.
2. Выполнить `php artisan winter:up`, чтобы применить миграции, если они появятся.
3. Добавить компонент `uiCore` в нужный layout или страницу:
   ```twig
   [uiCore]
   ==
   {% component 'uiCore' %}
   ```
   Компонент автоматически подключает CSS и JS UiCore.

## Backend API

### AjaxResponse
Используйте `Samvol\UiCore\Classes\AjaxResponse` или сервис ниже для единообразных ответов:
```php
return AjaxResponse::success(['id' => 10], 'Сохранено');
return AjaxResponse::error('Серверная ошибка');
return AjaxResponse::validationError(['name' => ['Обязательно']]);
```

### AjaxService

Централизованный пайплайн обработки бизнес-кода с try/catch, авторизацией и логами.
```php
use Samvol\UiCore\Services\AjaxService;

public function onCreate()
{
    return app(AjaxService::class)->handle(function () {
        // бизнес-логика
        return ['id' => $model->id];
    }, [
        'auth' => true, // требуется авторизация
        'permission' => 'samvol.uicore.access_core',
        'successMessage' => 'Сохранено',
        'errorMessage' => 'Что-то пошло не так',
    ]);
}
```

### UiEvents

Backend-события для расширения:
- `samvol.uicore.ajax.before`
- `samvol.uicore.ajax.after`
- `samvol.uicore.ajax.error`

```php
Event::listen(UiEvents::AJAX_AFTER, function ($data, $options) {
    // кастомная телеметрия
});
```

## Frontend API

UiCore публикует `window.UI` с модулями `toast`, `modal`, `loader`, `request`, `confirm`, `events`.

### Подключение Snowboard и UiCore в layout
```twig
{% snowboard all %}
{% styles %}
    <link rel="stylesheet" href="{{ 'plugins/samvol/uicore/assets/css/ui-core.css'|theme }}">
{% endstyles %}

{% scripts %}
    <script src="{{ 'plugins/samvol/uicore/assets/js/loader.js'|theme }}"></script>
    <script src="{{ 'plugins/samvol/uicore/assets/js/toast.js'|theme }}"></script>
    <script src="{{ 'plugins/samvol/uicore/assets/js/modal.js'|theme }}"></script>
    <script src="{{ 'plugins/samvol/uicore/assets/js/request.js'|theme }}"></script>
    <script src="{{ 'plugins/samvol/uicore/assets/js/ui-core.js'|theme }}"></script>
{% endscripts %}
```
Компонент `uiCore` делает это автоматически.

### UI.request
```javascript
UI.request('#form', 'MyPlugin\\Component::onSave', {
    data: new FormData(document.querySelector('#form')),
    success(response) {
        console.log(response.data);
    },
    error(error) {
        console.error(error);
    },
});
```
Loader включается автоматически, ошибки выводятся через toast.

### Toast
```javascript
UI.toast.success('Сохранено', { timeout: 5000, position: 'top-center' });
UI.toast.error('Ошибка', { position: 'bottom-right' });
UI.toast.info('Информация');
```
Доступные позиции: `top-right` (по умолчанию), `top-center`, `bottom-right`. Таймер при наведении ставится на паузу.

### Modal и Confirm
```javascript
UI.modal.open('<p>Содержимое</p>', {
    title: 'Заголовок',
    actions: [
        { label: 'Отмена', variant: 'ghost' },
        { label: 'OK', variant: 'primary', onClick: () => console.log('OK') },
    ],
});

UI.confirm('Удалить запись?', function (result) {
    if (result) {
        // пользователь подтвердил
    }
});
```

### Loader
```javascript
UI.loader.show();
// ... асинхронные операции ...
UI.loader.hide();
```
Обычно управляется автоматически через `UI.request`.

### Events
```javascript
UI.events.on('snowboard:ajaxError', (event) => {
    console.warn('Ошибка Snowboard', event.detail);
});
```

## Пример полной страницы (Home)
```twig
title = "Home"
url = "/"
":[uiCore]
==
{% component 'uiCore' %}

<button id="toast-demo">Toast</button>
<button id="modal-demo">Modal</button>
<button id="ping-demo">Ping</button>

{% put scripts %}
<script>
uiCoreReady(function (UI) {
    document.getElementById('toast-demo').addEventListener('click', function () {
        UI.toast.success('Успех!', { position: 'top-center' });
    });

    document.getElementById('modal-demo').addEventListener('click', function () {
        UI.modal.open('<p>UiCore modal</p>');
    });

    document.getElementById('ping-demo').addEventListener('click', function () {
        UI.request(this, 'uiCore::onPing');
    });
});
</script>
{% endput %}
```

## Требования
- Winter CMS 1.2+
- Snowboard assets (подключаются через `{% snowboard all %}`)
- Отсутствие jQuery (UiCore полностью на чистом JS)

## Расширение
- Добавляйте новые JS-модули в `assets/js` и регистрируйте через `window.SamvolUiCoreModules`.
- Переопределяйте стили через CSS-переменные в своей теме.
- Для кастомных Snowboard событий используйте `UI.events` или `UiEvents` на сервере.
