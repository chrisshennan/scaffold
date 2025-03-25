<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Twig;

use Scaffold\CoreBundle\Helper\ScaffoldContext;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ScaffoldExtension extends AbstractExtension
{
    public function __construct(
        private readonly ScaffoldContext $context,
        private readonly Environment $twig,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('scaffold_site_name', [$this, 'siteName']),
            new TwigFunction('scaffold_site_strapline', [$this, 'siteStrapline']),
        ];
    }

    public function siteName(): string
    {
        return $this->context->get('site_name') ?? $this->twig->getGlobals()['scaffold_site_name_default'];
    }

    public function siteStrapline(): string
    {
        return $this->context->get('strapline') ?? $this->twig->getGlobals()['scaffold_site_strapline_default'];
    }
}
