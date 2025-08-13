<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Controller\Site;

use RuntimeException;
use Scaffold\CoreBundle\Helper\ScaffoldParsedown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/site/markdown-textarea')]
class MarkdownTextareaController extends AbstractController
{
    #[Route('/image/upload', 'app_site_markdown_textrea_image_upload', methods: ['POST'])]
    public function post(
        Request $request,
        #[Autowire(env: 'SCAFFOLD_PUBLIC_DIRECTORY')]
        string $publicDirectory,
    ): JsonResponse {
        // Extract JSON body from the request object
        $json = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $image = $json['image'];

        if (!$image) {
            return $this->json([
                'error' => 'Missing required parameters',
            ], 400);
        }

        // Remove the base64 header from the image
        $image = preg_replace("/data:image\/png;base64,/", '', $image);

        $name = new UuidV7()->toString();

        // Save image to filesystem as a jpeg
        $path = $publicDirectory.'/uploads/images/'.$name.'.jpg';
        $concurrentDirectory = dirname($path);
        if (!is_dir($concurrentDirectory)) {
            // Create the directory if it does not exist
            $concurrentDirectory = rtrim($concurrentDirectory, '/');
            if (!mkdir($concurrentDirectory, 0777, true) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
        }

        file_put_contents($path, base64_decode($image));

        // Return the relative URL of the file
        $url = '/uploads/images/'.$name.'.jpg';

        return $this->json([
            'url' => $url,
        ]);
    }

    #[Route('/preview', 'app_site_markdown_textarea_preview_upload', methods: ['POST'])]
    public function preview(Request $request, ScaffoldParsedown $parsedown): JsonResponse
    {
        $json = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $markdown = $json['markdown'] ?? '';

        $parsedown = $parsedown->parse($markdown);

        $json = [
            'markdown' => $markdown,
            'html' => $parsedown,
        ];

        return new JsonResponse($json);
    }
}
