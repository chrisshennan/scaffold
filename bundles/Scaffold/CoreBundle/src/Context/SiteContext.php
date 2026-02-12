<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Context;

use Scaffold\CoreBundle\DTO\Config\PrivacyPolicyConfiguration;
use Scaffold\CoreBundle\DTO\Config\SiteConfiguration;
use Scaffold\CoreBundle\DTO\Config\TermsAndConditionsConfiguration;

class SiteContext
{
    public function __construct(
        private(set) SiteConfiguration $site,
        private(set) PrivacyPolicyConfiguration $privacyPolicy,
        private(set) TermsAndConditionsConfiguration $termsAndConditions,
    ) {}
}
