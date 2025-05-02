<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Scaffold\CoreBundle\Entity\WaitlistEntry;
use Scaffold\CoreBundle\Form\Waitlist\WaitlistEntryEmbedType;
use Scaffold\CoreBundle\Repository\WaitlistEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Throwable;

class WaitlistController extends AbstractController
{
    public function embed(): Response
    {
        $waitlistEntry = new WaitlistEntry();
        $form = $this->createForm(WaitlistEntryEmbedType::class, $waitlistEntry);

        return $this->render('@ScaffoldCore/waitlistEntry/embed.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsCsrfTokenValid(WaitlistEntry::class, '_token')]
    #[Route('/waitlist/embed-ajax', name: 'scaffold_core_waitlist_embedajax', methods: ['POST'], format: 'json')]
    public function embedAjax(
        Request $request,
        WaitlistEntryRepository $waitlistEntryRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $waitlistEntry = new WaitlistEntry();
        $form = $this->createForm(WaitlistEntryEmbedType::class, $waitlistEntry);

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $existing = $waitlistEntryRepository->findOneBy(['email' => $data['email']]);
        if ($existing !== null) {
            return new JsonResponse(['success' => true], Response::HTTP_OK);
        }

        // Submit manually since it's a JSON request
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $waitlistEntry->setSource('website');
            try {
                $entityManager->persist($waitlistEntry);
                $entityManager->flush();
            } catch (Throwable $e) {
                // Handle the exception (e.g., log it, return an error response)
                return new JsonResponse(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new JsonResponse(['success' => true], Response::HTTP_OK);
        }

        return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
    }
}
