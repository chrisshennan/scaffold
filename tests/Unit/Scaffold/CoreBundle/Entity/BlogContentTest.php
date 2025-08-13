<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scaffold\CoreBundle\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Scaffold\CoreBundle\Entity\BlogContent;

/**
 * @internal
 */
#[CoversClass(BlogContent::class)]
class BlogContentTest extends TestCase
{
    public function testAccessors(): void
    {
        $blogContent = new BlogContent();

        $title = '... title ...';
        self::assertSame($title, $blogContent->setTitle($title)->getTitle());

        $summary = '... summary ...';
        self::assertSame($summary, $blogContent->setSummary($summary)->getSummary());

        $content = '... content ...';
        self::assertSame($content, $blogContent->setContent($content)->getContent());

        $slug = 'content-slug';
        self::assertSame($slug, $blogContent->setSlug($slug)->getSlug());

        $mainImage = 'main-image.jpg';
        self::assertSame($mainImage, $blogContent->setMainImage($mainImage)->getMainImage());

        self::assertFalse($blogContent->isPublished());
        $publishedAt = new DateTimeImmutable('2023-01-01T12:38:12Z');
        self::assertSame($publishedAt, $blogContent->setPublishedAt($publishedAt)->getPublishedAt());
        self::assertTrue($blogContent->isPublished());

        $canonicalUrl = 'https://example.com/canonical-url';
        self::assertSame($canonicalUrl, $blogContent->setCanonicalUrl($canonicalUrl)->getCanonicalUrl());
    }
}
