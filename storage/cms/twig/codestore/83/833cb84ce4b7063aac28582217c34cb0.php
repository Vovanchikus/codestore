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
        $context["currentSort"] = get("sort", ((array_key_exists("currentSort", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["currentSort"] ?? null), 5, $this->source), null)) : (null)));
        // line 6
        yield "        ";
        $context["currentDir"] = get("direction", ((array_key_exists("currentDir", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["currentDir"] ?? null), 6, $this->source), "desc")) : ("desc")));
        // line 7
        yield "
        ";
        // line 8
        $context["sortingItems"] = ((array_key_exists("sortingItems", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["sortingItems"] ?? null), 8, $this->source), [])) : ([]));
        // line 9
        yield "        ";
        $context["requestedSort"] = get("sort", ((array_key_exists("requestedSort", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["requestedSort"] ?? null), 9, $this->source), null)) : (null)));
        // line 10
        yield "        ";
        $context["uiSort"] = ((($context["requestedSort"] ?? null)) ? ($context["requestedSort"]) : (($context["currentSort"] ?? null)));
        // line 11
        yield "
        ";
        // line 12
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["sortingItems"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 13
            yield "            ";
            $context["isActive"] = ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "isActive", [], "any", true, true, true, 13)) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "isActive", [], "any", false, false, true, 13)) : ((($context["uiSort"] ?? null) && (((($context["uiSort"] ?? null) == ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "code", [], "any", true, true, true, 13)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "code", [], "any", false, false, true, 13), 13, $this->source), "")) : (""))) || (($context["uiSort"] ?? null) == ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", true, true, true, 13)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", false, false, true, 13), 13, $this->source), "")) : ("")))) || (($context["uiSort"] ?? null) == ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", true, true, true, 13)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 13), 13, $this->source), "")) : ("")))))));
            // line 14
            yield "            ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "type", [], "any", false, false, true, 14) == "group")) {
                // line 15
                yield "                ";
                if ((($tmp = ($context["isActive"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 16
                    yield "                    ";
                    if (CoreExtension::matches("/_desc\$/", ($context["uiSort"] ?? null))) {
                        // line 17
                        yield "                        ";
                        $context["target"] = Twig\Extension\CoreExtension::replace($this->sandbox->ensureToStringAllowed(($context["uiSort"] ?? null), 17, $this->source), ["_desc" => "_asc"]);
                        // line 18
                        yield "                        ";
                        $context["dirClass"] = "desc";
                        // line 19
                        yield "                    ";
                    } elseif (CoreExtension::matches("/_asc\$/", ($context["uiSort"] ?? null))) {
                        // line 20
                        yield "                        ";
                        $context["target"] = Twig\Extension\CoreExtension::replace($this->sandbox->ensureToStringAllowed(($context["uiSort"] ?? null), 20, $this->source), ["_asc" => "_desc"]);
                        // line 21
                        yield "                        ";
                        $context["dirClass"] = "asc";
                        // line 22
                        yield "                    ";
                    } else {
                        // line 23
                        yield "                        ";
                        $context["target"] = ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 23)) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 23)) : (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", false, false, true, 23)));
                        // line 24
                        yield "                        ";
                        $context["dirClass"] = ((array_key_exists("currentDir", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["currentDir"] ?? null), 24, $this->source), "desc")) : ("desc"));
                        // line 25
                        yield "                    ";
                    }
                    // line 26
                    yield "                ";
                } else {
                    // line 27
                    yield "                    ";
                    $context["target"] = ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 27)) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 27)) : (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", false, false, true, 27)));
                    // line 28
                    yield "                    ";
                    $context["dirClass"] = ((CoreExtension::matches("/_desc\$/", CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 28))) ? ("desc") : (((CoreExtension::matches("/_asc\$/", CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", false, false, true, 28))) ? ("asc") : (((array_key_exists("currentDir", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["currentDir"] ?? null), 28, $this->source), "desc")) : ("desc"))))));
                    // line 29
                    yield "                ";
                }
                // line 30
                yield "            ";
            } else {
                // line 31
                yield "                ";
                $context["target"] = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "code", [], "any", false, false, true, 31);
                // line 32
                yield "                ";
                $context["dirClass"] = ((CoreExtension::matches("/_asc\$/", ($context["target"] ?? null))) ? ("asc") : (((CoreExtension::matches("/_desc\$/", ($context["target"] ?? null))) ? ("desc") : (((array_key_exists("currentDir", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["currentDir"] ?? null), 32, $this->source), "desc")) : ("desc"))))));
                // line 33
                yield "            ";
            }
            // line 34
            yield "
            <li>
                <a href=\"?sort=";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["target"] ?? null), 36, $this->source), "html", null, true);
            yield "\"
                   class=\"catalog__sort-link ";
            // line 37
            yield (((($tmp = ($context["isActive"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("active") : (""));
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["dirClass"] ?? null), 37, $this->source), "html", null, true);
            yield "\"
                   ";
            // line 38
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "type", [], "any", false, false, true, 38) == "group")) {
                yield "data-asc=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", true, true, true, 38)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "asc", [], "any", false, false, true, 38), 38, $this->source), "")) : ("")), "html", null, true);
                yield "\" data-desc=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", true, true, true, 38)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "desc", [], "any", false, false, true, 38), 38, $this->source), "")) : ("")), "html", null, true);
                yield "\"";
            } else {
                yield "data-sort=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "code", [], "any", true, true, true, 38)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "code", [], "any", false, false, true, 38), 38, $this->source), "")) : ("")), "html", null, true);
                yield "\"";
            }
            // line 39
            yield "                   ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "tooltip", [], "any", false, false, true, 39)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield "data-tooltip=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "tooltip", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
                yield "\"";
            }
            yield ">
                    <span class=\"catalog__sort-menu-icon\">

                        ";
            // line 42
            if (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "field", [], "any", false, false, true, 42) == "date") || CoreExtension::matches("/date|published|date_desc|date_asc/", CoreExtension::getAttribute($this->env, $this->source, $context["item"], "field", [], "any", false, false, true, 42)))) {
                // line 43
                yield "                            ";
                // line 44
                yield "                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path d=\"M6.25 13.25C6.25 12.6977 6.69772 12.25 7.25 12.25C7.80228 12.25 8.2501 12.6977 8.2501 13.25C8.2501 13.8023 7.80238 14.25 7.2501 14.25C6.69782 14.25 6.25 13.8023 6.25 13.25Z\" fill=\"currentColor\"/>
                                <path d=\"M11.75 12.25C11.1977 12.25 10.75 12.6977 10.75 13.25C10.75 13.8023 11.1977 14.25 11.75 14.25C12.3023 14.25 12.7501 13.8023 12.7501 13.25C12.7501 12.6977 12.3023 12.25 11.75 12.25Z\" fill=\"currentColor\"/>
                                <path d=\"M15.25 13.25C15.25 12.6977 15.6977 12.25 16.25 12.25C16.8023 12.25 17.2501 12.6977 17.2501 13.25C17.2501 13.8023 16.8024 14.25 16.2501 14.25C15.6978 14.25 15.25 13.8023 15.25 13.25Z\" fill=\"currentColor\"/>
                                <path d=\"M7.25 16.25C6.69772 16.25 6.25 16.6977 6.25 17.25C6.25 17.8023 6.69772 18.25 7.25 18.25C7.80228 18.25 8.2501 17.8023 8.2501 17.25C8.2501 16.6977 7.80228 16.25 7.25 16.25Z\" fill=\"currentColor\"/>
                                <path d=\"M10.75 17.25C10.75 16.6977 11.1977 16.25 11.75 16.25C12.3023 16.25 12.7501 16.6977 12.7501 17.25C12.7501 17.8023 12.3024 18.25 11.7501 18.25C11.1978 18.25 10.75 17.8023 10.75 17.25Z\" fill=\"currentColor\"/>
                                <path d=\"M16.25 16.25C15.6977 16.25 15.25 16.6977 15.25 17.25C15.25 17.8023 15.6977 18.25 16.25 18.25C16.8023 18.25 17.2501 17.8023 17.2501 17.25C17.2501 16.6977 16.8023 16.25 16.25 16.25Z\" fill=\"currentColor\"/>
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.5 0.75C8.5 0.335786 8.16421 0 7.75 0C7.33579 0 7 0.335786 7 0.75V2.00007C6.19395 2.00064 5.53458 2.00569 4.99013 2.05018C4.36012 2.10165 3.81824 2.20963 3.32054 2.46322C2.52085 2.87068 1.87068 3.52085 1.46322 4.32054C1.20963 4.81824 1.10165 5.36012 1.05018 5.99013C0.999989 6.60439 0.999994 7.36493 1 8.31737V17.1826C0.999994 18.135 0.999989 18.8956 1.05018 19.5099C1.10165 20.1399 1.20963 20.6818 1.46322 21.1795C1.87068 21.9791 2.52085 22.6293 3.32054 23.0368C3.81824 23.2904 4.36012 23.3983 4.99013 23.4498C5.60438 23.5 6.3649 23.5 7.3173 23.5H16.1826C17.135 23.5 17.8956 23.5 18.5099 23.4498C19.1399 23.3983 19.6818 23.2904 20.1795 23.0368C20.9791 22.6293 21.6293 21.9791 22.0368 21.1795C22.2904 20.6818 22.3983 20.1399 22.4498 19.5099C22.5 18.8956 22.5 18.1351 22.5 17.1827V8.31737C22.5 7.36496 22.5 6.60438 22.4498 5.99013C22.3983 5.36012 22.2904 4.81824 22.0368 4.32054C21.6293 3.52085 20.9791 2.87068 20.1795 2.46322C19.6818 2.20963 19.1399 2.10165 18.5099 2.05018C17.9654 2.00569 17.3061 2.00064 16.5 2.00007V0.75C16.5 0.335786 16.1642 0 15.75 0C15.3358 0 15 0.335786 15 0.75V2H8.5V0.75ZM16.15 3.5C17.1425 3.5 17.8417 3.50058 18.3877 3.54519C18.925 3.58909 19.2475 3.67184 19.4985 3.79973C20.0159 4.06338 20.4366 4.48408 20.7003 5.00153C20.8282 5.25252 20.9109 5.57503 20.9548 6.11228C20.9855 6.4874 20.9953 6.93484 20.9985 7.5H2.5015C2.50468 6.93484 2.51455 6.4874 2.54519 6.11228C2.58909 5.57503 2.67184 5.25252 2.79973 5.00153C3.06338 4.48408 3.48408 4.06338 4.00153 3.79973C4.25252 3.67184 4.57503 3.58909 5.11228 3.54519C5.65829 3.50058 6.35753 3.5 7.35 3.5H16.15ZM2.5 9V17.15C2.5 18.1425 2.50058 18.8417 2.54519 19.3877C2.58909 19.925 2.67184 20.2475 2.79973 20.4985C3.06338 21.0159 3.48408 21.4366 4.00153 21.7003C4.25252 21.8282 4.57503 21.9109 5.11228 21.9548C5.65829 21.9994 6.35753 22 7.35 22H16.15C17.1425 22 17.8417 21.9994 18.3877 21.9548C18.925 21.9109 19.2475 21.8282 19.4985 21.7003C20.0159 21.4366 20.4366 21.0159 20.7003 20.4985C20.8282 20.2475 20.9109 19.925 20.9548 19.3877C20.9994 18.8417 21 18.1425 21 17.15V9H2.5Z\" fill=\"currentColor\"/>
                            </svg>

                        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 54
$context["item"], "field", [], "any", false, false, true, 54) == "ratings")) {
                // line 55
                yield "                            ";
                // line 56
                yield "                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M1 9.22438C1 5.4358 3.60795 2 7.64333 2C9.51025 2 10.8558 2.81969 11.75 3.66063C12.6442 2.81969 13.9898 2 15.8567 2C19.8921 2 22.5 5.4358 22.5 9.22438C22.5 13.051 20.1895 16.12 17.8121 18.2436C15.4337 20.3683 12.8463 21.6751 11.9872 21.9615L11.75 22.0406L11.5128 21.9615C10.6537 21.6751 8.06635 20.3683 5.68786 18.2436C3.31046 16.12 1 13.051 1 9.22438ZM7.64333 3.5C4.62317 3.5 2.5 6.0642 2.5 9.22438C2.5 12.4233 4.43954 15.1172 6.68714 17.125C8.71385 18.9354 10.8758 20.0836 11.75 20.4478C12.6242 20.0836 14.7861 18.9354 16.8129 17.125C19.0605 15.1172 21 12.4233 21 9.22438C21 6.0642 18.8768 3.5 15.8567 3.5C14.1441 3.5 13.0103 4.41987 12.3231 5.23383L11.75 5.91259L11.1769 5.23383C10.4897 4.41987 9.35595 3.5 7.64333 3.5Z\" fill=\"currentColor\"/></svg>

                        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 58
$context["item"], "field", [], "any", false, false, true, 58) == "comments")) {
                // line 59
                yield "                            ";
                // line 60
                yield "                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M21.2502 10.2402C21.2502 6.15226 17.6853 2.75004 13.1701 2.75C8.82269 2.75 5.35734 5.90381 5.10562 9.78711C5.84538 9.49669 6.65348 9.33984 7.50015 9.33984C10.9058 9.33992 13.7502 11.9294 13.7502 15.2305C13.7501 16.1247 13.5335 16.972 13.1515 17.7295L13.1701 17.7305L13.2707 17.7363C13.37 17.7498 13.4661 17.7834 13.5529 17.835L17.7433 20.3252L17.7482 20.3281C17.9126 20.4277 18.1402 20.3016 18.1154 20.082L17.7257 16.9219C17.6918 16.6467 17.8122 16.375 18.0392 16.2158C19.9991 14.8421 21.2501 12.6718 21.2502 10.2402ZM22.7502 10.2402C22.7501 13.0486 21.3792 15.5338 19.2668 17.1709L19.6046 19.8984V19.9004C19.7777 21.3374 18.2308 22.3685 16.9767 21.6143L12.9572 19.2256C12.5379 19.2176 12.1251 19.1882 11.7189 19.1328C11.4474 19.0958 11.2172 18.9141 11.1193 18.6582C11.0214 18.4022 11.0716 18.1131 11.2492 17.9043C11.8813 17.161 12.25 16.2307 12.2502 15.2305C12.2502 12.8517 10.1744 10.8399 7.50015 10.8398C6.48445 10.8398 5.55647 11.1279 4.78922 11.6279C4.57208 11.7694 4.29749 11.7893 4.06265 11.6797C3.82793 11.5701 3.66593 11.347 3.63492 11.0898C3.60025 10.801 3.59 10.5189 3.59 10.2402C3.59 5.22824 7.92532 1.25 13.1701 1.25C18.4148 1.25004 22.7502 5.22826 22.7502 10.2402Z\" fill=\"currentColor\"/></svg>

                        ";
            } else {
                // line 63
                yield "                            ";
                // line 64
                yield "                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M11 12.9393L11 1.75C11 1.33579 11.3358 1 11.75 1C12.1642 1 12.5 1.33579 12.5 1.75L12.5 12.9393L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C12.1397 15.421 11.9489 15.5 11.75 15.5C11.5511 15.5 11.3603 15.421 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11 12.9393Z\" fill=\"currentColor\"/></svg>

                        ";
            }
            // line 67
            yield "
                    </span>
                    ";
            // line 69
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 69), 69, $this->source), "html", null, true);
            yield "
                    <span class=\"catalog__sort-menu-arrow\">
                      <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M7.28033 15.2197C6.98744 14.9268 6.51256 14.9268 6.21967 15.2197C5.92678 15.5126 5.92678 15.9874 6.21967 16.2803L11.2197 21.2803C11.5126 21.5732 11.9874 21.5732 12.2803 21.2803L17.2803 16.2803C17.5732 15.9874 17.5732 15.5126 17.2803 15.2197C16.9874 14.9268 16.5126 14.9268 16.2197 15.2197L12.5 18.9393V2.75C12.5 2.33579 12.1642 2 11.75 2C11.3358 2 11 2.33579 11 2.75V18.9393L7.28033 15.2197Z\" fill=\"currentColor\"/>
                        </svg>
                    </span>
                </a>
            </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 78
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
        return array (  235 => 78,  220 => 69,  216 => 67,  211 => 64,  209 => 63,  204 => 60,  202 => 59,  200 => 58,  196 => 56,  194 => 55,  192 => 54,  180 => 44,  178 => 43,  176 => 42,  165 => 39,  153 => 38,  147 => 37,  143 => 36,  139 => 34,  136 => 33,  133 => 32,  130 => 31,  127 => 30,  124 => 29,  121 => 28,  118 => 27,  115 => 26,  112 => 25,  109 => 24,  106 => 23,  103 => 22,  100 => 21,  97 => 20,  94 => 19,  91 => 18,  88 => 17,  85 => 16,  82 => 15,  79 => 14,  76 => 13,  72 => 12,  69 => 11,  66 => 10,  63 => 9,  61 => 8,  58 => 7,  55 => 6,  53 => 5,  49 => 4,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"catalog__sort\">
    <div class=\"catalog__sort-title\">Сортировать по:</div>

    <div class=\"catalog__sort-menu\" data-current-sort=\"{{ currentSort|default('') }}\">
        {% set currentSort = get('sort', currentSort|default(null)) %}
        {% set currentDir = get('direction', currentDir|default('desc')) %}

        {% set sortingItems = sortingItems|default([]) %}
        {% set requestedSort = get('sort', requestedSort|default(null)) %}
        {% set uiSort = requestedSort ?: currentSort %}

        {% for item in sortingItems %}
            {% set isActive = item.isActive is defined ? item.isActive : (uiSort and (uiSort == (item.code|default('')) or uiSort == (item.asc|default('')) or uiSort == (item.desc|default('')))) %}
            {% if item.type == 'group' %}
                {% if isActive %}
                    {% if uiSort matches '/_desc\$/' %}
                        {% set target = uiSort|replace({'_desc':'_asc'}) %}
                        {% set dirClass = 'desc' %}
                    {% elseif uiSort matches '/_asc\$/' %}
                        {% set target = uiSort|replace({'_asc':'_desc'}) %}
                        {% set dirClass = 'asc' %}
                    {% else %}
                        {% set target = item.desc ?: item.asc %}
                        {% set dirClass = currentDir|default('desc') %}
                    {% endif %}
                {% else %}
                    {% set target = item.desc ?: item.asc %}
                    {% set dirClass = item.desc matches '/_desc\$/' ? 'desc' : (item.asc matches '/_asc\$/' ? 'asc' : (currentDir|default('desc'))) %}
                {% endif %}
            {% else %}
                {% set target = item.code %}
                {% set dirClass = target matches '/_asc\$/' ? 'asc' : (target matches '/_desc\$/' ? 'desc' : (currentDir|default('desc'))) %}
            {% endif %}

            <li>
                <a href=\"?sort={{ target }}\"
                   class=\"catalog__sort-link {{ isActive ? 'active' : '' }} {{ dirClass }}\"
                   {% if item.type == 'group' %}data-asc=\"{{ item.asc|default('') }}\" data-desc=\"{{ item.desc|default('') }}\"{% else %}data-sort=\"{{ item.code|default('') }}\"{% endif %}
                   {% if item.tooltip %}data-tooltip=\"{{ item.tooltip }}\"{% endif %}>
                    <span class=\"catalog__sort-menu-icon\">

                        {% if item.field == 'date' or item.field matches '/date|published|date_desc|date_asc/' %}
                            {# date icon #}
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                <path d=\"M6.25 13.25C6.25 12.6977 6.69772 12.25 7.25 12.25C7.80228 12.25 8.2501 12.6977 8.2501 13.25C8.2501 13.8023 7.80238 14.25 7.2501 14.25C6.69782 14.25 6.25 13.8023 6.25 13.25Z\" fill=\"currentColor\"/>
                                <path d=\"M11.75 12.25C11.1977 12.25 10.75 12.6977 10.75 13.25C10.75 13.8023 11.1977 14.25 11.75 14.25C12.3023 14.25 12.7501 13.8023 12.7501 13.25C12.7501 12.6977 12.3023 12.25 11.75 12.25Z\" fill=\"currentColor\"/>
                                <path d=\"M15.25 13.25C15.25 12.6977 15.6977 12.25 16.25 12.25C16.8023 12.25 17.2501 12.6977 17.2501 13.25C17.2501 13.8023 16.8024 14.25 16.2501 14.25C15.6978 14.25 15.25 13.8023 15.25 13.25Z\" fill=\"currentColor\"/>
                                <path d=\"M7.25 16.25C6.69772 16.25 6.25 16.6977 6.25 17.25C6.25 17.8023 6.69772 18.25 7.25 18.25C7.80228 18.25 8.2501 17.8023 8.2501 17.25C8.2501 16.6977 7.80228 16.25 7.25 16.25Z\" fill=\"currentColor\"/>
                                <path d=\"M10.75 17.25C10.75 16.6977 11.1977 16.25 11.75 16.25C12.3023 16.25 12.7501 16.6977 12.7501 17.25C12.7501 17.8023 12.3024 18.25 11.7501 18.25C11.1978 18.25 10.75 17.8023 10.75 17.25Z\" fill=\"currentColor\"/>
                                <path d=\"M16.25 16.25C15.6977 16.25 15.25 16.6977 15.25 17.25C15.25 17.8023 15.6977 18.25 16.25 18.25C16.8023 18.25 17.2501 17.8023 17.2501 17.25C17.2501 16.6977 16.8023 16.25 16.25 16.25Z\" fill=\"currentColor\"/>
                                <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.5 0.75C8.5 0.335786 8.16421 0 7.75 0C7.33579 0 7 0.335786 7 0.75V2.00007C6.19395 2.00064 5.53458 2.00569 4.99013 2.05018C4.36012 2.10165 3.81824 2.20963 3.32054 2.46322C2.52085 2.87068 1.87068 3.52085 1.46322 4.32054C1.20963 4.81824 1.10165 5.36012 1.05018 5.99013C0.999989 6.60439 0.999994 7.36493 1 8.31737V17.1826C0.999994 18.135 0.999989 18.8956 1.05018 19.5099C1.10165 20.1399 1.20963 20.6818 1.46322 21.1795C1.87068 21.9791 2.52085 22.6293 3.32054 23.0368C3.81824 23.2904 4.36012 23.3983 4.99013 23.4498C5.60438 23.5 6.3649 23.5 7.3173 23.5H16.1826C17.135 23.5 17.8956 23.5 18.5099 23.4498C19.1399 23.3983 19.6818 23.2904 20.1795 23.0368C20.9791 22.6293 21.6293 21.9791 22.0368 21.1795C22.2904 20.6818 22.3983 20.1399 22.4498 19.5099C22.5 18.8956 22.5 18.1351 22.5 17.1827V8.31737C22.5 7.36496 22.5 6.60438 22.4498 5.99013C22.3983 5.36012 22.2904 4.81824 22.0368 4.32054C21.6293 3.52085 20.9791 2.87068 20.1795 2.46322C19.6818 2.20963 19.1399 2.10165 18.5099 2.05018C17.9654 2.00569 17.3061 2.00064 16.5 2.00007V0.75C16.5 0.335786 16.1642 0 15.75 0C15.3358 0 15 0.335786 15 0.75V2H8.5V0.75ZM16.15 3.5C17.1425 3.5 17.8417 3.50058 18.3877 3.54519C18.925 3.58909 19.2475 3.67184 19.4985 3.79973C20.0159 4.06338 20.4366 4.48408 20.7003 5.00153C20.8282 5.25252 20.9109 5.57503 20.9548 6.11228C20.9855 6.4874 20.9953 6.93484 20.9985 7.5H2.5015C2.50468 6.93484 2.51455 6.4874 2.54519 6.11228C2.58909 5.57503 2.67184 5.25252 2.79973 5.00153C3.06338 4.48408 3.48408 4.06338 4.00153 3.79973C4.25252 3.67184 4.57503 3.58909 5.11228 3.54519C5.65829 3.50058 6.35753 3.5 7.35 3.5H16.15ZM2.5 9V17.15C2.5 18.1425 2.50058 18.8417 2.54519 19.3877C2.58909 19.925 2.67184 20.2475 2.79973 20.4985C3.06338 21.0159 3.48408 21.4366 4.00153 21.7003C4.25252 21.8282 4.57503 21.9109 5.11228 21.9548C5.65829 21.9994 6.35753 22 7.35 22H16.15C17.1425 22 17.8417 21.9994 18.3877 21.9548C18.925 21.9109 19.2475 21.8282 19.4985 21.7003C20.0159 21.4366 20.4366 21.0159 20.7003 20.4985C20.8282 20.2475 20.9109 19.925 20.9548 19.3877C20.9994 18.8417 21 18.1425 21 17.15V9H2.5Z\" fill=\"currentColor\"/>
                            </svg>

                        {% elseif item.field == 'ratings' %}
                            {# ratings icon #}
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M1 9.22438C1 5.4358 3.60795 2 7.64333 2C9.51025 2 10.8558 2.81969 11.75 3.66063C12.6442 2.81969 13.9898 2 15.8567 2C19.8921 2 22.5 5.4358 22.5 9.22438C22.5 13.051 20.1895 16.12 17.8121 18.2436C15.4337 20.3683 12.8463 21.6751 11.9872 21.9615L11.75 22.0406L11.5128 21.9615C10.6537 21.6751 8.06635 20.3683 5.68786 18.2436C3.31046 16.12 1 13.051 1 9.22438ZM7.64333 3.5C4.62317 3.5 2.5 6.0642 2.5 9.22438C2.5 12.4233 4.43954 15.1172 6.68714 17.125C8.71385 18.9354 10.8758 20.0836 11.75 20.4478C12.6242 20.0836 14.7861 18.9354 16.8129 17.125C19.0605 15.1172 21 12.4233 21 9.22438C21 6.0642 18.8768 3.5 15.8567 3.5C14.1441 3.5 13.0103 4.41987 12.3231 5.23383L11.75 5.91259L11.1769 5.23383C10.4897 4.41987 9.35595 3.5 7.64333 3.5Z\" fill=\"currentColor\"/></svg>

                        {% elseif item.field == 'comments' %}
                            {# comments icon #}
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M21.2502 10.2402C21.2502 6.15226 17.6853 2.75004 13.1701 2.75C8.82269 2.75 5.35734 5.90381 5.10562 9.78711C5.84538 9.49669 6.65348 9.33984 7.50015 9.33984C10.9058 9.33992 13.7502 11.9294 13.7502 15.2305C13.7501 16.1247 13.5335 16.972 13.1515 17.7295L13.1701 17.7305L13.2707 17.7363C13.37 17.7498 13.4661 17.7834 13.5529 17.835L17.7433 20.3252L17.7482 20.3281C17.9126 20.4277 18.1402 20.3016 18.1154 20.082L17.7257 16.9219C17.6918 16.6467 17.8122 16.375 18.0392 16.2158C19.9991 14.8421 21.2501 12.6718 21.2502 10.2402ZM22.7502 10.2402C22.7501 13.0486 21.3792 15.5338 19.2668 17.1709L19.6046 19.8984V19.9004C19.7777 21.3374 18.2308 22.3685 16.9767 21.6143L12.9572 19.2256C12.5379 19.2176 12.1251 19.1882 11.7189 19.1328C11.4474 19.0958 11.2172 18.9141 11.1193 18.6582C11.0214 18.4022 11.0716 18.1131 11.2492 17.9043C11.8813 17.161 12.25 16.2307 12.2502 15.2305C12.2502 12.8517 10.1744 10.8399 7.50015 10.8398C6.48445 10.8398 5.55647 11.1279 4.78922 11.6279C4.57208 11.7694 4.29749 11.7893 4.06265 11.6797C3.82793 11.5701 3.66593 11.347 3.63492 11.0898C3.60025 10.801 3.59 10.5189 3.59 10.2402C3.59 5.22824 7.92532 1.25 13.1701 1.25C18.4148 1.25004 22.7502 5.22826 22.7502 10.2402Z\" fill=\"currentColor\"/></svg>

                        {% else %}
                            {# fallback icon #}
                            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M11 12.9393L11 1.75C11 1.33579 11.3358 1 11.75 1C12.1642 1 12.5 1.33579 12.5 1.75L12.5 12.9393L16.2197 9.21967C16.5126 8.92678 16.9874 8.92678 17.2803 9.21967C17.5732 9.51256 17.5732 9.98744 17.2803 10.2803L12.2803 15.2803C12.1397 15.421 11.9489 15.5 11.75 15.5C11.5511 15.5 11.3603 15.421 11.2197 15.2803L6.21967 10.2803C5.92678 9.98744 5.92678 9.51256 6.21967 9.21967C6.51256 8.92678 6.98744 8.92678 7.28033 9.21967L11 12.9393Z\" fill=\"currentColor\"/></svg>

                        {% endif %}

                    </span>
                    {{ item.label }}
                    <span class=\"catalog__sort-menu-arrow\">
                      <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M7.28033 15.2197C6.98744 14.9268 6.51256 14.9268 6.21967 15.2197C5.92678 15.5126 5.92678 15.9874 6.21967 16.2803L11.2197 21.2803C11.5126 21.5732 11.9874 21.5732 12.2803 21.2803L17.2803 16.2803C17.5732 15.9874 17.5732 15.5126 17.2803 15.2197C16.9874 14.9268 16.5126 14.9268 16.2197 15.2197L12.5 18.9393V2.75C12.5 2.33579 12.1642 2 11.75 2C11.3358 2 11 2.33579 11 2.75V18.9393L7.28033 15.2197Z\" fill=\"currentColor\"/>
                        </svg>
                    </span>
                </a>
            </li>
        {% endfor %}
    </div>
</div>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\catalog\\sorting.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 5, "for" => 12, "if" => 14];
        static $filters = ["escape" => 4, "default" => 4, "replace" => 17];
        static $functions = ["get" => 5];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'for', 'if'],
                ['escape', 'default', 'replace'],
                ['get'],
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
