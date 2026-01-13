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

/* C:\OSPanel\domains\codestore-new\themes\codestore\partials\catalog\sorting.htm */
class __TwigTemplate_a1c43e405a8f0ea9cf3d0797c8cf2f87 extends Template
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
        yield "<div class=\"catalog__sort\">
    <div class=\"catalog__sort-title\">Сортировать по:</div>

    <div class=\"catalog__sort-menu\" data-current-sort=\"";
        // line 4
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("currentSort", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["currentSort"] ?? null), 4, $this->source), "")) : ("")), "html", null, true);
        yield "\">
        ";
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["sortingItems"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 6
            yield "            <li>
                <a href=\"";
            // line 7
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "href", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
            yield "\" class=\"catalog__sort-link ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "class", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
            yield "\">
                    <span class=\"catalog__sort-menu-icon\">
                        ";
            // line 9
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "icon", [], "any", false, false, true, 9) == "calendar")) {
                // line 10
                yield "                            <!-- SVG календаря -->

                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                              <path d=\"M6.25 13.25C6.25 12.6977 6.69772 12.25 7.25 12.25C7.80228 12.25 8.2501 12.6977 8.2501 13.25C8.2501 13.8023 7.80238 14.25 7.2501 14.25C6.69782 14.25 6.25 13.8023 6.25 13.25Z\" fill=\"currentColor\"/>
                              <path d=\"M11.75 12.25C11.1977 12.25 10.75 12.6977 10.75 13.25C10.75 13.8023 11.1977 14.25 11.75 14.25C12.3023 14.25 12.7501 13.8023 12.7501 13.25C12.7501 12.6977 12.3023 12.25 11.75 12.25Z\" fill=\"currentColor\"/>
                              <path d=\"M15.25 13.25C15.25 12.6977 15.6977 12.25 16.25 12.25C16.8023 12.25 17.2501 12.6977 17.2501 13.25C17.2501 13.8023 16.8024 14.25 16.2501 14.25C15.6978 14.25 15.25 13.8023 15.25 13.25Z\" fill=\"currentColor\"/>
                              <path d=\"M7.25 16.25C6.69772 16.25 6.25 16.6977 6.25 17.25C6.25 17.8023 6.69772 18.25 7.25 18.25C7.80228 18.25 8.2501 17.8023 8.2501 17.25C8.2501 16.6977 7.80228 16.25 7.25 16.25Z\" fill=\"currentColor\"/>
                              <path d=\"M10.75 17.25C10.75 16.6977 11.1977 16.25 11.75 16.25C12.3023 16.25 12.7501 16.6977 12.7501 17.25C12.7501 17.8023 12.3024 18.25 11.7501 18.25C11.1978 18.25 10.75 17.8023 10.75 17.25Z\" fill=\"currentColor\"/>
                              <path d=\"M16.25 16.25C15.6977 16.25 15.25 16.6977 15.25 17.25C15.25 17.8023 15.6977 18.25 16.25 18.25C16.8023 18.25 17.2501 17.8023 17.2501 17.25C17.2501 16.6977 16.8023 16.25 16.25 16.25Z\" fill=\"currentColor\"/>
                              <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.5 0.75C8.5 0.335786 8.16421 0 7.75 0C7.33579 0 7 0.335786 7 0.75V2.00007C6.19395 2.00064 5.53458 2.00569 4.99013 2.05018C4.36012 2.10165 3.81824 2.20963 3.32054 2.46322C2.52085 2.87068 1.87068 3.52085 1.46322 4.32054C1.20963 4.81824 1.10165 5.36012 1.05018 5.99013C0.999989 6.60439 0.999994 7.36493 1 8.31737V17.1826C0.999994 18.135 0.999989 18.8956 1.05018 19.5099C1.10165 20.1399 1.20963 20.6818 1.46322 21.1795C1.87068 21.9791 2.52085 22.6293 3.32054 23.0368C3.81824 23.2904 4.36012 23.3983 4.99013 23.4498C5.60438 23.5 6.3649 23.5 7.3173 23.5H16.1826C17.135 23.5 17.8956 23.5 18.5099 23.4498C19.1399 23.3983 19.6818 23.2904 20.1795 23.0368C20.9791 22.6293 21.6293 21.9791 22.0368 21.1795C22.2904 20.6818 22.3983 20.1399 22.4498 19.5099C22.5 18.8956 22.5 18.1351 22.5 17.1827V8.31737C22.5 7.36496 22.5 6.60438 22.4498 5.99013C22.3983 5.36012 22.2904 4.81824 22.0368 4.32054C21.6293 3.52085 20.9791 2.87068 20.1795 2.46322C19.6818 2.20963 19.1399 2.10165 18.5099 2.05018C17.9654 2.00569 17.3061 2.00064 16.5 2.00007V0.75C16.5 0.335786 16.1642 0 15.75 0C15.3358 0 15 0.335786 15 0.75V2H8.5V0.75ZM16.15 3.5C17.1425 3.5 17.8417 3.50058 18.3877 3.54519C18.925 3.58909 19.2475 3.67184 19.4985 3.79973C20.0159 4.06338 20.4366 4.48408 20.7003 5.00153C20.8282 5.25252 20.9109 5.57503 20.9548 6.11228C20.9855 6.4874 20.9953 6.93484 20.9985 7.5H2.5015C2.50468 6.93484 2.51455 6.4874 2.54519 6.11228C2.58909 5.57503 2.67184 5.25252 2.79973 5.00153C3.06338 4.48408 3.48408 4.06338 4.00153 3.79973C4.25252 3.67184 4.57503 3.58909 5.11228 3.54519C5.65829 3.50058 6.35753 3.5 7.35 3.5H16.15ZM2.5 9V17.15C2.5 18.1425 2.50058 18.8417 2.54519 19.3877C2.58909 19.925 2.67184 20.2475 2.79973 20.4985C3.06338 21.0159 3.48408 21.4366 4.00153 21.7003C4.25252 21.8282 4.57503 21.9109 5.11228 21.9548C5.65829 21.9994 6.35753 22 7.35 22H16.15C17.1425 22 17.8417 21.9994 18.3877 21.9548C18.925 21.9109 19.2475 21.8282 19.4985 21.7003C20.0159 21.4366 20.4366 21.0159 20.7003 20.4985C20.8282 20.2475 20.9109 19.925 20.9548 19.3877C20.9994 18.8417 21 18.1425 21 17.15V9H2.5Z\" fill=\"currentColor\"/>
                            </svg>

                        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 22
$context["item"], "icon", [], "any", false, false, true, 22) == "font-case")) {
                // line 23
                yield "                            <!-- SVG иконки -->
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M6.2502 4C6.55857 4 6.83551 4.18875 6.94826 4.47576L12.4483 18.4758C12.5997 18.8613 12.41 19.2966 12.0244 19.4481C11.6389 19.5995 11.2036 19.4098 11.0521 19.0242L9.45154 14.95H3.04886L1.44826 19.0242C1.29681 19.4098 0.861491 19.5995 0.475962 19.4481C0.0904317 19.2966 -0.099321 18.8613 0.0521372 18.4758L5.55214 4.47576C5.66489 4.18875 5.94183 4 6.2502 4ZM3.63814 13.45H8.86226L6.2502 6.80113L3.63814 13.45Z\" fill=\"currentColor\"/>
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M22.7502 8C23.1644 8 23.5002 8.33579 23.5002 8.75V18.75C23.5002 19.1642 23.1644 19.5 22.7502 19.5C22.336 19.5 22.0002 19.1642 22.0002 18.75V18.0982C21.159 18.9502 20.0235 19.5 18.7502 19.5C17.4704 19.5 16.2518 19.0604 15.3618 18.0614C14.4796 17.0713 14.0002 15.6217 14.0002 13.75C14.0002 11.8783 14.4796 10.4287 15.3618 9.4386C16.2518 8.43958 17.4704 8 18.7502 8C20.0235 8 21.159 8.54975 22.0002 9.40176V8.75C22.0002 8.33579 22.336 8 22.7502 8ZM22.0002 13C22.0002 11.1454 20.4878 9.5 18.7502 9.5C17.8209 9.5 17.0394 9.81042 16.4818 10.4364C15.9162 11.0713 15.5002 12.1217 15.5002 13.75C15.5002 15.3783 15.9162 16.4287 16.4818 17.0636C17.0394 17.6896 17.8209 18 18.7502 18C20.4878 18 22.0002 16.3546 22.0002 14.5V13Z\" fill=\"currentColor\"/>
                            </svg>


                        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 30
$context["item"], "icon", [], "any", false, false, true, 30) == "download-arc")) {
                // line 31
                yield "                            <!-- SVG download -->
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path d=\"M11 12.9393L11 1.75C11 1.33579 11.3358 1 11.75 1C12.1642 1 12.5 1.33579 12.5 1.75L12.5 12.9393L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C12.1397 15.421 11.9489 15.5 11.75 15.5C11.5511 15.5 11.3603 15.421 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11 12.9393Z\" fill=\"currentColor\"/>
                                <path d=\"M21.75 11C22.1642 11 22.5 11.3358 22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 11.3358 1.33579 11 1.75 11C2.16421 11 2.5 11.3358 2.5 11.75C2.5 16.8586 6.64137 21 11.75 21C16.8586 21 21 16.8586 21 11.75C21 11.3358 21.3358 11 21.75 11Z\" fill=\"currentColor\"/>
                            </svg>

                        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 37
$context["item"], "icon", [], "any", false, false, true, 37) == "eye")) {
                // line 38
                yield "                            <!-- SVG eye -->
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M11.75 7.5C9.40003 7.5 7.5 9.40003 7.5 11.75C7.5 14.1 9.40003 16 11.75 16C14.1 16 16 14.1 16 11.75C16 9.40003 14.1 7.5 11.75 7.5ZM9 11.75C9 10.2285 10.2285 9 11.75 9C13.2715 9 14.5 10.2285 14.5 11.75C14.5 13.2715 13.2715 14.5 11.75 14.5C10.2285 14.5 9 13.2715 9 11.75Z\" fill=\"currentColor\"/>
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M22.2926 10.5572C18.6739 0.480934 4.82613 0.480935 1.20739 10.5572C0.93087 11.3272 0.93087 12.1728 1.20739 12.9428C4.82613 23.0191 18.6739 23.0191 22.2926 12.9428C22.5691 12.1728 22.5691 11.3272 22.2926 10.5572ZM2.61911 11.0642C5.76235 2.31193 17.7376 2.31194 20.8809 11.0642C21.0397 11.5064 21.0397 11.9936 20.8809 12.4358C17.7376 21.1881 5.76235 21.1881 2.61911 12.4358C2.4603 11.9936 2.4603 11.5064 2.61911 11.0642Z\" fill=\"currentColor\"/>
                            </svg>
                        ";
            } else {
                // line 44
                yield "                            ";
                yield $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "iconHtml", [], "any", false, false, true, 44), 44, $this->source);
                yield "
                        ";
            }
            // line 46
            yield "                    </span>
                    ";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 47), 47, $this->source), "html", null, true);
            yield "

                    ";
            // line 49
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "isActive", [], "any", false, false, true, 49)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 50
                yield "                        <span class=\"catalog__sort-menu-arrow\">

                            <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path d=\"M7.28033 15.2197C6.98744 14.9268 6.51256 14.9268 6.21967 15.2197C5.92678 15.5126 5.92678 15.9874 6.21967 16.2803L11.2197 21.2803C11.5126 21.5732 11.9874 21.5732 12.2803 21.2803L17.2803 16.2803C17.5732 15.9874 17.5732 15.5126 17.2803 15.2197C16.9874 14.9268 16.5126 14.9268 16.2197 15.2197L12.5 18.9393V2.75C12.5 2.33579 12.1642 2 11.75 2C11.3358 2 11 2.33579 11 2.75V18.9393L7.28033 15.2197Z\" fill=\"currentColor\"/>
                            </svg>

                        </span>
                    ";
            }
            // line 58
            yield "                </a>
            </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        yield "    </div>
</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\sorting.htm";
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
        return array (  148 => 61,  140 => 58,  130 => 50,  128 => 49,  123 => 47,  120 => 46,  114 => 44,  106 => 38,  104 => 37,  96 => 31,  94 => 30,  85 => 23,  83 => 22,  69 => 10,  67 => 9,  60 => 7,  57 => 6,  53 => 5,  49 => 4,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"catalog__sort\">
    <div class=\"catalog__sort-title\">Сортировать по:</div>

    <div class=\"catalog__sort-menu\" data-current-sort=\"{{ currentSort|default('') }}\">
        {% for item in sortingItems %}
            <li>
                <a href=\"{{ item.href }}\" class=\"catalog__sort-link {{ item.class }}\">
                    <span class=\"catalog__sort-menu-icon\">
                        {% if item.icon == 'calendar' %}
                            <!-- SVG календаря -->

                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                              <path d=\"M6.25 13.25C6.25 12.6977 6.69772 12.25 7.25 12.25C7.80228 12.25 8.2501 12.6977 8.2501 13.25C8.2501 13.8023 7.80238 14.25 7.2501 14.25C6.69782 14.25 6.25 13.8023 6.25 13.25Z\" fill=\"currentColor\"/>
                              <path d=\"M11.75 12.25C11.1977 12.25 10.75 12.6977 10.75 13.25C10.75 13.8023 11.1977 14.25 11.75 14.25C12.3023 14.25 12.7501 13.8023 12.7501 13.25C12.7501 12.6977 12.3023 12.25 11.75 12.25Z\" fill=\"currentColor\"/>
                              <path d=\"M15.25 13.25C15.25 12.6977 15.6977 12.25 16.25 12.25C16.8023 12.25 17.2501 12.6977 17.2501 13.25C17.2501 13.8023 16.8024 14.25 16.2501 14.25C15.6978 14.25 15.25 13.8023 15.25 13.25Z\" fill=\"currentColor\"/>
                              <path d=\"M7.25 16.25C6.69772 16.25 6.25 16.6977 6.25 17.25C6.25 17.8023 6.69772 18.25 7.25 18.25C7.80228 18.25 8.2501 17.8023 8.2501 17.25C8.2501 16.6977 7.80228 16.25 7.25 16.25Z\" fill=\"currentColor\"/>
                              <path d=\"M10.75 17.25C10.75 16.6977 11.1977 16.25 11.75 16.25C12.3023 16.25 12.7501 16.6977 12.7501 17.25C12.7501 17.8023 12.3024 18.25 11.7501 18.25C11.1978 18.25 10.75 17.8023 10.75 17.25Z\" fill=\"currentColor\"/>
                              <path d=\"M16.25 16.25C15.6977 16.25 15.25 16.6977 15.25 17.25C15.25 17.8023 15.6977 18.25 16.25 18.25C16.8023 18.25 17.2501 17.8023 17.2501 17.25C17.2501 16.6977 16.8023 16.25 16.25 16.25Z\" fill=\"currentColor\"/>
                              <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.5 0.75C8.5 0.335786 8.16421 0 7.75 0C7.33579 0 7 0.335786 7 0.75V2.00007C6.19395 2.00064 5.53458 2.00569 4.99013 2.05018C4.36012 2.10165 3.81824 2.20963 3.32054 2.46322C2.52085 2.87068 1.87068 3.52085 1.46322 4.32054C1.20963 4.81824 1.10165 5.36012 1.05018 5.99013C0.999989 6.60439 0.999994 7.36493 1 8.31737V17.1826C0.999994 18.135 0.999989 18.8956 1.05018 19.5099C1.10165 20.1399 1.20963 20.6818 1.46322 21.1795C1.87068 21.9791 2.52085 22.6293 3.32054 23.0368C3.81824 23.2904 4.36012 23.3983 4.99013 23.4498C5.60438 23.5 6.3649 23.5 7.3173 23.5H16.1826C17.135 23.5 17.8956 23.5 18.5099 23.4498C19.1399 23.3983 19.6818 23.2904 20.1795 23.0368C20.9791 22.6293 21.6293 21.9791 22.0368 21.1795C22.2904 20.6818 22.3983 20.1399 22.4498 19.5099C22.5 18.8956 22.5 18.1351 22.5 17.1827V8.31737C22.5 7.36496 22.5 6.60438 22.4498 5.99013C22.3983 5.36012 22.2904 4.81824 22.0368 4.32054C21.6293 3.52085 20.9791 2.87068 20.1795 2.46322C19.6818 2.20963 19.1399 2.10165 18.5099 2.05018C17.9654 2.00569 17.3061 2.00064 16.5 2.00007V0.75C16.5 0.335786 16.1642 0 15.75 0C15.3358 0 15 0.335786 15 0.75V2H8.5V0.75ZM16.15 3.5C17.1425 3.5 17.8417 3.50058 18.3877 3.54519C18.925 3.58909 19.2475 3.67184 19.4985 3.79973C20.0159 4.06338 20.4366 4.48408 20.7003 5.00153C20.8282 5.25252 20.9109 5.57503 20.9548 6.11228C20.9855 6.4874 20.9953 6.93484 20.9985 7.5H2.5015C2.50468 6.93484 2.51455 6.4874 2.54519 6.11228C2.58909 5.57503 2.67184 5.25252 2.79973 5.00153C3.06338 4.48408 3.48408 4.06338 4.00153 3.79973C4.25252 3.67184 4.57503 3.58909 5.11228 3.54519C5.65829 3.50058 6.35753 3.5 7.35 3.5H16.15ZM2.5 9V17.15C2.5 18.1425 2.50058 18.8417 2.54519 19.3877C2.58909 19.925 2.67184 20.2475 2.79973 20.4985C3.06338 21.0159 3.48408 21.4366 4.00153 21.7003C4.25252 21.8282 4.57503 21.9109 5.11228 21.9548C5.65829 21.9994 6.35753 22 7.35 22H16.15C17.1425 22 17.8417 21.9994 18.3877 21.9548C18.925 21.9109 19.2475 21.8282 19.4985 21.7003C20.0159 21.4366 20.4366 21.0159 20.7003 20.4985C20.8282 20.2475 20.9109 19.925 20.9548 19.3877C20.9994 18.8417 21 18.1425 21 17.15V9H2.5Z\" fill=\"currentColor\"/>
                            </svg>

                        {% elseif item.icon == 'font-case' %}
                            <!-- SVG иконки -->
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M6.2502 4C6.55857 4 6.83551 4.18875 6.94826 4.47576L12.4483 18.4758C12.5997 18.8613 12.41 19.2966 12.0244 19.4481C11.6389 19.5995 11.2036 19.4098 11.0521 19.0242L9.45154 14.95H3.04886L1.44826 19.0242C1.29681 19.4098 0.861491 19.5995 0.475962 19.4481C0.0904317 19.2966 -0.099321 18.8613 0.0521372 18.4758L5.55214 4.47576C5.66489 4.18875 5.94183 4 6.2502 4ZM3.63814 13.45H8.86226L6.2502 6.80113L3.63814 13.45Z\" fill=\"currentColor\"/>
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M22.7502 8C23.1644 8 23.5002 8.33579 23.5002 8.75V18.75C23.5002 19.1642 23.1644 19.5 22.7502 19.5C22.336 19.5 22.0002 19.1642 22.0002 18.75V18.0982C21.159 18.9502 20.0235 19.5 18.7502 19.5C17.4704 19.5 16.2518 19.0604 15.3618 18.0614C14.4796 17.0713 14.0002 15.6217 14.0002 13.75C14.0002 11.8783 14.4796 10.4287 15.3618 9.4386C16.2518 8.43958 17.4704 8 18.7502 8C20.0235 8 21.159 8.54975 22.0002 9.40176V8.75C22.0002 8.33579 22.336 8 22.7502 8ZM22.0002 13C22.0002 11.1454 20.4878 9.5 18.7502 9.5C17.8209 9.5 17.0394 9.81042 16.4818 10.4364C15.9162 11.0713 15.5002 12.1217 15.5002 13.75C15.5002 15.3783 15.9162 16.4287 16.4818 17.0636C17.0394 17.6896 17.8209 18 18.7502 18C20.4878 18 22.0002 16.3546 22.0002 14.5V13Z\" fill=\"currentColor\"/>
                            </svg>


                        {% elseif item.icon == 'download-arc' %}
                            <!-- SVG download -->
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path d=\"M11 12.9393L11 1.75C11 1.33579 11.3358 1 11.75 1C12.1642 1 12.5 1.33579 12.5 1.75L12.5 12.9393L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C12.1397 15.421 11.9489 15.5 11.75 15.5C11.5511 15.5 11.3603 15.421 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11 12.9393Z\" fill=\"currentColor\"/>
                                <path d=\"M21.75 11C22.1642 11 22.5 11.3358 22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 11.3358 1.33579 11 1.75 11C2.16421 11 2.5 11.3358 2.5 11.75C2.5 16.8586 6.64137 21 11.75 21C16.8586 21 21 16.8586 21 11.75C21 11.3358 21.3358 11 21.75 11Z\" fill=\"currentColor\"/>
                            </svg>

                        {% elseif item.icon == 'eye' %}
                            <!-- SVG eye -->
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M11.75 7.5C9.40003 7.5 7.5 9.40003 7.5 11.75C7.5 14.1 9.40003 16 11.75 16C14.1 16 16 14.1 16 11.75C16 9.40003 14.1 7.5 11.75 7.5ZM9 11.75C9 10.2285 10.2285 9 11.75 9C13.2715 9 14.5 10.2285 14.5 11.75C14.5 13.2715 13.2715 14.5 11.75 14.5C10.2285 14.5 9 13.2715 9 11.75Z\" fill=\"currentColor\"/>
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M22.2926 10.5572C18.6739 0.480934 4.82613 0.480935 1.20739 10.5572C0.93087 11.3272 0.93087 12.1728 1.20739 12.9428C4.82613 23.0191 18.6739 23.0191 22.2926 12.9428C22.5691 12.1728 22.5691 11.3272 22.2926 10.5572ZM2.61911 11.0642C5.76235 2.31193 17.7376 2.31194 20.8809 11.0642C21.0397 11.5064 21.0397 11.9936 20.8809 12.4358C17.7376 21.1881 5.76235 21.1881 2.61911 12.4358C2.4603 11.9936 2.4603 11.5064 2.61911 11.0642Z\" fill=\"currentColor\"/>
                            </svg>
                        {% else %}
                            {{ item.iconHtml|raw }}
                        {% endif %}
                    </span>
                    {{ item.label }}

                    {% if item.isActive %}
                        <span class=\"catalog__sort-menu-arrow\">

                            <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path d=\"M7.28033 15.2197C6.98744 14.9268 6.51256 14.9268 6.21967 15.2197C5.92678 15.5126 5.92678 15.9874 6.21967 16.2803L11.2197 21.2803C11.5126 21.5732 11.9874 21.5732 12.2803 21.2803L17.2803 16.2803C17.5732 15.9874 17.5732 15.5126 17.2803 15.2197C16.9874 14.9268 16.5126 14.9268 16.2197 15.2197L12.5 18.9393V2.75C12.5 2.33579 12.1642 2 11.75 2C11.3358 2 11 2.33579 11 2.75V18.9393L7.28033 15.2197Z\" fill=\"currentColor\"/>
                            </svg>

                        </span>
                    {% endif %}
                </a>
            </li>
        {% endfor %}
    </div>
</div>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\sorting.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 5, "if" => 9];
        static $filters = ["escape" => 4, "default" => 4, "raw" => 44];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape', 'default', 'raw'],
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
