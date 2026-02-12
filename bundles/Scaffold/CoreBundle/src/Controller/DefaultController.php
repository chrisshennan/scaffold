<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller;

use Scaffold\CoreBundle\DTO\Config\TermsAndConditionsConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/privacy-policy', name: 'scaffold_core_bundle_privacy_policy', methods: ['GET'])]
    public function privacyPolicy(): Response {
        return $this->render('@ScaffoldCore/default/privacyPolicy.html.twig');
    }

    #[Route('/terms', name: 'scaffold_core_bundle_terms', methods: ['GET'])]
    public function terms(
        TermsAndConditionsConfiguration $termsAndConditionsConfiguration
    ): Response {
        return $this->render('@ScaffoldCore/default/terms.html.twig');
    }
}
