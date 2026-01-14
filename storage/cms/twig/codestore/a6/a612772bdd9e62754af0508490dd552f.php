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
                yield "
        <a href=\"";
                // line 8
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, (($_v0 = CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 8)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[0] ?? null) : CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 8), 0, [], "array", false, false, true, 8)), "path", [], "any", false, false, true, 8), 8, $this->source), "html", null, true);
                yield "\" class=\"product-card__img-link glightbox\" data-gallery=\"product-";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "id", [], "any", false, false, true, 8), 8, $this->source), "html", null, true);
                yield "\">

          <img src=\"";
                // line 10
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, (($_v1 = CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 10)) && is_array($_v1) || $_v1 instanceof ArrayAccess && in_array($_v1::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v1[0] ?? null) : CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "screenshot", [], "any", false, false, true, 10), 0, [], "array", false, false, true, 10)), "path", [], "any", false, false, true, 10), 10, $this->source), "html", null, true);
                yield "\" alt=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "display_name", [], "any", false, false, true, 10), 10, $this->source), "html", null, true);
                yield "\">

          <div class=\"img-zoom\">
            <div class=\"img-zoom__icon\">

            <svg width=\"56\" height=\"56\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
            <path opacity=\"1\" d=\"M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z\" fill=\"#fff\"></path>
            <path d=\"M16 11.25H12.75V8C12.75 7.59 12.41 7.25 12 7.25C11.59 7.25 11.25 7.59 11.25 8V11.25H8C7.59 11.25 7.25 11.59 7.25 12C7.25 12.41 7.59 12.75 8 12.75H11.25V16C11.25 16.41 11.59 16.75 12 16.75C12.41 16.75 12.75 16.41 12.75 16V12.75H16C16.41 12.75 16.75 12.41 16.75 12C16.75 11.59 16.41 11.25 16 11.25Z\" fill=\"#4a4a7d\"></path>
            </svg>

            </div><!-- img-zomm__icon -->
          </div>";
                // line 22
                yield "
        </a>";
                // line 24
                yield "      ";
            } else {
                // line 25
                yield "        <img src=\"https://via.placeholder.com/150\" alt=\"No image available\">
      ";
            }
            // line 27
            yield "
      ";
            // line 28
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["catalog"] ?? null), "track_updates_badge_enabled", [], "any", false, false, true, 28)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 29
                yield "        ";
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["product"], "isRecentlyUpdated", [], "any", false, false, true, 29)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 30
                    yield "          <span class=\"product-card__badge badge badge--lg badge--success\">Обновлено</span>
        ";
                } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source,                 // line 31
$context["product"], "hasEverBeenUpdated", [], "any", false, false, true, 31)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 32
                    yield "          <span class=\"product-card__badge badge badge--lg badge--brand\">v";
                    yield (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, true, true, 32), "version", [], "any", true, true, true, 32) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 32), "version", [], "any", false, false, true, 32)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 32), "version", [], "any", false, false, true, 32), 32, $this->source), "html", null, true)) : ("1.0.0"));
                    yield "</span>
        ";
                }
                // line 34
                yield "      ";
            }
            // line 35
            yield "
    </div>";
            // line 37
            yield "
    <div class=\"product-card__middle\">

      <div class=\"product-card__labels\">

      ";
            // line 42
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "category", [], "any", false, false, true, 42), "name", [], "any", false, false, true, 42) == "Макеты")) {
                // line 43
                yield "          <div class=\"product-card__category label label--sm label--success\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "category", [], "any", false, false, true, 43), "name", [], "any", false, false, true, 43), 43, $this->source), "html", null, true);
                yield "</div>
      ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 44
$context["product"], "category", [], "any", false, false, true, 44), "name", [], "any", false, false, true, 44) == "Шаблоны")) {
                // line 45
                yield "          <div class=\"product-card__category label label--sm label--brand\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "category", [], "any", false, false, true, 45), "name", [], "any", false, false, true, 45), 45, $this->source), "html", null, true);
                yield "</div>
      ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 46
$context["product"], "category", [], "any", false, false, true, 46), "name", [], "any", false, false, true, 46) == "Элементы дизайна")) {
                // line 47
                yield "          <div class=\"product-card__category label label--sm label--error\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "category", [], "any", false, false, true, 47), "name", [], "any", false, false, true, 47), 47, $this->source), "html", null, true);
                yield "</div>
      ";
            }
            // line 49
            yield "
      </div>";
            // line 51
            yield "


      <div class=\"product-card__title\">";
            // line 54
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "display_name", [], "any", false, false, true, 54), 54, $this->source), "html", null, true);
            yield "</div>

      <div class=\"product-card__brief\">
        ";
            // line 57
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 57), "message", [], "any", false, false, true, 57)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 58
                yield "            <p> ";
                yield $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 58), "message", [], "any", false, false, true, 58), 58, $this->source);
                yield " </p>
        ";
            } else {
                // line 60
                yield "            <p>Описание отсутствует.</p>
        ";
            }
            // line 62
            yield "      </div>";
            // line 63
            yield "
    </div>";
            // line 65
            yield "
    <div class=\"product-card__bottom\">

      <div class=\"product-card__price-box\">

        ";
            // line 70
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 70), "new_price", [], "any", false, false, true, 70)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 71
                yield "          <div class=\"product-card__price product-card__price--old\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 71), "price", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
                yield "</div>
          <div class=\"product-card__price product-card__price--new\">";
                // line 72
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 72), "new_price", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
                yield "</div>
        ";
            } elseif ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 73
$context["product"], "data", [], "any", false, false, true, 73), "price", [], "any", false, false, true, 73)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 74
                yield "          <div class=\"product-card__price product-card__price--start\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["product"], "data", [], "any", false, false, true, 74), "price", [], "any", false, false, true, 74), 74, $this->source), "html", null, true);
                yield "</div>
        ";
            }
            // line 76
            yield "
      </div>";
            // line 78
            yield "
      <a href=\"";
            // line 79
            yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-item", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,             // line 80
($context["catalog"] ?? null), "code", [], "any", false, false, true, 80), 80, $this->source), "categorySlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 81
$context["product"], "category", [], "any", false, false, true, 81), "slug", [], "any", false, false, true, 81), 81, $this->source), "itemSlug" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 82
$context["product"], "data", [], "any", false, false, true, 82), "slug", [], "any", false, false, true, 82), 82, $this->source)]);
            // line 83
            yield "\"
        class=\"product-card__button button button--sm button--brand\">Подробнее</a>

    </div>";
            // line 87
            yield "
  </div>";
            // line 89
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
        return array (  222 => 89,  219 => 87,  214 => 83,  212 => 82,  211 => 81,  210 => 80,  209 => 79,  206 => 78,  203 => 76,  197 => 74,  195 => 73,  191 => 72,  186 => 71,  184 => 70,  177 => 65,  174 => 63,  172 => 62,  168 => 60,  162 => 58,  160 => 57,  154 => 54,  149 => 51,  146 => 49,  140 => 47,  138 => 46,  133 => 45,  131 => 44,  126 => 43,  124 => 42,  117 => 37,  114 => 35,  111 => 34,  105 => 32,  103 => 31,  100 => 30,  97 => 29,  95 => 28,  92 => 27,  88 => 25,  85 => 24,  82 => 22,  66 => 10,  59 => 8,  56 => 7,  54 => 6,  48 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% for product in items %}

  <div class=\"product-card\">
    <div class=\"product-card__img\">

      {% if product.screenshot|length %}

        <a href=\"{{ product.screenshot[0].path }}\" class=\"product-card__img-link glightbox\" data-gallery=\"product-{{ product.id }}\">

          <img src=\"{{ product.screenshot[0].path }}\" alt=\"{{ product.display_name }}\">

          <div class=\"img-zoom\">
            <div class=\"img-zoom__icon\">

            <svg width=\"56\" height=\"56\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
            <path opacity=\"1\" d=\"M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z\" fill=\"#fff\"></path>
            <path d=\"M16 11.25H12.75V8C12.75 7.59 12.41 7.25 12 7.25C11.59 7.25 11.25 7.59 11.25 8V11.25H8C7.59 11.25 7.25 11.59 7.25 12C7.25 12.41 7.59 12.75 8 12.75H11.25V16C11.25 16.41 11.59 16.75 12 16.75C12.41 16.75 12.75 16.41 12.75 16V12.75H16C16.41 12.75 16.75 12.41 16.75 12C16.75 11.59 16.41 11.25 16 11.25Z\" fill=\"#4a4a7d\"></path>
            </svg>

            </div><!-- img-zomm__icon -->
          </div>{# img-zoom #}

        </a>{# glightbox #}
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

      {% if product.category.name == 'Макеты' %}
          <div class=\"product-card__category label label--sm label--success\">{{ product.category.name }}</div>
      {% elseif product.category.name == 'Шаблоны' %}
          <div class=\"product-card__category label label--sm label--brand\">{{ product.category.name }}</div>
      {% elseif product.category.name == 'Элементы дизайна' %}
          <div class=\"product-card__category label label--sm label--error\">{{ product.category.name }}</div>
      {% endif %}

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
        static $filters = ["length" => 6, "escape" => 8, "raw" => 58, "page" => 79];
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
