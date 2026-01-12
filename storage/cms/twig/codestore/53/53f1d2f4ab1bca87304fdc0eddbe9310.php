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
            // line 14
            yield "    <dl>
    ";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["fields"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
                // line 16
                yield "        <dt>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["field"], "name", [], "any", false, false, true, 16), 16, $this->source), "html", null, true);
                yield "</dt>
        ";
                // line 17
                $context["val"] = (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, true, true, 17), CoreExtension::getAttribute($this->env, $this->source, $context["field"], "code", [], "any", false, false, true, 17), [], "array", true, true, true, 17) &&  !(null === (($_v0 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, false, true, 17)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[CoreExtension::getAttribute($this->env, $this->source, $context["field"], "code", [], "any", false, false, true, 17)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, false, true, 17), CoreExtension::getAttribute($this->env, $this->source, $context["field"], "code", [], "any", false, false, true, 17), [], "array", false, false, true, 17))))) ? ((($_v1 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, false, true, 17)) && is_array($_v1) || $_v1 instanceof ArrayAccess && in_array($_v1::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v1[CoreExtension::getAttribute($this->env, $this->source, $context["field"], "code", [], "any", false, false, true, 17)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "data", [], "any", false, false, true, 17), CoreExtension::getAttribute($this->env, $this->source, $context["field"], "code", [], "any", false, false, true, 17), [], "array", false, false, true, 17))) : (""));
                // line 18
                yield "        <dd>
            ";
                // line 19
                if (is_iterable(($context["val"] ?? null))) {
                    // line 20
                    yield "                ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(($context["val"] ?? null));
                    $context['loop'] = [
                      'parent' => $context['_parent'],
                      'index0' => 0,
                      'index'  => 1,
                      'first'  => true,
                    ];
                    if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                        $length = count($context['_seq']);
                        $context['loop']['revindex0'] = $length - 1;
                        $context['loop']['revindex'] = $length;
                        $context['loop']['length'] = $length;
                        $context['loop']['last'] = 1 === $length;
                    }
                    foreach ($context['_seq'] as $context["_key"] => $context["v"]) {
                        // line 21
                        yield "                    ";
                        if (is_iterable($context["v"])) {
                            // line 22
                            yield "                        ";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(json_encode($this->sandbox->ensureToStringAllowed($context["v"], 22, $this->source)), "html", null, true);
                            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, true, 22)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                                yield ", ";
                            }
                            // line 23
                            yield "                    ";
                        } else {
                            // line 24
                            yield "                        ";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["v"], 24, $this->source), "html", null, true);
                            if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, true, 24)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                                yield ", ";
                            }
                            // line 25
                            yield "                    ";
                        }
                        // line 26
                        yield "                ";
                        ++$context['loop']['index0'];
                        ++$context['loop']['index'];
                        $context['loop']['first'] = false;
                        if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                            --$context['loop']['revindex0'];
                            --$context['loop']['revindex'];
                            $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['v'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 27
                    yield "            ";
                } else {
                    // line 28
                    yield "                ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["val"] ?? null), 28, $this->source), "html", null, true);
                    yield "
            ";
                }
                // line 30
                yield "        </dd>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['field'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 32
            yield "    </dl>

    ";
            // line 35
            yield "    ";
            $context['__cms_partial_params'] = [];
            $context['__cms_partial_params']['history'] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getNormalizedHistory", [], "method", false, false, true, 35)            ;
            echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/update_history"            , $context['__cms_partial_params']            , true            );
            unset($context['__cms_partial_params']);
            // line 36
            yield "
    ";
            // line 38
            yield "    ";
            if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "screenshot", [], "any", false, false, true, 38))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 39
                yield "        <div class=\"screens\">
            <strong>Скриншоты:</strong>
            ";
                // line 41
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "screenshot", [], "any", false, false, true, 41));
                foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                    // line 42
                    yield "                <img src=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["image"], "path", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
                    yield "\" alt=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "display_name", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
                    yield "\">
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['image'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 44
                yield "        </div>
    ";
            }
            // line 46
            yield "
    ";
            // line 48
            yield "    ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "archive", [], "any", false, false, true, 48)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 49
                yield "        <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "archive", [], "any", false, false, true, 49), "path", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
                yield "\"
           id=\"download-btn\"
           class=\"btn btn-link\"
           download=\"";
                // line 52
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "archive", [], "any", false, false, true, 52), "file_name", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
                yield "\"
           data-request=\"catalogItem::onDownload\"
           data-request-loading
           data-request-success=\"if (data && data.link) { window.location = data.link; } else { window.location = this.href; }\"
           data-request-error=\"window.location = this.href\">
           Скачать файл
        </a>
        <div class=\"text-muted\">Загрузок: ";
                // line 59
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "downloads_count", [], "any", true, true, true, 59)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "downloads_count", [], "any", false, false, true, 59), 59, $this->source), 0)) : (0)), "html", null, true);
                yield "</div>
    ";
            }
            // line 61
            yield "
    <p class=\"text-muted\">Просмотров: ";
            // line 62
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "views_count", [], "any", true, true, true, 62)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "views_count", [], "any", false, false, true, 62), 62, $this->source), 0)) : (0)), "html", null, true);
            yield "</p>

    ";
            // line 65
            yield "    <button
        class=\"btn btn-primary\"
        data-request=\"catalogItem::onRaise\"
        data-request-data=\"item_id: ";
            // line 68
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "id", [], "any", false, false, true, 68), 68, $this->source), "html", null, true);
            yield "\">
        Поднять материал
    </button>

    ";
            // line 73
            yield "    <p class=\"mt-3\">
        ";
            // line 74
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "category", [], "any", false, false, true, 74)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 75
                yield "            <a href=\"";
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-category", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,                 // line 76
($context["catalog"] ?? null), "code", [], "any", false, false, true, 76), 76, $this->source), "categorySlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                 // line 77
($context["item"] ?? null), "category", [], "any", false, false, true, 77), "slug", [], "any", false, false, true, 77), 77, $this->source)]);
                // line 78
                yield "\">
                Вернуться к категории
            </a>
        ";
            } else {
                // line 82
                yield "            <a href=\"";
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["catalog"] ?? null), "code", [], "any", false, false, true, 82), 82, $this->source)]);
                yield "\">
                Вернуться в каталог
            </a>
        ";
            }
            // line 86
            yield "    </p>

";
        } else {
            // line 89
            yield "    <p>Элемент не найден.</p>
";
        }
        // line 91
        yield "
";
        // line 93
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
        return array (  288 => 93,  285 => 91,  281 => 89,  276 => 86,  268 => 82,  262 => 78,  260 => 77,  259 => 76,  257 => 75,  255 => 74,  252 => 73,  245 => 68,  240 => 65,  235 => 62,  232 => 61,  227 => 59,  217 => 52,  210 => 49,  207 => 48,  204 => 46,  200 => 44,  189 => 42,  185 => 41,  181 => 39,  178 => 38,  175 => 36,  169 => 35,  165 => 32,  158 => 30,  152 => 28,  149 => 27,  135 => 26,  132 => 25,  126 => 24,  123 => 23,  117 => 22,  114 => 21,  96 => 20,  94 => 19,  91 => 18,  89 => 17,  84 => 16,  80 => 15,  77 => 14,  74 => 12,  68 => 10,  65 => 9,  59 => 6,  56 => 4,  48 => 3,  46 => 2,  44 => 1,);
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

    {# Все динамические поля по схеме fields #}
    <dl>
    {% for field in fields %}
        <dt>{{ field.name }}</dt>
        {% set val = item.data[field.code] ?? '' %}
        <dd>
            {% if val is iterable %}
                {% for v in val %}
                    {% if v is iterable %}
                        {{ v|json_encode }}{% if not loop.last %}, {% endif %}
                    {% else %}
                        {{ v }}{% if not loop.last %}, {% endif %}
                    {% endif %}
                {% endfor %}
            {% else %}
                {{ val }}
            {% endif %}
        </dd>
    {% endfor %}
    </dl>

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
        static $tags = ["if" => 1, "partial" => 3, "for" => 15, "set" => 17];
        static $filters = ["escape" => 6, "json_encode" => 22, "length" => 38, "default" => 59, "page" => 75];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'partial', 'for', 'set'],
                ['escape', 'json_encode', 'length', 'default', 'page'],
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
