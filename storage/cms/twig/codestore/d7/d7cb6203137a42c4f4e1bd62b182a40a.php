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

/* C:\OSPanel\domains\codestore-new\themes\codestore\partials\catalog\categories.htm */
class __TwigTemplate_6f5a1fa871ed089ee43fb944d96ec68e extends Template
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
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["categories"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 3
            yield "  <div class=\"catalog__categories\">
    <ul class=\"catalog__categories-list\">
      ";
            // line 5
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["categories"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["cat"]) {
                // line 6
                yield "
      <li class=\"catalog__category\">
        <a href=\"";
                // line 8
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-category", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["catalog"] ?? null), "code", [], "any", false, false, true, 8), 8, $this->source), "categorySlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "slug", [], "any", false, false, true, 8), 8, $this->source)]);
                yield "\" class=\"catalog__category-link\">

          <div class=\"catalog__category-icon\">
            ";
                // line 11
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "icon", [], "any", false, false, true, 11)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 12
                    yield "                <img src=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "icon", [], "any", false, false, true, 12), "path", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
                    yield "\" alt=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "name", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
                    yield " \">
            ";
                } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source,                 // line 13
$context["cat"], "icon_svg", [], "any", false, false, true, 13)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 14
                    yield "                ";
                    yield $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "icon_svg", [], "any", false, false, true, 14), 14, $this->source);
                    yield "
            ";
                }
                // line 16
                yield "          </div>

          <div class=\"catalog__category-info\">
            <div class=\"catalog__category-title\">";
                // line 19
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "name", [], "any", false, false, true, 19), 19, $this->source), "html", null, true);
                yield " <span class=\"catalog__category-count\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "items_count", [], "any", true, true, true, 19)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "items_count", [], "any", false, false, true, 19), 19, $this->source), 0)) : (0)), "html", null, true);
                yield "</span></div>
            <div class=\"catalog__category-subtitle\">";
                // line 20
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "description", [], "any", false, false, true, 20), 20, $this->source), "html", null, true);
                yield "</div>
          </div>

        </a>
      </li>

      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['cat'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 27
            yield "    </ul>
  </div>
";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\categories.htm";
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
        return array (  105 => 27,  92 => 20,  86 => 19,  81 => 16,  75 => 14,  73 => 13,  66 => 12,  64 => 11,  58 => 8,  54 => 6,  50 => 5,  46 => 3,  44 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# Вывод категорий каталога с ссылками на фильтр по categorySlug #}
{% if categories|length %}
  <div class=\"catalog__categories\">
    <ul class=\"catalog__categories-list\">
      {% for cat in categories %}

      <li class=\"catalog__category\">
        <a href=\"{{ 'catalog/catalog-category'|page({ catalogCode: catalog.code, categorySlug: cat.slug }) }}\" class=\"catalog__category-link\">

          <div class=\"catalog__category-icon\">
            {% if cat.icon %}
                <img src=\"{{ cat.icon.path }}\" alt=\"{{ cat.name }} \">
            {% elseif cat.icon_svg %}
                {{ cat.icon_svg|raw }}
            {% endif %}
          </div>

          <div class=\"catalog__category-info\">
            <div class=\"catalog__category-title\">{{ cat.name }} <span class=\"catalog__category-count\">{{ cat.items_count|default(0) }}</span></div>
            <div class=\"catalog__category-subtitle\">{{ cat.description }}</div>
          </div>

        </a>
      </li>

      {% endfor %}
    </ul>
  </div>
{% endif %}", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\categories.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 2, "for" => 5];
        static $filters = ["length" => 2, "page" => 8, "escape" => 12, "raw" => 14, "default" => 19];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['length', 'page', 'escape', 'raw', 'default'],
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
