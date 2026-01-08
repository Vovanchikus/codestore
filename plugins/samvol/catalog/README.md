# Catalog plugin

This README documents the `Samvol.Catalog` plugin located at `plugins/samvol/catalog`.

Гибкий движок каталогов для Winter CMS 1.2+: один плагин обслуживает любое число независимых каталогов, динамических полей и категорий. Компоненты не содержат вёрстки и возвращают только данные — фронтенд полностью управляется вашей темой.

## Возможности

-   Много каталогов с разными схемами, функциями (comments, rating, views, files, moderation) и настройками.
-   Конструктор полей (`text`, `textarea`, `number`, `select`, `checkbox`, `file`) с JSON-опциями и валидацией.
-   Категории произвольной глубины, хранящие дополнительный JSON в `Category.data`.
-   Элементы со статусами `draft/published`, хранением значений в `Item.data`, привязкой к каталогу и категории.
-   Фронтенд-компоненты `catalogList`, `catalogItem`, `catalogForm`, `catalogsList`, отдающие данные, пагинацию, features и динамические поля.

## Установка

1. Скопируйте каталог `plugins/samvol/catalog` в проект Winter CMS.
2. Выполните миграции: `php artisan plugin:refresh Samvol.Catalog` (или `plugin:install` при первой установке).
3. Очистите кэш шаблонов при необходимости: `php artisan winter:up` и `php artisan cache:clear`.

## Бэкенд: каталоги

1. Откройте **Каталоги → Catalogs** и создайте запись.
2. Заполните вкладки:
    - **Основное** — `Name`, `Code` (уникальный slug для компонентов), описание и флаг `Active`.
    - **Поля** — конструктор динамических полей. `Code` становится ключом в `Item.data`, за исключением специальных файловых полей. При создании каталога вкладка сразу содержит готовый набор из 11 полей, который можно редактировать или дополнять:
        - `title` — Название материала (required)
        - `slug` — URL (required, slug от `title`)
        - `brief` — Краткое описание
        - `message` — Полный текст (richeditor, required)
        - `version` — Версия материала
        - `screenshot` — Скриншоты (attachMany, media)
        - `archive` — Файл-архив (attachOne)
        - `author_name` / `author_email`
        - `source` — ссылка на источник
        - `docpage_url` — ссылка на документацию
    - Каждый файл можно временно спрятать переключателем **Enabled** вместо удаления: выключенные поля исчезают из формы элементов, но остаются в таблице. `Required` управляет валидацией.
    - `Code` должен быть уникален внутри каталога — система не позволит добавить дубликат и автоматически нормализует значение (строчные символы + подчёркивания).
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
-   Сервисные поля `screenshot` и `archive` используют `attachMany`/`attachOne` загрузчики. Файлы попадают в `system_files`, остаются доступны до сохранения записи (через deferred binding) и не занимают место в `Item.data`.
-   Выключенные поля не попадают в форму элементов, но их данные остаются в базе (если поле позже включить, значения снова появятся в форме).

## Фронтенд компоненты

Компоненты передают данные в Twig, а тема решает, как их отобразить.

### Поля и данные, доступные в компонентах

`catalogList`

-   `{{ catalog.name }}`, `{{ catalog.code }}` — метаданные каталога
-   `{{ items }}` — пагинированная коллекция элементов (LengthAwarePaginator)
-   `{{ categories }}` — список категорий каталога
-   `{{ fields }}` — схема динамических полей (только включённые)
-   `{{ features }}` — массив признаков (comments/rating/views/files/moderation)

`catalogItem`

-   `{{ catalog }}` — текущий каталог
-   `{{ item }}` — текущий элемент
-   `{{ fields }}` — схема динамических полей
-   `{{ features }}` — признаки каталога

`catalogForm`

-   `{{ catalog }}` — каталог, для которого работает форма
-   `{{ item }}` — редактируемый элемент (или пустой для создания)
-   `{{ fields }}` — динамические поля каталога (включённые)
-   `{{ categories }}` — категории каталога
-   `{{ features }}` — признаки каталога

Стандартные поля элемента (доступны во всех компонентах, где есть `item` или `record`):

-   `{{ item.display_name }}` — человекочитаемый заголовок (берётся из `data.title` или `data.name`)
-   `{{ item.data.title }}` — название материала
-   `{{ item.data.slug }}` — URL-часть (slug)
-   `{{ item.data.brief }}` — краткое описание
-   `{{ item.data.message }}` — полный текст (HTML)
-   `{{ item.data.version }}` — версия материала
-   `{{ item.data.author_name }}` — имя автора
-   `{{ item.data.author_email }}` — email автора
-   `{{ item.data.source }}` — ссылка на источник
-   `{{ item.data.docpage_url }}` — ссылка на документацию

Файлы:

-   Галерея скриншотов (attachMany): `{% for image in record.screenshot %}<img src="{{ image.path }}">{% endfor %}`
-   Архив (attachOne): `{{ record.archive.path }}`

### Справочник Twig-кодов и когда их применять

Как выбрать `item` или `record`:

-   На странице детали (компонент `catalogItem`) доступна одна запись в переменной `item` → используйте `item.*`.
-   На странице списка (компонент `catalogList`) доступна пагинация `items`; внутри цикла `for record in items` текущая запись — `record` → используйте `record.*`.

Базовые данные элемента:

-   `{{ item.display_name }}` / `{{ record.display_name }}` — человекочитаемый заголовок (из `data.title`/`data.name`, fallback `Item #id`).
-   `{{ item.id }}` / `{{ record.id }}` — ID материала.
-   `{{ item.status }}` — статус (`draft`/`published`).
-   `{{ item.created_at }}`, `{{ item.updated_at }}`, `{{ item.published_at }}` — метки времени (форматируйте: `|date('d.m.Y H:i')`).
-   Категория: `{{ item.category.name }}`, `{{ item.category.id }}`, `{{ item.category.slug }}` (если привязана).

Стандартные поля (хранятся в `data`):

-   `{{ item.data.title }}` / `{{ record.data.title }}` — название материала.
-   `{{ item.data.slug }}` — slug (часть URL).
-   `{{ item.data.brief }}` — краткое описание.
-   `{{ item.data.message }}` — полный текст (HTML/richeditor).
-   `{{ item.data.version }}` — версия.
-   `{{ item.data.author_name }}` — имя автора.
-   `{{ item.data.author_email }}` — email автора.
-   `{{ item.data.source }}` — ссылка на источник.
-   `{{ item.data.docpage_url }}` — ссылка на документацию.

Файлы:

-   Скриншоты (attachMany):
    ```twig
    {% for image in record.screenshot %}
      <img src="{{ image.path }}" alt="{{ record.display_name }}">
    {% endfor %}
    ```
    Вместо `record` используйте `item` на странице детали. Для превью — `image.thumb(300,200,{ mode:'crop' })`.
-   Архив (attachOne): `{{ record.archive.path }}` или `{{ item.archive.path }}` — ссылка на файл.

Ссылки и навигация:

-   Ссылка на страницу элемента (если маршрут `/catalog/:catalogCode/:itemSlug`):
    `{{ 'catalog-item'|page({ catalogCode: catalog.code, itemSlug: record.data.slug }) }}`
-   Ссылка на категорию (если используется `categorySlug`):
    `{{ 'catalog'|page({ catalogCode: catalog.code, categorySlug: record.category.slug }) }}`

Что всегда приходит в компонентах:

-   `catalogsList`: `catalogs` (все активные каталоги)
-   `catalogList`: `catalog`, `items`, `categories`, `fields`, `features`.
-   `catalogItem`: `catalog`, `item`, `fields`, `features`.
-   `catalogForm`: `catalog`, `item` (или пустой), `fields`, `categories`, `features`.

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

### `catalogsList`

Параметры: отсутствуют. Возвращает `catalogs` — коллекцию всех активных каталогов (по scope `active`). Используйте в шапке или на любой странице, чтобы построить меню без PHP в Twig.

```twig
{% component 'catalogsList' %}
{% if catalogs|length %}
    <ul>
        {% for catalog in catalogs %}
            <li>
                <a href="{{ 'catalog/catalog'|page({ catalogCode: catalog.code }) }}">{{ catalog.name }}</a>
            </li>
        {% endfor %}
    </ul>
{% else %}
    <p>Каталоги отсутствуют.</p>
{% endif %}
```

## Дополнительные заметки

## Категории: иконки (файл или SVG)

Категория может иметь либо загруженную иконку, либо встроенный SVG-код. Если заполнить оба варианта, сохранение вернёт ошибку `Choose either an uploaded icon or SVG code, not both.`

-   В форме есть переключатель источника (balloon): загрузка файла или SVG-код. Показывается только активный вариант, он определяется по уже сохранённым данным.

### Пример Twig: список категорий с иконками

```twig
{# Берём активные категории с иконками #}
{% set categories = Samvol\Catalog\Models\Category.active().with('icon').get() %}

<ul class="catalog-categories">
    {% for category in categories %}
        <li class="catalog-category">
            {# Если есть SVG-код — рендерим его, иначе показываем загруженный файл #}
            {% if category.icon_svg %}
                <span class="category-icon" aria-hidden="true">{{ category.icon_svg|raw }}</span>
            {% elseif category.icon %}
                <img src="{{ category.icon.thumb(96, 96, { mode: 'crop' }) }}" alt="{{ category.name }}" loading="lazy">
            {% endif %}

            {# Название #}
            <h3>{{ category.name }}</h3>

            {# Описание (необязательно) #}
            {% if category.description %}
                <p>{{ category.description }}</p>
            {% endif %}
        </li>
    {% else %}
        <li>Категории не найдены.</li>
    {% endfor %}
</ul>
```

-   `{{ category.icon_svg|raw }}` — выводит сохранённый SVG-код без экранирования.
-   `{{ category.icon.thumb(96, 96, { mode: 'crop' }) }}` — превью загруженного изображения 96×96.

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
