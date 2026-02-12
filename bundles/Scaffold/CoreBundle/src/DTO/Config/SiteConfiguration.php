<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DTO\Config;

class SiteConfiguration
{
    public function __construct(
        private(set) string $name,
        private(set) string $strapline,
    ) {}
}
