<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller\Site;

use App\Repository\BlogContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BlogContentComponentController extends AbstractController
{
    public function sidePanel(BlogContentRepository $repository, int $limit = 5): Response
    {
        $posts = $repository->findPublished(limit: $limit);

        return $this->render('@ScaffoldCore/site/blog/components/sidePanel.html.twig', [
            'posts' => $posts,
        ]);
    }
}
