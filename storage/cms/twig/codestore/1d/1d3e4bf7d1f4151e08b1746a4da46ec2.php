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

/* C:\OSPanel\domains\codestore-new\themes\codestore\partials\catalog\update_history.htm */
class __TwigTemplate_b761ea3ae285a7a1444560e1312b639c extends Template
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
        $context["entries"] = (((array_key_exists("history", $context) &&  !(null === $context["history"]))) ? ($context["history"]) : ([]));
        // line 2
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["entries"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 3
            yield "<div class=\"catalog-update-history\">
    <h4>История обновлений</h4>
    <ul>
        ";
            // line 6
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["entries"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["entry"]) {
                // line 7
                yield "            <li>
                <span class=\"update-date\">";
                // line 8
                yield $this->env->getFilter('date')->getCallable()($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["entry"], "date", [], "any", false, false, true, 8), 8, $this->source), "d.m.Y H:i");
                yield "</span>
                <span class=\"update-text\">";
                // line 9
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["entry"], "text", [], "any", false, false, true, 9), 9, $this->source), "html", null, true);
                yield "</span>
            </li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['entry'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 12
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
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\update_history.htm";
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
        return array (  73 => 12,  64 => 9,  60 => 8,  57 => 7,  53 => 6,  48 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% set entries = history ?? [] %}
{% if entries|length %}
<div class=\"catalog-update-history\">
    <h4>История обновлений</h4>
    <ul>
        {% for entry in entries %}
            <li>
                <span class=\"update-date\">{{ entry.date|date('d.m.Y H:i') }}</span>
                <span class=\"update-text\">{{ entry.text }}</span>
            </li>
        {% endfor %}
    </ul>
</div>
{% endif %}", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\update_history.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 1, "if" => 2, "for" => 6];
        static $filters = ["length" => 2, "date" => 8, "escape" => 9];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['length', 'date', 'escape'],
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
