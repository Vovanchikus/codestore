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

/* C:\OSPanel\domains\codestore-new\themes\codestore\partials\catalog\product-card.htm */
class __TwigTemplate_9cfe2d4da93279a76b5a292b39b8aee4 extends Template
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
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 2
            yield "
  <div class=\"product-card\">
    <div class=\"product-card__img\">

      ";
            // line 6
            if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 6))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 7
                yield "        <img src=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, (($_v0 = CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 7)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[0] ?? null) : CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 7), 0, [], "array", false, false, true, 7)), "path", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
                yield "\" alt=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "display_name", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
                yield "\">
      ";
            } else {
                // line 9
                yield "        <img src=\"https://via.placeholder.com/150\" alt=\"No image available\">
      ";
            }
            // line 11
            yield "
      ";
            // line 12
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["catalog"] ?? null), "track_updates_badge_enabled", [], "any", false, false, true, 12)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 13
                yield "        ";
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["product"], "isRecentlyUpdated", [], "any", false, false, true, 13)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 14
                    yield "          <span class=\"product-card__badge badge badge--lg badge--success\">Обновлено</span>
        ";
                } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source,                 // line 15
$context["product"], "hasEverBeenUpdated", [], "any", false, false, true, 15)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 16
                    yield "          <span class=\"product-card__badge badge badge--lg badge--brand\">v";
                    yield (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, true, true, 16), "version", [], "any", true, true, true, 16) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 16), "version", [], "any", false, false, true, 16)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 16), "version", [], "any", false, false, true, 16), 16, $this->source), "html", null, true)) : ("1.0.0"));
                    yield "</span>
        ";
                }
                // line 18
                yield "      ";
            }
            // line 19
            yield "
    </div>";
            // line 21
            yield "
    <div class=\"product-card__middle\">

      <div class=\"product-card__labels\">
        <div class=\"product-card__category label label--sm label--success\">";
            // line 25
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "category", [], "any", false, false, true, 25), "name", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
            yield "</div>
      </div>";
            // line 27
            yield "


      <div class=\"product-card__title\">";
            // line 30
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "display_name", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
            yield "</div>

      <div class=\"product-card__brief\">
        ";
            // line 33
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 33), "message", [], "any", false, false, true, 33)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 34
                yield "            <p> ";
                yield $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 34), "message", [], "any", false, false, true, 34), 34, $this->source);
                yield " </p>
        ";
            } else {
                // line 36
                yield "            <p>Описание отсутствует.</p>
        ";
            }
            // line 38
            yield "      </div>";
            // line 39
            yield "
    </div>";
            // line 41
            yield "
    <div class=\"product-card__bottom\">

      <div class=\"product-card__price-box\">

        ";
            // line 46
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 46), "new_price", [], "any", false, false, true, 46)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 47
                yield "          <div class=\"product-card__price product-card__price--old\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 47), "price", [], "any", false, false, true, 47), 47, $this->source), "html", null, true);
                yield "</div>
          <div class=\"product-card__price product-card__price--new\">";
                // line 48
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 48), "new_price", [], "any", false, false, true, 48), 48, $this->source), "html", null, true);
                yield "</div>
        ";
            } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 49
$context["product"], "data", [], "any", false, false, true, 49), "price", [], "any", false, false, true, 49)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 50
                yield "          <div class=\"product-card__price product-card__price--start\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 50), "price", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
                yield "</div>
        ";
            }
            // line 52
            yield "
      </div>";
            // line 54
            yield "
      <a href=\"";
            // line 55
            yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-item", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,             // line 56
($context["catalog"] ?? null), "code", [], "any", false, false, true, 56), 56, $this->source), "categorySlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 57
$context["product"], "category", [], "any", false, false, true, 57), "slug", [], "any", false, false, true, 57), 57, $this->source), "itemSlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 58
$context["product"], "data", [], "any", false, false, true, 58), "slug", [], "any", false, false, true, 58), 58, $this->source)]);
            // line 59
            yield "\"
        class=\"product-card__button button button--sm button--brand\">Подробнее</a>

    </div>";
            // line 63
            yield "
  </div>";
            // line 65
            yield "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['product'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\product-card.htm";
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
        return array (  176 => 65,  173 => 63,  168 => 59,  166 => 58,  165 => 57,  164 => 56,  163 => 55,  160 => 54,  157 => 52,  151 => 50,  149 => 49,  145 => 48,  140 => 47,  138 => 46,  131 => 41,  128 => 39,  126 => 38,  122 => 36,  116 => 34,  114 => 33,  108 => 30,  103 => 27,  99 => 25,  93 => 21,  90 => 19,  87 => 18,  81 => 16,  79 => 15,  76 => 14,  73 => 13,  71 => 12,  68 => 11,  64 => 9,  56 => 7,  54 => 6,  48 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% for product in items %}

  <div class=\"product-card\">
    <div class=\"product-card__img\">

      {% if product.screenshot|length %}
        <img src=\"{{ product.screenshot[0].path }}\" alt=\"{{ product.display_name }}\">
      {% else %}
        <img src=\"https://via.placeholder.com/150\" alt=\"No image available\">
      {% endif %}

      {% if catalog.track_updates_badge_enabled %}
        {% if product.isRecentlyUpdated %}
          <span class=\"product-card__badge badge badge--lg badge--success\">Обновлено</span>
        {% elseif product.hasEverBeenUpdated %}
          <span class=\"product-card__badge badge badge--lg badge--brand\">v{{ product.data.version ?? '1.0.0' }}</span>
        {% endif %}
      {% endif %}

    </div>{# product-card__img #}

    <div class=\"product-card__middle\">

      <div class=\"product-card__labels\">
        <div class=\"product-card__category label label--sm label--success\">{{ product.category.name }}</div>
      </div>{# product-card__labels #}



      <div class=\"product-card__title\">{{ product.display_name }}</div>

      <div class=\"product-card__brief\">
        {% if product.data.message %}
            <p> {{ product.data.message|raw }} </p>
        {% else %}
            <p>Описание отсутствует.</p>
        {% endif %}
      </div>{# product-card__brief #}

    </div>{# product-card__middle #}

    <div class=\"product-card__bottom\">

      <div class=\"product-card__price-box\">

        {% if product.data.new_price %}
          <div class=\"product-card__price product-card__price--old\">{{ product.data.price }}</div>
          <div class=\"product-card__price product-card__price--new\">{{ product.data.new_price }}</div>
        {% elseif product.data.price %}
          <div class=\"product-card__price product-card__price--start\">{{ product.data.price }}</div>
        {% endif %}

      </div>{# product-card__price-box #}

      <a href=\"{{ 'catalog/catalog-item'|page({
          catalogCode: catalog.code,
          categorySlug: product.category.slug,
          itemSlug: product.data.slug
        }) }}\"
        class=\"product-card__button button button--sm button--brand\">Подробнее</a>

    </div>{# product-card__bottom #}

  </div>{# product-card #}

{% endfor %}", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\product-card.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 1, "if" => 6];
        static $filters = ["length" => 6, "escape" => 7, "raw" => 34, "page" => 55];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['length', 'escape', 'raw', 'page'],
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
