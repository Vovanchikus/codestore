# Catalog plugin

Гибкий движок каталогов для Winter CMS 1.2+: один плагин обслуживает любое число независимых каталогов, динамических полей и категорий. Компоненты не содержат вёрстки и возвращают только данные — фронтенд полностью управляется вашей темой.

## Возможности

-   Много каталогов с разными схемами, функциями (comments, rating, views, files, moderation) и настройками.
-   Конструктор полей (`text`, `textarea`, `number`, `select`, `checkbox`, `file`) с JSON-опциями и валидацией.
-   Категории произвольной глубины, хранящие дополнительный JSON в `Category.data`.
-   Элементы со статусами `draft/published`, хранением значений в `Item.data`, привязкой к каталогу и категории.
-   Фронтенд-компоненты `catalogList`, `catalogItem`, `catalogForm`, отдающие данные, пагинацию, features и динамические поля.

## Установка

1. Скопируйте каталог `plugins/samvol/catalog` в проект Winter CMS.
2. Выполните миграции: `php artisan plugin:refresh Samvol.Catalog` (или `plugin:install` при первой установке).
3. Очистите кэш шаблонов при необходимости: `php artisan winter:up` и `php artisan cache:clear`.

## Бэкенд: каталоги

1. Откройте **Каталоги → Catalogs** и создайте запись.
2. Заполните вкладки:
    - **Основное** — `Name`, `Code` (уникальный slug для компонентов), описание и флаг `Active`.
    - **Поля** — конструктор динамических полей. `Code` становится ключом в `Item.data`. Для типа `select` используйте JSON `{ "s": "Small" }`. Значение `Sort order` управляет порядком рендера.
    - Кнопка «Добавить поля “Название материала” и “URL”» создаёт готовый заголовок и slug прямо в текущей сессии (даже до сохранения каталога). После нажатия обе записи сразу появляются в таблице полей, и их можно удалить или отредактировать как любые другие записи.
    - **Категории** — древовидная структура. `Slug` участвует в выборке (`categorySlug` у компонента списка). Поле `Data (JSON)` хранит произвольные метаданные.
    - **Функции** — чекбоксы `comments`, `rating`, `views`, `files`, `moderation`. Компоненты возвращают массив features, чтобы фронт принимал решения (например, показывать рейтинг).
    - **Настройки** — JSON-поле для любых параметров (сортировка, лимиты, интеграции).

## Бэкенд: элементы

-   Раздел **Каталоги → Items** показывает общую таблицу.
-   При создании записи сначала выберите каталог в поле `Catalog`. Пока каталог не выбран, блок динамических полей показывает подсказку.
-   После выбора каталогов AJAX подтягивает схему и рендерит виджеты (медиафайндер для `file`, чекбокс, селект и т.д.).
-   Значения сохраняются в `data` (JSON) и доступны фронтенду как массив. Обязательность проверяется согласно настройкам поля.
-   Поле `Status` переключает `draft/published` и влияет на выборку компонентов.

### Как работают динамические поля

-   Поле `catalog_id` является триггером: контроллер отслеживает изменение и перерисовывает секцию `_dynamic_fields_placeholder.htm`.
-   В момент редактирования существующего элемента схема загружается автоматически, даже если `catalog_id` не менялся.
-   Для селектов JSON должен быть валидным объектом: `{ "key": "Label" }`. Неверный JSON не сохранится — валидатор сообщит об ошибке.

## Фронтенд компоненты

Компоненты передают данные в Twig, а тема решает, как их отобразить.

### `catalogList`

Параметры: `catalogCode`, `categorySlug` (опционально), `page`, `perPage`, `status`. Возвращает `catalog`, `items` (LengthAwarePaginator), `fields`, `features`, `categories`.

```twig
{% component 'catalogList' catalogCode='products' categorySlug=paramSlug %}
{% if catalog %}
    <h2>{{ catalog.name }}</h2>
    {% for record in items %}
        <article>
            <h3>{{ record.display_name }}</h3>
            {% for field in fields %}
                {% set value = record.data[field.code] %}
                <p>{{ field.name }}: {{ value }}</p>
            {% endfor %}
        </article>
    {% else %}
        <p>Нет элементов.</p>
    {% endfor %}
    {% if items.lastPage() > 1 %}
        <nav>
            {% for pageNumber in 1..items.lastPage() %}
                <a href="{{ this.page.baseFileName|page({ ('page'): pageNumber }) }}">{{ pageNumber }}</a>
            {% endfor %}
        </nav>
    {% endif %}
{% endif %}
```

### `catalogItem`

Параметры: `catalogCode`, `itemId` или `itemSlug`. Возвращает `catalog`, `item`, `fields`, `features`.

```twig
{% component 'catalogItem' catalogCode='products' itemId=paramId %}
{% if item %}
    <h1>{{ item.display_name }}</h1>
    <dl>
        {% for field in fields %}
            <dt>{{ field.name }}</dt>
            <dd>{{ item.data[field.code] }}</dd>
        {% endfor %}
    </dl>
{% else %}
    <p>Элемент не найден.</p>
{% endif %}
```

### `catalogForm`

Используется для публичного создания/редактирования. Компонент отдаёт `catalog`, `item`, `fields`, `features`, `categories`. Сохранение через `onSave`.

```twig
{% component 'catalogForm' catalogCode='products' %}
{% if catalog %}
<form data-request="{{ __SELF__ }}::onSave">
    <input type="hidden" name="Item[catalog_id]" value="{{ catalog.id }}">
    <select name="Item[category_id]">
        <option value="">Без категории</option>
        {% for category in categories %}
            <option value="{{ category.id }}">{{ category.name }}</option>
        {% endfor %}
    </select>
    {% for field in fields %}
        <label>{{ field.name }}</label>
        <input name="Item[data][{{ field.code }}]" value="{{ item.data[field.code]|default('') }}">
    {% endfor %}
    <select name="Item[status]">
        {% for key, label in item.getStatusOptions() %}
            <option value="{{ key }}" {{ key == item.status ? 'selected' }}>{{ label }}</option>
        {% endfor %}
    </select>
    <button type="submit">Сохранить</button>
</form>
{% endif %}
```

AJAX-ответ содержит `item` и сообщения в стандартном формате Winter. Можно подключить Snowboard (`data-request`) либо UiCore (`samvol/uicore`) при необходимости.

## Дополнительные заметки

-   Все JSON-поля (`fields.options`, `categories.data`, `items.data`) проходят валидацию на `json`. Если вы редактируете их вручную, используйте валидный синтаксис.
-   Миграции `update_catalog_relations_nullable` делают `catalog_id`, `category_id` необязательными на ранних этапах, чтобы можно было создавать черновики с отложенным выбором каталога.
-   Если при сохранении элемента не видно динамических полей, убедитесь, что у записи выбран каталог и нет ошибок в ответах AJAX (Snowboard покажет уведомление).

## Миграции

-   `create_catalogs_table.php`
-   `create_catalog_fields_table.php`
-   `create_catalog_categories_table.php`
-   `create_catalog_items_table.php`
-   `update_catalog_relations_nullable.php`

Каждая миграция использует JSON-поля для гибкой структуры, поэтому требуется база с поддержкой JSON (MySQL 5.7+/MariaDB 10.2+, PostgreSQL 9.4+).
