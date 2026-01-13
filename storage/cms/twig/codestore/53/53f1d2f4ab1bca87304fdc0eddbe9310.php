<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* C:\OSPanel\domains\codestore-new\themes\codestore\pages\catalog\catalog-item.htm */
class __TwigTemplate_294bbf68ce8c0e36a4fd041db39637aa extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        if ((($tmp = ($context["item"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 2
            yield "        ";
            // line 3
            yield "        ";
            $context['__cms_partial_params'] = [];
            $context['__cms_partial_params']['catalog'] = ($context["catalog"] ?? null)            ;
            $context['__cms_partial_params']['category'] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "category", [], "any", false, false, true, 3)            ;
            $context['__cms_partial_params']['item'] = ($context["item"] ?? null)            ;
            echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/breadcrumbs"            , $context['__cms_partial_params']            , true            );
            unset($context['__cms_partial_params']);
            // line 4
            yield "
        ";
            // line 6
            yield "        <h1>";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "display_name", [], "any", false, false, true, 6), 6, $this->source), "html", null, true);
            yield "</h1>

        ";
            // line 9
            yield "        ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "category", [], "any", false, false, true, 9)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 10
                yield "                <h2>Категория: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "category", [], "any", false, false, true, 10), "name", [], "any", false, false, true, 10), 10, $this->source), "html", null, true);
                yield "</h2>
        ";
            }
            // line 12
            yield "
        ";
            // line 13
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, false, true, 13), "message", [], "any", false, false, true, 13)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 14
                yield "            Текст материала: ";
                yield $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, false, true, 14), "message", [], "any", false, false, true, 14), 14, $this->source);
                yield "
        ";
            }
            // line 16
            yield "    ";
            // line 17
            yield "
    ";
            // line 19
            yield "    ";
            $context['__cms_partial_params'] = [];
            $context['__cms_partial_params']['history'] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getNormalizedHistory", [], "method", false, false, true, 19)            ;
            echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/update_history"            , $context['__cms_partial_params']            , true            );
            unset($context['__cms_partial_params']);
            // line 20
            yield "
    ";
            // line 22
            yield "    ";
            if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "screenshot", [], "any", false, false, true, 22))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 23
                yield "        <div class=\"screens\">
            <strong>Скриншоты:</strong>
            ";
                // line 25
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "screenshot", [], "any", false, false, true, 25));
                foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                    // line 26
                    yield "                <img src=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["image"], "path", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                    yield "\" alt=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "display_name", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                    yield "\">
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['image'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 28
                yield "        </div>
    ";
            }
            // line 30
            yield "
    ";
            // line 32
            yield "    ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "archive", [], "any", false, false, true, 32)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 33
                yield "        <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "archive", [], "any", false, false, true, 33), "path", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
                yield "\"
           id=\"download-btn\"
           class=\"btn btn-link\"
           download=\"";
                // line 36
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "archive", [], "any", false, false, true, 36), "file_name", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
                yield "\"
           data-request=\"catalogItem::onDownload\"
           data-request-loading
           data-request-success=\"if (data && data.link) { window.location = data.link; } else { window.location = this.href; }\"
           data-request-error=\"window.location = this.href\">
           Скачать файл
        </a>
        <div class=\"text-muted\">Загрузок: ";
                // line 43
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "downloads_count", [], "any", true, true, true, 43)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "downloads_count", [], "any", false, false, true, 43), 43, $this->source), 0)) : (0)), "html", null, true);
                yield "</div>
    ";
            }
            // line 45
            yield "
    <p class=\"text-muted\">Просмотров: ";
            // line 46
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "views_count", [], "any", true, true, true, 46)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "views_count", [], "any", false, false, true, 46), 46, $this->source), 0)) : (0)), "html", null, true);
            yield "</p>

    ";
            // line 49
            yield "    <button
        class=\"btn btn-primary\"
        data-request=\"catalogItem::onRaise\"
        data-request-data=\"item_id: ";
            // line 52
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "id", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
            yield "\">
        Поднять материал
    </button>

    ";
            // line 57
            yield "    <p class=\"mt-3\">
        ";
            // line 58
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "category", [], "any", false, false, true, 58)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 59
                yield "            <a href=\"";
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-category", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,                 // line 60
($context["catalog"] ?? null), "code", [], "any", false, false, true, 60), 60, $this->source), "categorySlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                 // line 61
($context["item"] ?? null), "category", [], "any", false, false, true, 61), "slug", [], "any", false, false, true, 61), 61, $this->source)]);
                // line 62
                yield "\">
                Вернуться к категории
            </a>
        ";
            } else {
                // line 66
                yield "            <a href=\"";
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["catalog"] ?? null), "code", [], "any", false, false, true, 66), 66, $this->source)]);
                yield "\">
                Вернуться в каталог
            </a>
        ";
            }
            // line 70
            yield "    </p>

";
        } else {
            // line 73
            yield "    <p>Элемент не найден.</p>
";
        }
        // line 75
        yield "
";
        // line 77
        yield "<style>
.update-history ul { padding-left: 1em; }
.update-history li { margin-bottom: 0.5em; }
.update-history strong { color: #333; }
.mt-3 { margin-top: 1rem; }
.mt-4 { margin-top: 1.5rem; }
</style>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\pages\\catalog\\catalog-item.htm";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  209 => 77,  206 => 75,  202 => 73,  197 => 70,  189 => 66,  183 => 62,  181 => 61,  180 => 60,  178 => 59,  176 => 58,  173 => 57,  166 => 52,  161 => 49,  156 => 46,  153 => 45,  148 => 43,  138 => 36,  131 => 33,  128 => 32,  125 => 30,  121 => 28,  110 => 26,  106 => 25,  102 => 23,  99 => 22,  96 => 20,  90 => 19,  87 => 17,  85 => 16,  79 => 14,  77 => 13,  74 => 12,  68 => 10,  65 => 9,  59 => 6,  56 => 4,  48 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if item %}
        {# Универсальные хлебные крошки для элемента #}
        {% partial 'catalog/breadcrumbs' catalog=catalog category=item.category item=item %}

        {# Заголовок элемента #}
        <h1>{{ item.display_name }}</h1>

        {# Категория элемента #}
        {% if item.category %}
                <h2>Категория: {{ item.category.name }}</h2>
        {% endif %}

        {% if item.data.message %}
            Текст материала: {{ item.data.message|raw }}
        {% endif %}
    {# Все динамические поля по схеме fields #}

    {# История обновлений через partial #}
    {% partial 'catalog/update_history' history=item.getNormalizedHistory() %}

    {# Скриншоты attachMany #}
    {% if item.screenshot|length %}
        <div class=\"screens\">
            <strong>Скриншоты:</strong>
            {% for image in item.screenshot %}
                <img src=\"{{ image.path }}\" alt=\"{{ item.display_name }}\">
            {% endfor %}
        </div>
    {% endif %}

    {# Архив attachOne #}
    {% if item.archive %}
        <a href=\"{{ item.archive.path }}\"
           id=\"download-btn\"
           class=\"btn btn-link\"
           download=\"{{ item.archive.file_name }}\"
           data-request=\"catalogItem::onDownload\"
           data-request-loading
           data-request-success=\"if (data && data.link) { window.location = data.link; } else { window.location = this.href; }\"
           data-request-error=\"window.location = this.href\">
           Скачать файл
        </a>
        <div class=\"text-muted\">Загрузок: {{ item.downloads_count|default(0) }}</div>
    {% endif %}

    <p class=\"text-muted\">Просмотров: {{ item.views_count|default(0) }}</p>

    {# Кнопка \"Поднять материал\" #}
    <button
        class=\"btn btn-primary\"
        data-request=\"catalogItem::onRaise\"
        data-request-data=\"item_id: {{ item.id }}\">
        Поднять материал
    </button>

    {# Ссылка обратно в каталог (общий список) #}
    <p class=\"mt-3\">
        {% if item.category %}
            <a href=\"{{ 'catalog/catalog-category'|page({
                catalogCode: catalog.code,
                categorySlug: item.category.slug
            }) }}\">
                Вернуться к категории
            </a>
        {% else %}
            <a href=\"{{ 'catalog/catalog'|page({ catalogCode: catalog.code }) }}\">
                Вернуться в каталог
            </a>
        {% endif %}
    </p>

{% else %}
    <p>Элемент не найден.</p>
{% endif %}

{# Минимальные стили для истории #}
<style>
.update-history ul { padding-left: 1em; }
.update-history li { margin-bottom: 0.5em; }
.update-history strong { color: #333; }
.mt-3 { margin-top: 1rem; }
.mt-4 { margin-top: 1.5rem; }
</style>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\pages\\catalog\\catalog-item.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "partial" => 3, "for" => 25];
        static $filters = ["escape" => 6, "raw" => 14, "length" => 22, "default" => 43, "page" => 59];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'partial', 'for'],
                ['escape', 'raw', 'length', 'default', 'page'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
