# Catalog plugin (Samvol.Catalog)

Гибкий движок каталогов для Winter CMS 1.2+: один плагин обслуживает любое число независимых каталогов, динамических полей и категорий. Компоненты не содержат вёрстки и возвращают только данные — фронтенд полностью управляется вашей темой.

Плагин находится в каталоге `plugins/samvol/catalog`.

---

## Оглавление

-   [Обзор](#обзор)
-   [Установка](#установка)
-   [Бэкенд](#бэкенд)
    -   [Каталоги](#каталоги)
    -   [Элементы](#элементы)
    -   [Динамические поля](#динамические-поля)
    -   [Категории](#категории)
    -   [Функции каталога](#функции-каталога)
-   [Фронтенд-компоненты](#фронтенд-компоненты)
    -   [`catalogList`](#cataloglist)
    -   [`catalogItem`](#catalogitem)
    -   [`catalogForm`](#catalogform)
    -   [`catalogsList`](#catalogslist)
-   [Сортировка](#сортировка)
-   [JS/UX](#jsux)
-   [Миграции](#миграции)
-   [Примечания](#примечания)

---

## Обзор

Плагин **Samvol.Catalog** решает задачу универсального каталога контента:

-   Несколько независимых каталогов с разными схемами полей и настройками.
-   Динамические поля произвольной структуры, настраиваемые через бэкенд.
-   Иерархические категории с дополнительными данными.
-   Элементы с JSON-данными, файлами, статусами публикации.
-   Функции каталога: `views`, `files`, `rating`, `comments`, `moderation`.
-   Фронтенд-компоненты:
    -   `catalogList` — список элементов каталога;
    -   `catalogItem` — детальная страница элемента;
    -   `catalogForm` — публичная форма создания/редактирования;
    -   `catalogsList` — список всех активных каталогов.

Текущие поддерживаемые сортировки: **дата**, **название**, **загрузки**, **просмотры**.
Функции `comments` и `rating` находятся в разработке и будут вынесены в отдельные плагины.

---

## Установка

1. Скопируйте каталог `plugins/samvol/catalog` в проект Winter CMS.
2. Выполните миграции плагина:

    ```bash
    php artisan plugin:refresh Samvol.Catalog
    # или при первой установке:
    php artisan plugin:install Samvol.Catalog
    ```

3. При необходимости обновите и очистите кэш:

    ```bash
    php artisan winter:up
    php artisan cache:clear
    ```

База данных должна поддерживать JSON-поля (MySQL 5.7+/MariaDB 10.2+, PostgreSQL 9.4+).

---

## Бэкенд

### Каталоги

Раздел: **Каталоги → Catalogs**

Каталог — это «контейнер» для элементов с собственной схемой полей, категориями и настройками.

Основные вкладки каталога:

-   **Основное**
-   **Поля**
-   **Категории**
-   **Функции**
-   **Настройки**

#### Основное

-   `Name` — человекочитаемое название каталога.
-   `Code` — уникальный slug, который используется во фронтенд-компонентах (`catalogCode`).
-   `Active` — флаг активности каталога (неактивные можно скрыть с фронтенда).

#### Поля каталога (динамическая схема)

Вкладка **Fields** — конструктор динамических полей. Каждое поле:

-   имеет уникальный `Code` (ключ в `Item.data`);
-   имеет тип (text, textarea, number, select, checkbox, file и т.д.);
-   может быть помечено как `Enabled` (показывать в форме элементов) и `Required` (валидация при сохранении).

При создании нового каталога по умолчанию добавляется набор из 11 полей:

| Код поля       | Название (RU)          | Тип        | Обязательное | Описание                                            |
| -------------- | ---------------------- | ---------- | ------------ | --------------------------------------------------- |
| `title`        | Название материала     | text       | да           | Заголовок материала, используется в `display_name`. |
| `slug`         | URL                    | text/slug  | да           | URL-часть (slug от `title`).                        |
| `brief`        | Краткое описание       | textarea   | нет          | Краткий текст-анонс.                                |
| `message`      | Полный текст           | richeditor | да           | Основное содержимое (HTML).                         |
| `version`      | Версия материала       | text       | нет          | Версия/номер релиза.                                |
| `screenshot`   | Скриншоты              | file       | нет          | `attachMany`, хранит изображения.                   |
| `archive`      | Файл-архив             | file       | нет          | `attachOne`, хранит единичный файл.                 |
| `author_name`  | Имя автора             | text       | нет          | Отображаемое имя автора.                            |
| `author_email` | Email автора           | text       | нет          | Email для связи.                                    |
| `source`       | Ссылка на источник     | text       | нет          | Внешний источник/репозиторий.                       |
| `docpage_url`  | Ссылка на документацию | text       | нет          | Внешняя страница документации.                      |

Особенности:

-   Переключатель **Enabled** скрывает поле из форм, но данные в базе остаются.
-   Флаг **Required** включает валидацию при сохранении элемента.
-   `Code` должен быть уникален в рамках одного каталога — система нормализует код (строчные символы + подчёркивания) и не позволяет дублировать.

---

### Элементы

Раздел: **Каталоги → Items**

Элемент — запись внутри конкретного каталога, привязанная (опционально) к категории и содержащая данные в JSON.

Основное:

-   Список элементов — общая таблица по всем каталогам.
-   При создании:
    -   сначала выберите каталог в поле `Catalog`;
    -   после выбора каталогов AJAX подтянет схему полей и отрисует динамические виджеты.
-   Данные полей сохраняются в `Item.data` (JSON) и отдаются во фронтенд через компоненты.
-   Поле `Status` управляет публикацией (`draft` / `published`) и участвует в фильтрации выборки.

Стандартные поля элемента, доступные во фронтенде:

| Поле                | Где хранится  | Обязательное | Описание                                                                                   |
| ------------------- | ------------- | ------------ | ------------------------------------------------------------------------------------------ |
| `display_name`      | вычисляемое   | да\*         | Человекочитаемый заголовок (берётся из `data.title` или `data.name`, fallback `Item #id`). |
| `data.title`        | JSON (`data`) | да           | Название материала.                                                                        |
| `data.slug`         | JSON (`data`) | да           | URL-часть (slug).                                                                          |
| `data.brief`        | JSON (`data`) | нет          | Краткое описание.                                                                          |
| `data.message`      | JSON (`data`) | да           | Полный текст (HTML/richeditor).                                                            |
| `data.version`      | JSON (`data`) | нет          | Версия материала.                                                                          |
| `data.author_name`  | JSON (`data`) | нет          | Имя автора.                                                                                |
| `data.author_email` | JSON (`data`) | нет          | Email автора.                                                                              |
| `data.source`       | JSON (`data`) | нет          | Ссылка на источник.                                                                        |
| `data.docpage_url`  | JSON (`data`) | нет          | Ссылка на документацию.                                                                    |
| `screenshot`        | relation      | нет          | Галерея изображений (attachMany).                                                          |
| `archive`           | relation      | нет          | Архивный файл (attachOne).                                                                 |

\* `display_name` всегда доступен, но его корректность зависит от заполнения `data.title`/`data.name`.

---

### Динамические поля

Динамические поля — это схема, задаваемая на уровне каталога и применяемая к элементам. Ключевые моменты:

-   Поле `catalog_id` в элементе является триггером — при его изменении контроллер обновляет секцию `_dynamic_fields_placeholder.htm`.
-   При редактировании существующего элемента схема загружается автоматически, даже если `catalog_id` не менялся.
-   Значения динамических полей сохраняются в `Item.data` (JSON).

Типы полей и их особенности:

| Тип        | Описание                          | Хранение             |
| ---------- | --------------------------------- | -------------------- |
| `text`     | Однострочный текст                | `Item.data[code]`    |
| `textarea` | Многострочный текст               | `Item.data[code]`    |
| `number`   | Числовое значение                 | `Item.data[code]`    |
| `select`   | Выпадающий список (ключ → метка)  | `Item.data[code]`    |
| `checkbox` | Логическое значение (on/off)      | `Item.data[code]`    |
| `file`     | Файл/медиа (attachOne/attachMany) | через `system_files` |

**JSON-опции для select:**

-   Опции задаются в виде JSON-объекта:
    `{"key": "Label", "another": "Another label"}`
-   Невалидный JSON не сохранится — валидатор сообщит об ошибке.

---

### Категории

Категории настраиваются во вкладке **Категории** каталога:

-   Иерархическая древовидная структура.
-   Поле `Slug` участвует в выборке (используется как `categorySlug` во фронтенде).
-   Поле `Data (JSON)` позволяет хранить произвольные метаданные категории.

**JSON в категориях:**

-   `Category.data` — JSON-объект для произвольных настроек.
-   Поле проходит валидацию на корректность JSON.

#### Иконки категорий (файл или SVG)

Категория может иметь:

-   либо загруженную иконку (изображение),
-   либо встроенный SVG-код.

Если заполнить оба варианта, сохранение вернёт ошибку:

```text
Choose either an uploaded icon or SVG code, not both.
```

Особенности формы:

-   В форме есть переключатель источника (balloon): файл или SVG-код.
-   Отображается только активный вариант, который определяется по сохранённым данным.

Пример вывода категорий с иконками (Twig):

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
                <img src="{{ category.icon.thumb(96, 96, { mode: 'crop' }) }}"
                     alt="{{ category.name }}"
                     loading="lazy">
            {% endif %}

            <h3>{{ category.name }}</h3>

            {% if category.description %}
                <p>{{ category.description }}</p>
            {% endif %}
        </li>
    {% else %}
        <li>Категории не найдены.</li>
    {% endfor %}
</ul>
```

---

### Функции каталога

Вкладка **Функции** управляет дополнительными возможностями каталога. Компоненты возвращают массив `features`, чтобы фронтенд мог принимать решения (например, показывать рейтинг или счётчик просмотров).

Поддерживаемые функции:

| Функция     | Код          | Включено по умолчанию | Статус            | Назначение                                        |
| ----------- | ------------ | --------------------- | ----------------- | ------------------------------------------------- |
| Просмотры   | `views`      | да                    | стабильно         | Подсчёт просмотров элементов.                     |
| Файлы       | `files`      | да                    | стабильно         | Работа с файлами (скачивание, статистика и т.п.). |
| Комментарии | `comments`   | нет                   | в разработке      | Интеграция с системой комментариев.               |
| Рейтинг     | `rating`     | нет                   | в разработке      | Оценки и рейтинги материалов.                     |
| Модерация   | `moderation` | нет                   | стабильно / basic | Поток модерации контента (черновики, публикация). |

В текущей версии основная реализация сосредоточена вокруг `views` и `files`, которые включены по умолчанию.

---

## Фронтенд-компоненты

Компоненты плагина не содержат вёрстки — они только подготавливают данные для Twig. Разметка и UX полностью контролируются вашей темой.

Общие сущности, которые часто возвращают компоненты:

-   `catalog` — текущий каталог.
-   `item` / `record` — элемент каталога.
-   `items` — пагинированная коллекция элементов (`LengthAwarePaginator`).
-   `fields` — схема динамических полей (включённые).
-   `categories` — список категорий каталога.
-   `features` — массив включённых функций (`views`, `files`, `rating`, `comments`, `moderation`).

---

### `catalogList`

Список элементов каталога с пагинацией и категориями.

**Параметры компонента:**

-   `catalogCode` — **обязательный**, код каталога.
-   `categorySlug` — опционально, slug категории.
-   `page` — номер страницы (поддерживается стандартная пагинация Winter).
-   `perPage` — число элементов на странице.
-   `status` — фильтрация по статусу (`published` / `draft` и т.п.).

**Возвращаемые данные в Twig:**

-   `catalog` — объект каталога.
-   `items` — `LengthAwarePaginator` с элементами.
-   `categories` — список категорий каталога.
-   `fields` — схема динамических полей.
-   `features` — включённые функции каталога.
-   `availableSorts` — доступные сортировки (код => метка).
-   `currentSort` — текущий выбранный код (из запроса `?sort=` или сортировки по умолчанию).

Пример использования:

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
        <nav class="pagination">
            {% for pageNumber in 1..items.lastPage() %}
                <a href="{{ this.page.baseFileName|page({ ('page'): pageNumber }) }}">
                    {{ pageNumber }}
                </a>
            {% endfor %}
        </nav>
    {% endif %}
{% endif %}
```

---

### `catalogItem`

Детальная страница одного элемента каталога.

**Параметры компонента:**

-   `catalogCode` — **обязательный**, код каталога.
-   `itemId` — ID элемента (взаимоисключимо с `itemSlug`).
-   `itemSlug` — slug элемента (взаимоисключимо с `itemId`).

**Возвращаемые данные:**

-   `catalog` — текущий каталог.
-   `item` — элемент.
-   `fields` — схема динамических полей.
-   `features` — функции каталога.

**Пример скачивания файла с правильным именем:**

```twig
{% if item.archive %}
    <a href="{{ item.archive.path }}"
       class="btn btn-link"
       download="{{ item.archive.file_name }}"
       data-request="catalogItem::onDownload"
       data-request-success="if (data && data.link) { window.location = data.link; } else { window.location = this.href; }"
       data-request-error="window.location = this.href">
       Скачать файл
    </a>
{% endif %}
```

-   `download` задаёт имя файла для браузера — берём `item.archive.file_name`.
-   `data-request` вызывает `catalogItem::onDownload`, который считает загрузку и может вернуть прямую ссылку.
-   При ошибке или отсутствии JS ссылка остаётся рабочей.

**Пример вывода истории обновлений (partial):**

```twig
{# замените log_field на ваш код #}
{% partial 'catalog/update_history' history=item.data['update_log'] %}
```

Партиал `partials/catalog/update_history.htm` выводит дату и текст каждого изменения.

Пример:

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

---

### `catalogForm`

Публичная форма создания/редактирования элементов.

**Назначение:**

-   Позволяет пользователям создавать или править элементы конкретного каталога.
-   Работает через AJAX-обработчик `onSave`.

**Возвращаемые данные:**

-   `catalog` — каталог, для которого отображается форма.
-   `item` — редактируемый элемент (или пустой объект для создания).
-   `fields` — динамические поля каталога.
-   `categories` — список категорий.
-   `features` — функции каталога.

Пример Twig с использованием Snowboard (`data-request`):

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
        <input name="Item[data][{{ field.code }}]"
               value="{{ item.data[field.code]|default('') }}">
    {% endfor %}

    <select name="Item[status]">
        {% for key, label in item.getStatusOptions() %}
            <option value="{{ key }}" {{ key == item.status ? 'selected' }}>
                {{ label }}
            </option>
        {% endfor %}
    </select>

    <button type="submit">Сохранить</button>
</form>
{% endif %}
```

AJAX-ответ содержит `item` и стандартные сообщения Winter CMS (success/error).

---

### `catalogsList`

Список всех активных каталогов. Удобен для построения навигационного меню.

**Параметры:** отсутствуют.
**Возвращаемые данные:**

-   `catalogs` — коллекция всех активных каталогов (scope `active`).

Пример:

```twig
{% component 'catalogsList' %}

{% if catalogs|length %}
    <ul class="catalogs-menu">
        {% for catalog in catalogs %}
            <li>
                <a href="{{ 'catalog/catalog'|page({ catalogCode: catalog.code }) }}">
                    {{ catalog.name }}
                </a>
            </li>
        {% endfor %}
    </ul>
{% else %}
    <p>Каталоги отсутствуют.</p>
{% endif %}
```

---

## Сортировка

Плагин поддерживает конфигурируемую сортировку списков элементов через компонент `catalogList` и сервис `services/CatalogSorting`.

### Коды сортировки (code → объяснение)

-   `date_desc` — по дате публикации, новые выше.
-   `date_asc` — по дате публикации, старые выше.
-   `title_asc` — по названию (JSON `data.title`) A–Z.
-   `title_desc` — по названию (JSON `data.title`) Z–A.
-   `downloads_desc` — по количеству загрузок, популярные выше.
-   `views_desc` — по количеству просмотров, популярные выше.

### Настройка сортировки в бэкенде

Во вкладке **Настройки** каталога:

-   включите сортировку;
-   задайте:
    -   **Сортировка по умолчанию** (код);
    -   **Разрешённые сортировки** (список кодов и их меток).

Сервис `CatalogSorting`:

-   валидирует коды сортировок и их метки;
-   хранит список разрешённых вариантов;
-   применяет сортировку к запросу элементов.

### Отслеживание обновлений

-   Включается в каталоге: **Настройки → Отслеживание обновлений → Включить**.
-   Поле для отслеживания (`track_updates_field`) — стандартное (`updated_at`, `published_at`, `version`) или любое динамическое.
-   Поле для истории (`track_updates_log_field`) — код в `data`, который вы задаёте сами. Его можно выбрать среди существующих полей на вкладке **Поля** или создать через кнопку «Создать поле» рядом; код вводится вручную в модалке при создании.
-   При изменении отслеживаемого поля в `Item::beforeSave` добавляется запись `{ date: 'YYYY-MM-DD HH:MM:SS', text: 'Изменено поле: ...' }` в лог и материал автоматически поднимается (обновляется `published_at`).
-   Если отслеживание выключено и чекбокс «Поднять материал» не выбран — элемент остаётся на месте.
-   В форме элемента есть чекбокс «Поднять материал» — он сразу обновляет `published_at` и добавляет запись с `manual: true` в историю даже без изменения поля.
-   Формат `data._update_history`: массив объектов вида `{ ts: 'YYYY-MM-DD HH:MM:SS', field: 'code'|null, manual: true|false }`. Старые строковые значения тоже поддерживаются.

### Использование во фронтенде

Компонент `catalogList` передаёт в Twig:

-   `availableSorts` — словарь `код → метка`;
-   `currentSort` — текущий выбранный код (из запроса `?sort=` или сортировки по умолчанию).

Рекомендуется реализовать обычную HTML-форму с методом GET.

#### Пример: главная каталога (`catalog-home`)

```twig
[catalogList]
catalogCode = "{{ :catalogCode }}"   {# параметр из URL, например /:catalogCode #}
==

{% if availableSorts|length %}
<form method="get" class="catalog-sorting">
    <label for="catalog-sort">Сортировать:</label>
    <select id="catalog-sort" name="sort" onchange="this.form.submit()">
        {% for code, label in availableSorts %}
            <option value="{{ code }}" {% if code == currentSort %}selected{% endif %}>
                {{ label }}
            </option>
        {% endfor %}
    </select>

    {# сохраняем прочие параметры страницы (пагинация, фильтры и т.п.) #}
    {% for name, value in this.request.get %}
        {% if name != 'sort' %}
            <input type="hidden" name="{{ name }}" value="{{ value }}">
        {% endif %}
    {% endfor %}
</form>
{% endif %}

{# здесь выводите список элементов каталога #}
```

#### Пример: страница категории (`catalog-category`)

```twig
[catalogList]
catalogCode = "{{ :catalogCode }}"
categorySlug = "{{ :slug }}"
==

{% if availableSorts|length %}
<form method="get" class="catalog-sorting">
    <label for="catalog-sort">Сортировать:</label>
    <select id="catalog-sort" name="sort" onchange="this.form.submit()">
        {% for code, label in availableSorts %}
            <option value="{{ code }}" {% if code == currentSort %}selected{% endif %}>
                {{ label }}
            </option>
        {% endfor %}
    </select>

    {% for name, value in this.request.get %}
        {% if name != 'sort' %}
            <input type="hidden" name="{{ name }}" value="{{ value }}">
        {% endif %}
    {% endfor %}
</form>
{% endif %}
```

Особенности:

-   `{{ code }}` — ключ сортировки из `availableSorts`, отправляется как `?sort={{ code }}`.
-   Внутри компонента `catalogList` вызываются:
    -   `CatalogSorting::resolveSortCode()` — нормализация и проверка кода;
    -   `CatalogSorting::applySorting()` — применение сортировки к запросу.
-   JavaScript не обязателен: форма работает как обычный GET-запрос, URL обновляется автоматически.

#### Пример вывода даты обновления и истории обновлений

```twig
{# В списке: показываем последнюю дату обновления и количество правок #}
{% for record in items %}
    <article>
        <h3>{{ record.display_name }}</h3>
        <p>Опубликовано: {{ record.published_at|date('d.m.Y H:i') }}</p>
        <p>Последнее обновление: {{ record.updated_at|date('d.m.Y H:i') }}</p>

        {% set history = record.data['_update_history'] ?? [] %}
        {% if history|length %}
            {% set last = history|last %}
            {% set lastTs = last.ts is defined ? last.ts : last %}
            <p>Правок: {{ history|length }}, последние: {{ lastTs|date('d.m.Y H:i') }}</p>
        {% endif %}
    </article>
{% endfor %}

{# На детальной странице: выводим полную историю #}
{% set itemHistory = item.data['_update_history'] ?? [] %}
{% if itemHistory|length %}
    <h4>История обновлений</h4>
    <ul>
        {% for entry in itemHistory %}
            {% set ts = entry.ts is defined ? entry.ts : entry %}
            {% set field = entry.field is defined ? entry.field : null %}
            {% set manual = entry.manual is defined ? entry.manual : false %}
            <li>
                {{ ts|date('d.m.Y H:i') }}
                {% if manual %}
                    — ручной подъём
                {% elseif field %}
                    — изменено поле {{ field }}
                {% endif %}
            </li>
        {% endfor %}
    </ul>
{% endif %}
```

---

## JS/UX

Плагин интегрируется с фронтендом Winter CMS и обеспечивает удобный UX без навязанных решений по вёрстке.

Основные UX-механики:

1. **Динамические поля в бэкенде**

    - Изменение `catalog_id` в форме элементов триггерит AJAX-перерисовку блока `_dynamic_fields_placeholder.htm`.
    - При открытии существующего элемента схема загружается автоматически.
    - Ошибки в JSON (например, в опциях `select`) подсвечиваются валидатором с сообщением.

2. **AJAX-формы на фронтенде**

    - `catalogForm` рассчитан на использование Snowboard (`data-request`) или UiCore (`samvol/uicore`).
    - После успешного сохранения в AJAX-ответе приходит обновлённый `item` и стандартные сообщения.

    Пример минимального JS-агностичного подхода (Snowboard уже встроен в Winter):

    ```twig
    <form data-request="{{ __SELF__ }}::onSave">
        {# поля формы #}
        <button type="submit">Сохранить</button>
    </form>
    ```

3. **Сортировка без JS**

    - Блок сортировки реализуется обычной HTML-формой с `method="get"`.
    - Выбор сортировки меняет query-параметр `sort`, компонент `catalogList` применяет его на сервере.
    - Такой подход хорошо работает с SEO и закладками.

4. **Работа с файлами и иконками**
    - Файлы элементов (`screenshot`, `archive`) отображаются стандартными для Winter способами (`path`, `thumb()`).
    - Иконки категорий (файл или SVG) позволяют реализовать современный UI с минимальным количеством кастомного кода.

Пример простой выборки элементов в PHP (если нужно использовать модели напрямую):

```php
use Samvol\Catalog\Models\Item;

$items = Item::where('catalog_id', $catalogId)
    ->where('status', 'published')
    ->orderBy('created_at', 'desc')
    ->get();
```

---

## Миграции

Плагин использует несколько миграций для создания и изменения структуры БД:

-   `create_catalogs_table.php`
-   `create_catalog_fields_table.php`
-   `create_catalog_categories_table.php`
-   `create_catalog_items_table.php`
-   `update_catalog_relations_nullable.php`

Особенности:

-   Все основные структуры (каталоги, поля, категории, элементы) используют JSON-поля для гибкости:
    -   `catalogs.settings` (условно),
    -   `categories.data`,
    -   `items.data`,
    -   опции полей и т.п.
-   Миграция `update_catalog_relations_nullable` делает `catalog_id` и `category_id` **необязательными** на ранних этапах, чтобы можно было:
    -   создавать черновики без выбранного каталога/категории;
    -   откладывать привязку к структуре до более позднего этапа.

Требования к СУБД:

-   Поддержка JSON-полей:
    -   MySQL 5.7+ или MariaDB 10.2+;
    -   PostgreSQL 9.4+.

---

## Примечания

-   Все JSON-поля (`fields.options`, `categories.data`, `items.data` и т.п.) проходят валидацию на корректный JSON. При ручном редактировании используйте строго валидный синтаксис.
-   Если при сохранении элемента не видно динамических полей:
    -   проверьте, выбран ли каталог у записи (`catalog_id`);
    -   убедитесь, что нет ошибок в AJAX-ответах (Snowboard покажет уведомление).
-   Массив `features`, который возвращают компоненты, помогает отделить логику от шаблонов: фронтенд может условно отображать блоки (счётчики просмотров, комментарии, рейтинг и т.п.).
-   Плагин ориентирован на Winter CMS 1.2+ и следует её стандартам:
    -   компоненты без вёрстки;
    -   AJAX через Snowboard;
    -   строгая работа с JSON и deferred binding файлов.
