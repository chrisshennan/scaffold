<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller;

use Scaffold\CoreBundle\DTO\Config\TermsAndConditionsConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DemoController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('@ScaffoldCore/demo/index.html.twig');
    }
}
