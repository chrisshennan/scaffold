<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller\Site\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/site/admin', name: 'app_site_admin_index', methods: ['GET'])]
    public function admin(): Response
    {
        return $this->render('@ScaffoldCore/site/admin/default/index.html.twig');
    }
}
