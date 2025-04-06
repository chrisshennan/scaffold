<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin', methods: ['GET'])]
    public function admin(): Response
    {
        return $this->render('@ScaffoldCore/admin/index.html.twig');
    }
}
