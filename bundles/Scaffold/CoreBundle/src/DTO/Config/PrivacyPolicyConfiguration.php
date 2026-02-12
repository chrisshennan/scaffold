<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DTO\Config;

use DateTimeImmutable;

class PrivacyPolicyConfiguration
{
    public function __construct(
        private(set) string $lastUpdated,
        private(set) string $contactEmail,
        private(set) string $siteUrl,
    ) {}
}
