<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Twig;

use App\Entity\Author;
use Scaffold\CoreBundle\Helper\ScaffoldParsedown;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ScaffoldContentExtension extends AbstractExtension
{
    public function __construct(
        private readonly ScaffoldParsedown $parsedown,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('scaffold_markdown_parse', $this->markdownToHtml(...), ['is_safe' => ['html']]),
            new TwigFunction('scaffold_generate_image', $this->generateImage(...)),

            new TwigFunction('scaffold_author_pic', $this->generateAuthorPicture(...)),
        ];
    }

    public function markdownToHtml(string $markdownContent): string
    {
        return $this->parsedown->parse($markdownContent);
    }

    public function generateImage(?string $imagePath): string
    {
        // @todo: Move this to an image generation service.

        if ($imagePath === null) {
            $imagePath = '/images/default/blog-thumbnail.jpg';
        }

        if (file_exists($imagePath) === false) {
            // If the image does not exist, return a default image path
            $imagePath = '/images/default/blog-thumbnail-16x9.jpg';
        }

        // Default image URL if none is provided
        return $imagePath;
    }

    public function generateAuthorPicture(Author $author, int $width = 250): string
    {
        return 'https://www.gravatar.com/avatar/'.md5($author->getEmail()).'?s='.$width;
    }

    public function cloudinaryImage($text, $type, $url)
    {
        if (empty($url)) {
            $url = 'https://res.cloudinary.com/chrisshennan/image/upload/v1652343022/developer-generic_rwjklj.jpg';
        } else {
            $url = 'https://chrisshennan.com'.$url;
        }

        return $this->cloudinaryManager
            ->setText($text)
            ->setType($type)
            ->setUrl($url)
            ->generateUrl()
        ;
    }
}
