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

/* C:\OSPanel\domains\codestore-new\plugins/samvol/catalog/components/catalogslist/default.htm */
class __TwigTemplate_26b4e09feea45e94a80bfccc5509d309 extends Template
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
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["catalogs"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 2
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["catalogs"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["catalog"]) {
                // line 3
                yield "
        <li class=\"header-nav__item\">
            <a href=\"";
                // line 5
                yield $this->extensions['Cms\Twig\Extension']->pageFilter("catalog/catalog-home", ["catalogCode" => $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["catalog"], "code", [], "any", false, false, true, 5), 5, $this->source)]);
                yield "\" class=\"header-nav__link\">
                ";
                // line 6
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["catalog"], "name", [], "any", false, false, true, 6), 6, $this->source), "html", null, true);
                yield "
            </a>
        </li>

    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['catalog'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\plugins/samvol/catalog/components/catalogslist/default.htm";
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
        return array (  59 => 6,  55 => 5,  51 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if catalogs|length %}
    {% for catalog in catalogs %}

        <li class=\"header-nav__item\">
            <a href=\"{{ 'catalog/catalog-home'|page({ catalogCode: catalog.code }) }}\" class=\"header-nav__link\">
                {{ catalog.name }}
            </a>
        </li>

    {% endfor %}
{% endif %}
", "C:\\OSPanel\\domains\\codestore-new\\plugins/samvol/catalog/components/catalogslist/default.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "for" => 2];
        static $filters = ["length" => 1, "page" => 5, "escape" => 6];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['length', 'page', 'escape'],
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
