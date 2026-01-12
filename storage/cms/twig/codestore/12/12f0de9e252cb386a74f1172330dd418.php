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

/* C:\OSPanel\domains\codestore-new\themes\codestore\layouts\default.htm */
class __TwigTemplate_201998bba6bac1e006a59d891ffa1f43 extends Template
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
        yield "<!DOCTYPE html>
<html lang=\"ru\">
    <head>
        <meta charset=\"utf-8\">
        <title>";
        // line 5
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 5), "title", [], "any", false, false, true, 5), 5, $this->source), "html", null, true);
        yield "</title>
        <meta name=\"description\" content=\"";
        // line 6
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 6), "meta_description", [], "any", false, false, true, 6), 6, $this->source), "html", null, true);
        yield "\">
        <meta name=\"title\" content=\"";
        // line 7
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 7), "meta_title", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
        yield "\">
        <meta name=\"author\" content=\"Winter CMS\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta name=\"generator\" content=\"Winter CMS\">
        <link rel=\"icon\" type=\"image/png\" href=\"";
        // line 11
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/images/winter.png");
        yield "\">
        <link href=\"";
        // line 12
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/variables.css");
        yield "\" rel=\"stylesheet\">
        <link href=\"";
        // line 13
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/main.css");
        yield "\" rel=\"stylesheet\">
        ";
        // line 14
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('css');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('styles');
        // line 15
        yield "    </head>
    <body>

        <div class=\"container\">

            ";
        // line 20
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/header"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 21
        yield "
            <main>";
        // line 22
        echo $this->env->getExtension('Cms\Twig\Extension')->pageFunction();
        yield "</main>

            ";
        // line 25
        yield "
            ";
        // line 26
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/scripts"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 27
        yield "
        </div>";
        // line 29
        yield "

    </body>
</html>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\layouts\\default.htm";
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
        return array (  109 => 29,  106 => 27,  102 => 26,  99 => 25,  94 => 22,  91 => 21,  87 => 20,  80 => 15,  77 => 14,  73 => 13,  69 => 12,  65 => 11,  58 => 7,  54 => 6,  50 => 5,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"ru\">
    <head>
        <meta charset=\"utf-8\">
        <title>{{ this.page.title }}</title>
        <meta name=\"description\" content=\"{{ this.page.meta_description }}\">
        <meta name=\"title\" content=\"{{ this.page.meta_title }}\">
        <meta name=\"author\" content=\"Winter CMS\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta name=\"generator\" content=\"Winter CMS\">
        <link rel=\"icon\" type=\"image/png\" href=\"{{ 'assets/images/winter.png'|theme }}\">
        <link href=\"{{ 'assets/css/variables.css'|theme }}\" rel=\"stylesheet\">
        <link href=\"{{ 'assets/css/main.css'|theme }}\" rel=\"stylesheet\">
        {% styles %}
    </head>
    <body>

        <div class=\"container\">

            {% partial 'global/header' %}

            <main>{% page %}</main>

            {# Технический блок #}

            {% partial 'global/scripts' %}

        </div>{# container #}


    </body>
</html>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\layouts\\default.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["styles" => 14, "partial" => 20, "page" => 22];
        static $filters = ["escape" => 5, "theme" => 11];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['styles', 'partial', 'page'],
                ['escape', 'theme'],
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
