<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/scaffold/core', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    #[Route('/privacy-policy', name: 'scaffold_core_bundle_privacy_policy', methods: ['GET'])]
    public function privacyPolicy(
        #[Autowire(env: 'SCAFFOLD_PRIVACY_POLICY_LAST_UPDATED')]
        string $lastUpdated,
        #[Autowire(env: 'SCAFFOLD_PRIVACY_POLICY_CONTACT_EMAIL')]
        string $contactEmail,
    ): Response
    {
        return $this->render('@ScaffoldCore/default/privacyPolicy.html.twig', [
            'last_updated' => $lastUpdated,
            'contact_email' => $contactEmail,
        ]);
    }

    #[Route('/terms', name: 'scaffold_core_bundle_terms', methods: ['GET'])]
    public function terms(
        #[Autowire(env: 'SCAFFOLD_TERMS_OF_SERVICE_EFFECTIVE_FROM')]
        string $effectiveFrom,
        #[Autowire(env: 'SCAFFOLD_TERMS_OF_SERVICE_CONTACT_EMAIL')]
        string $contactEmail,
        #[Autowire(env: 'SCAFFOLD_TERMS_OF_SERVICE_JURISDICTION')]
        string $jurisdiction,
        #[Autowire(env: 'SCAFFOLD_TERMS_OF_SERVICE_PAYMENT_PROVIDER')]
        string $paymentProvider

    ): Response
    {
        return $this->render('@ScaffoldCore/default/terms.html.twig', [
            'effective_from' => $effectiveFrom,
            'contact_email' => $contactEmail,
            'jurisdiction' => $jurisdiction,
            'payment_provider' => $paymentProvider,
        ]);
    }
}
