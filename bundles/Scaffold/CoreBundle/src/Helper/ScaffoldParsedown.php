<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Helper;

use Parsedown;
use Scaffold\CoreBundle\Model\PageLink;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ScaffoldParsedown extends Parsedown
{
    /**
     * @var PageLink[]
     */
    protected array $headerSections = [];

    protected AsciiSlugger $slugger;

    public function __construct(
        private readonly AssetMapperInterface $assetMapper,
    ) {
        $this->slugger = new AsciiSlugger();
    }

    /**
     * @param array{
     *     body: string,
     *     indent: int,
     *     text: string,
     * } $Line
     *
     * @return array{
     *   element: array{
     *     name: 'h1|h2'|'h3'|'h4'|'h5'|'h6',
     *     text: string,
     *     handler: string
     *   }
     * }
     */
    protected function blockHeader($Line): array
    {
        $block = parent::blockHeader($Line);

        if (!in_array($block['element']['name'], ['h2', 'h3'])) {
            return $block;
        }

        if (isset($block['element']['text'])) {
            $text = $block['element']['text'];

            // Generate slug using Symfony's AsciiSlugger
            $slug = $this->slugger->slug(strip_tags($text))->lower()->toString();

            // Add the ID attribute
            $block['element']['attributes'] = ['id' => $slug];

            if ($block['element']['name'] === 'h3') {
                $last = end($this->headerSections);
                $last->children[] = new PageLink($text, '#'.$slug);
            } else {
                $this->headerSections[] = new PageLink($text, '#'.$slug);
            }
        }

        return $block;
    }

    /**
     * @return PageLink[]
     */
    public function getHeaderSections(): array
    {
        return $this->headerSections;
    }

    /**
     * Parsedown calls this every time it finds `![alt](path)` in the markdown.
     *
     * @param array{
     *   text: string,
     *   content: string
     * } $Excerpt
     *
     * @return ?array{
     *   extent: int,
     *   element: array{
     *     name: string,
     *     attributes: array{
     *       src: string,
     *       alt: string,
     *       title: string|null
     *     }
     *   }
     * }
     */
    protected function inlineImage($Excerpt): ?array
    {
        $image = parent::inlineImage($Excerpt);

        if ($image === null) {
            return null;
        }

        if ($image && isset($image['element']['attributes']['src'])) {
            // @todo - replace `/uploads/` with a constant or configuration value.
            if (str_starts_with($image['element']['attributes']['src'], '/uploads/')) {
                // Keep user uploads as-is, e.g. /uploads/images/duck‑3c16d92m.png
                return $image;
            }

            if (preg_match('#^(?:https?:)?//#', $image['element']['attributes']['src']) === 1) {
                // Keep absolute URLs as-is, e.g. https://example.com/assets/images/duck‑3c16d92m.png
                return $image;
            }
        }

        // For relative paths, we assume they are in the public assets directory.
        // e.g. /assets/images/duck‑3c16d92m.png
        $logicalPath = ltrim($image['element']['attributes']['src'], '/');
        $image['element']['attributes']['src'] = $this->assetMapper->getPublicPath($logicalPath);

        return $image;
    }
}
