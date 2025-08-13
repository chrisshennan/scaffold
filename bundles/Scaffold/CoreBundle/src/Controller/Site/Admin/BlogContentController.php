<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller\Site\Admin;

use App\Entity\BlogContent;
use App\Repository\BlogContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Scaffold\CoreBundle\Form\Blog\BlogContentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/site/admin/blog')]
class BlogContentController extends AbstractController
{
    #[Route('/', 'app_site_admin_blog_index', methods: ['GET'])]
    public function index(BlogContentRepository $contentRepository): Response
    {
        return $this->render('@ScaffoldCore/site/admin/blog/index.html.twig', [
            'posts' => $contentRepository->findAll(),
        ]);
    }

    #[Route('/new', 'app_site_admin_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $content = new BlogContent();
        $form = $this->createForm(BlogContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('app_site_admin_blog_index', [], Response::HTTP_FOUND);
        }

        return $this->render('@ScaffoldCore/site/admin/blog/new.html.twig', [
            'post' => $content,
            'form' => $form,
        ]);
    }

    #[Route('/{post}/edit', 'app_site_admin_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogContent $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogContentType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_site_admin_blog_index', [], Response::HTTP_FOUND);
        }

        return $this->render('@ScaffoldCore/site/admin/blog/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{post}', 'app_site_admin_blog_delete', methods: ['POST'])]
    public function delete(Request $request, BlogContent $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_site_admin_blog_index', [], Response::HTTP_FOUND);
    }
}
