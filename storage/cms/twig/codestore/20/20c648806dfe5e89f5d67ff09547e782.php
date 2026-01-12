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

/* C:\OSPanel\domains\codestore-new\themes\codestore\pages\catalog\catalog-home.htm */
class __TwigTemplate_70cbe561002389ce57904e7e5b6f99f5 extends Template
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
        // line 2
        yield "
<div class=\"catalog\">

  <div class=\"catalog__controls box--light\">

    <div class=\"catalog__controls-top\">
      ";
        // line 8
        $context['__cms_partial_params'] = [];
        $context['__cms_partial_params']['catalog'] = ($context["catalog"] ?? null)        ;
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/breadcrumbs"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 9
        yield "      <div class=\"catalog__controls-count\">Всего:<span>";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "total", [], "method", false, false, true, 9), 9, $this->source), "html", null, true);
        yield "</span></div>
    </div>";
        // line 11
        yield "
    <div class=\"catalog__controls-middle\">
      ";
        // line 13
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/categories"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 14
        yield "    </div>";
        // line 15
        yield "
    <div class=\"catalog__controls-bottom\">
      ";
        // line 17
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/sorting"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 18
        yield "    </div>";
        // line 19
        yield "
  </div>

</div>";
        // line 23
        yield "




";
        // line 29
        yield "<div id=\"catalog-list\">
";
        // line 30
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["record"]) {
            // line 31
            yield "  <article class=\"catalog-item\">
    ";
            // line 33
            yield "    <h2>";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["record"], "display_name", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
            yield "</h2>

    ";
            // line 36
            yield "    ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["record"], "category", [], "any", false, false, true, 36)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 37
                yield "      <h3>Категория: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["record"], "category", [], "any", false, false, true, 37), "name", [], "any", false, false, true, 37), 37, $this->source), "html", null, true);
                yield "</h3>
    ";
            }
            // line 39
            yield "
    ";
            // line 41
            yield "    <p><strong>Title:</strong> ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["record"], "data", [], "any", false, false, true, 41), "title", [], "any", false, false, true, 41), 41, $this->source), "html", null, true);
            yield "</p>
    <p><strong>Brief:</strong> ";
            // line 42
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["record"], "data", [], "any", false, false, true, 42), "brief", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
            yield "</p>
    <div><strong>Message:</strong> ";
            // line 43
            yield $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["record"], "data", [], "any", false, false, true, 43), "message", [], "any", false, false, true, 43), 43, $this->source);
            yield "</div>

    ";
            // line 46
            yield "    ";
            if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["record"], "screenshot", [], "any", false, false, true, 46))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 47
                yield "      <div class=\"screens\">
        <strong>Скриншоты:</strong>
        ";
                // line 49
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["record"], "screenshot", [], "any", false, false, true, 49));
                foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                    // line 50
                    yield "          <img src=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["image"], "path", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
                    yield "\" alt=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["record"], "display_name", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
                    yield "\">
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['image'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 52
                yield "      </div>
    ";
            }
            // line 54
            yield "
    ";
            // line 56
            yield "    ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["record"], "archive", [], "any", false, false, true, 56)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 57
                yield "      <p><a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["record"], "archive", [], "any", false, false, true, 57), "path", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
                yield "\">Скачать архив</a></p>
    ";
            }
            // line 59
            yield "
    ";
            // line 61
            yield "    ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["record"], "category", [], "any", false, false, true, 61)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 62
                yield "      <p>
        <a href=\"";
                // line 63
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-item", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,                 // line 64
($context["catalog"] ?? null), "code", [], "any", false, false, true, 64), 64, $this->source), "categorySlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                 // line 65
$context["record"], "category", [], "any", false, false, true, 65), "slug", [], "any", false, false, true, 65), 65, $this->source), "itemSlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                 // line 66
$context["record"], "data", [], "any", false, false, true, 66), "slug", [], "any", false, false, true, 66), 66, $this->source)]);
                // line 67
                yield "\">
          Подробнее
        </a>
      </p>
    ";
            }
            // line 72
            yield "  </article>
";
            $context['_iterated'] = true;
        }
        // line 73
        if (!$context['_iterated']) {
            // line 74
            yield "  <p>Элементы отсутствуют.</p>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['record'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
        yield "
";
        // line 78
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "lastPage", [], "method", false, false, true, 78) > 1)) {
            // line 79
            yield "  <nav class=\"pagination\">
    ";
            // line 80
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(range(1, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "lastPage", [], "method", false, false, true, 80)));
            foreach ($context['_seq'] as $context["_key"] => $context["pageNumber"]) {
                // line 81
                yield "      <a href=\"";
                yield $this->extensions['Cms\Twig\Extension']->pageFilter($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 81), "baseFileName", [], "any", false, false, true, 81), 81, $this->source), ["page" => $this->sandbox->ensureToStringAllowed(                // line 82
$context["pageNumber"], 82, $this->source), "catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,                 // line 83
($context["catalog"] ?? null), "code", [], "any", false, false, true, 83), 83, $this->source)]);
                // line 84
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["pageNumber"], 84, $this->source), "html", null, true);
                yield "</a>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['pageNumber'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 86
            yield "  </nav>
";
        }
        // line 88
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\pages\\catalog\\catalog-home.htm";
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
        return array (  240 => 88,  236 => 86,  227 => 84,  225 => 83,  224 => 82,  222 => 81,  218 => 80,  215 => 79,  213 => 78,  210 => 76,  203 => 74,  201 => 73,  196 => 72,  189 => 67,  187 => 66,  186 => 65,  185 => 64,  184 => 63,  181 => 62,  178 => 61,  175 => 59,  169 => 57,  166 => 56,  163 => 54,  159 => 52,  148 => 50,  144 => 49,  140 => 47,  137 => 46,  132 => 43,  128 => 42,  123 => 41,  120 => 39,  114 => 37,  111 => 36,  105 => 33,  102 => 31,  97 => 30,  94 => 29,  87 => 23,  82 => 19,  80 => 18,  76 => 17,  72 => 15,  70 => 14,  66 => 13,  62 => 11,  57 => 9,  52 => 8,  44 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# Универсальные хлебные крошки (catalog only) #}

<div class=\"catalog\">

  <div class=\"catalog__controls box--light\">

    <div class=\"catalog__controls-top\">
      {% partial 'catalog/breadcrumbs' catalog=catalog %}
      <div class=\"catalog__controls-count\">Всего:<span>{{ items.total() }}</span></div>
    </div>{# catalog__controls-top #}

    <div class=\"catalog__controls-middle\">
      {% partial 'catalog/categories' %}
    </div>{# catalog-controls-middle #}

    <div class=\"catalog__controls-bottom\">
      {% partial \"catalog/sorting\" %}
    </div>{# catalog__controls-bottom #}

  </div>

</div>{# catalog #}





{# Цикл по элементам каталога #}
<div id=\"catalog-list\">
{% for record in items %}
  <article class=\"catalog-item\">
    {# Название элемента #}
    <h2>{{ record.display_name }}</h2>

    {# Название категории элемента (если есть) #}
    {% if record.category %}
      <h3>Категория: {{ record.category.name }}</h3>
    {% endif %}

    {# Динамические поля: title, brief, message #}
    <p><strong>Title:</strong> {{ record.data.title }}</p>
    <p><strong>Brief:</strong> {{ record.data.brief }}</p>
    <div><strong>Message:</strong> {{ record.data.message|raw }}</div>

    {# Скриншоты (attachMany) #}
    {% if record.screenshot|length %}
      <div class=\"screens\">
        <strong>Скриншоты:</strong>
        {% for image in record.screenshot %}
          <img src=\"{{ image.path }}\" alt=\"{{ record.display_name }}\">
        {% endfor %}
      </div>
    {% endif %}

    {# Архив (attachOne) #}
    {% if record.archive %}
      <p><a href=\"{{ record.archive.path }}\">Скачать архив</a></p>
    {% endif %}

    {# Ссылка на страницу элемента #}
    {% if record.category %}
      <p>
        <a href=\"{{ 'catalog/catalog-item'|page({
          catalogCode: catalog.code,
          categorySlug: record.category.slug,
          itemSlug: record.data.slug
        }) }}\">
          Подробнее
        </a>
      </p>
    {% endif %}
  </article>
{% else %}
  <p>Элементы отсутствуют.</p>
{% endfor %}

{# Пагинация #}
{% if items.lastPage() > 1 %}
  <nav class=\"pagination\">
    {% for pageNumber in 1..items.lastPage() %}
      <a href=\"{{ this.page.baseFileName|page({
        ('page'): pageNumber,
        catalogCode: catalog.code
      }) }}\">{{ pageNumber }}</a>
    {% endfor %}
  </nav>
{% endif %}
</div>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\pages\\catalog\\catalog-home.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["partial" => 8, "for" => 30, "if" => 36];
        static $filters = ["escape" => 9, "raw" => 43, "length" => 46, "page" => 63];
        static $functions = ["range" => 80];

        try {
            $this->sandbox->checkSecurity(
                ['partial', 'for', 'if'],
                ['escape', 'raw', 'length', 'page'],
                ['range'],
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
