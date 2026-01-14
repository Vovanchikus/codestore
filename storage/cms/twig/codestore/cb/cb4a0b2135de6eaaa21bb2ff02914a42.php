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

/* C:\OSPanel\domains\codestore-new\themes\codestore\partials\global\scripts.htm */
class __TwigTemplate_b9b85f8b1183597842553a8a8f59733e extends Template
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
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        echo '<script data-module="snowboard-manifest" src="http://codestore-new/modules/system/assets/js/build/manifest.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-vendor" src="http://codestore-new/modules/system/assets/js/snowboard/build/snowboard.vendor.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-base" data-base-url="http://codestore-new/" data-asset-url="http://codestore-new/" src="http://codestore-new/modules/system/assets/js/snowboard/build/snowboard.base.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="request" src="http://codestore-new/modules/system/assets/js/snowboard/build/snowboard.request.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="attr" src="http://codestore-new/modules/system/assets/js/snowboard/build/snowboard.data-attr.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="extras" src="http://codestore-new/modules/system/assets/js/snowboard/build/snowboard.extras.js?v=1.2.9"></script>'.PHP_EOL;
        // line 2
        yield "
";
        // line 3
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("modals/modal"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 4
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("modals/modal-search"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 5
        yield "

";
        // line 7
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('js');
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('vite');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('scripts');
        // line 8
        yield "<script src=\"";
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/vendor/swiper/swiper-bundle.min.js");
        yield "\"></script>
<script src=\"";
        // line 9
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/vendor/glightbox/glightbox.min.js");
        yield "\"></script>
<script src=\"";
        // line 10
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/modal.js");
        yield "\"></script>
<script src=\"";
        // line 11
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/scripts.js");
        yield "\"></script>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\global\\scripts.htm";
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
        return array (  84 => 11,  80 => 10,  76 => 9,  71 => 8,  67 => 7,  63 => 5,  59 => 4,  55 => 3,  52 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% snowboard all %}

{% partial 'modals/modal' %}
{% partial 'modals/modal-search' %}


{% scripts %}
<script src=\"{{ 'assets/vendor/swiper/swiper-bundle.min.js'|theme }}\"></script>
<script src=\"{{ 'assets/vendor/glightbox/glightbox.min.js'|theme }}\"></script>
<script src=\"{{ 'assets/js/modal.js'|theme }}\"></script>
<script src=\"{{ 'assets/js/scripts.js'|theme }}\"></script>", "C:\\OSPanel\\domains\\codestore-new\\themes\\codestore\\partials\\global\\scripts.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["snowboard" => 1, "partial" => 3, "scripts" => 7];
        static $filters = ["theme" => 8];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['snowboard', 'partial', 'scripts'],
                ['theme'],
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
