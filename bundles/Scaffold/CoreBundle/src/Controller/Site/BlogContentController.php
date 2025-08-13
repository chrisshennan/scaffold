<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller\Site;

use App\Entity\BlogContent;
use App\Repository\BlogContentRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogContentController extends AbstractController
{
    #[Route('/blog', name: 'scaffold_core_site_blogcontent_index', methods: ['GET'])]
    public function index(BlogContentRepository $repository): Response
    {
        $posts = $repository->findPublished();

        return $this->render('@ScaffoldCore/site/blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/{post}', name: 'scaffold_core_site_blogcontent_show', methods: ['GET'])]
    public function show(
        #[MapEntity(mapping: ['post' => 'slug'])]
        BlogContent $post,
    ): Response {
        if ($post->isPublished() === false) {
            throw $this->createNotFoundException('Blog post not found');
        }

        return $this->render('@ScaffoldCore/site/blog/show.html.twig', [
            'post' => $post,
        ]);
    }
}
