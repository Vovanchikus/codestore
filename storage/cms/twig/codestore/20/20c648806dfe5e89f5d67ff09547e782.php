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
  </div>";
        // line 21
        yield "
  <div class=\"catalog-products\">
    ";
        // line 23
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("catalog/product-card"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 24
        yield "  </div>

</div>";
        // line 27
        yield "






";
        // line 35
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "lastPage", [], "method", false, false, true, 35) > 1)) {
            // line 36
            yield "  <nav class=\"pagination\">
    ";
            // line 37
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(range(1, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "lastPage", [], "method", false, false, true, 37)));
            foreach ($context['_seq'] as $context["_key"] => $context["pageNumber"]) {
                // line 38
                yield "      <a href=\"";
                yield $this->extensions['Cms\Twig\Extension']->pageFilter($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 38), "baseFileName", [], "any", false, false, true, 38), 38, $this->source), ["page" => $this->sandbox->ensureToStringAllowed(                // line 39
$context["pageNumber"], 39, $this->source), "catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source,                 // line 40
($context["catalog"] ?? null), "code", [], "any", false, false, true, 40), 40, $this->source)]);
                // line 41
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["pageNumber"], 41, $this->source), "html", null, true);
                yield "</a>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['pageNumber'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            yield "  </nav>
";
        }
        // line 45
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
        return array (  133 => 45,  129 => 43,  120 => 41,  118 => 40,  117 => 39,  115 => 38,  111 => 37,  108 => 36,  106 => 35,  97 => 27,  93 => 24,  89 => 23,  85 => 21,  82 => 19,  80 => 18,  76 => 17,  72 => 15,  70 => 14,  66 => 13,  62 => 11,  57 => 9,  52 => 8,  44 => 2,);
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

  </div>{# catalog__controls box--light #}

  <div class=\"catalog-products\">
    {% partial 'catalog/product-card' %}
  </div>

</div>{# catalog #}







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
        static $tags = ["partial" => 8, "if" => 35, "for" => 37];
        static $filters = ["escape" => 9, "page" => 38];
        static $functions = ["range" => 37];

        try {
            $this->sandbox->checkSecurity(
                ['partial', 'if', 'for'],
                ['escape', 'page'],
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
