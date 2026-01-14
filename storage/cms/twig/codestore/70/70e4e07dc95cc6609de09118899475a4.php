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

/* C:\OSPanel\domains\codestore-new\themes\codestore\partials\pagination.htm */
class __TwigTemplate_a19791193cb04f3fd43584303330bf45 extends Template
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
        $context["currentPage"] = (((array_key_exists("currentPage", $context) &&  !(null === $context["currentPage"]))) ? ($context["currentPage"]) : (CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "currentPage", [], "method", false, false, true, 1)));
        // line 2
        $context["lastPage"] = (((array_key_exists("lastPage", $context) &&  !(null === $context["lastPage"]))) ? ($context["lastPage"]) : (CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "lastPage", [], "method", false, false, true, 2)));
        // line 3
        yield "
<nav class=\"pagination\" role=\"navigation\" aria-label=\"Pagination\">

  ";
        // line 6
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previousPageUrl", [], "method", false, false, true, 6)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 7
            yield "    <a class=\"pagination__prev\" href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previousPageUrl", [], "method", false, false, true, 7), 7, $this->source), "html", null, true);
            yield "\">
      <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
        <path d=\"M6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11.75 13.6893L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C11.9874 15.5732 11.5126 15.5732 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967Z\" fill=\"currentColor\"/>
      </svg>
    </a>
  ";
        }
        // line 13
        yield "
  <div class=\"pagination__pages\">
    ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["paginationPages"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["page"]) {
            // line 16
            yield "        ";
            if (($context["page"] == "ellipsis")) {
                // line 17
                yield "            <span class=\"pagination__ellipsis\">…</span>
        ";
            } elseif ((            // line 18
($context["currentPage"] ?? null) == $context["page"])) {
                // line 19
                yield "            <div class=\"pagination__page is-active\" aria-current=\"page\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["page"], 19, $this->source), "html", null, true);
                yield "</div>
        ";
            } else {
                // line 21
                yield "            <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "url", [$context["page"]], "method", false, false, true, 21), 21, $this->source), "html", null, true);
                yield "\" class=\"pagination__page\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["page"], 21, $this->source), "html", null, true);
                yield "</a>
        ";
            }
            // line 23
            yield "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['page'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 24
        yield "</div>


  ";
        // line 27
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "nextPageUrl", [], "method", false, false, true, 27)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 28
            yield "    <a class=\"pagination__next\" href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "nextPageUrl", [], "method", false, false, true, 28), 28, $this->source), "html", null, true);
            yield "\">
      <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
        <path d=\"M6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11.75 13.6893L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C11.9874 15.5732 11.5126 15.5732 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967Z\" fill=\"currentColor\"/>
      </svg>
    </a>
  ";
        }
        // line 34
        yield "
</nav>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\pagination.htm";
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
        return array (  118 => 34,  108 => 28,  106 => 27,  101 => 24,  95 => 23,  87 => 21,  81 => 19,  79 => 18,  76 => 17,  73 => 16,  69 => 15,  65 => 13,  55 => 7,  53 => 6,  48 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% set currentPage = currentPage ?? items.currentPage() %}
{% set lastPage = lastPage ?? items.lastPage() %}

<nav class=\"pagination\" role=\"navigation\" aria-label=\"Pagination\">

  {% if items.previousPageUrl() %}
    <a class=\"pagination__prev\" href=\"{{ items.previousPageUrl() }}\">
      <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
        <path d=\"M6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11.75 13.6893L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C11.9874 15.5732 11.5126 15.5732 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967Z\" fill=\"currentColor\"/>
      </svg>
    </a>
  {% endif %}

  <div class=\"pagination__pages\">
    {% for page in paginationPages %}
        {% if page == 'ellipsis' %}
            <span class=\"pagination__ellipsis\">…</span>
        {% elseif currentPage == page %}
            <div class=\"pagination__page is-active\" aria-current=\"page\">{{ page }}</div>
        {% else %}
            <a href=\"{{ items.url(page) }}\" class=\"pagination__page\">{{ page }}</a>
        {% endif %}
    {% endfor %}
</div>


  {% if items.nextPageUrl() %}
    <a class=\"pagination__next\" href=\"{{ items.nextPageUrl() }}\">
      <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
        <path d=\"M6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11.75 13.6893L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C11.9874 15.5732 11.5126 15.5732 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967Z\" fill=\"currentColor\"/>
      </svg>
    </a>
  {% endif %}

</nav>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\pagination.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 1, "if" => 6, "for" => 15];
        static $filters = ["escape" => 7];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['escape'],
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
