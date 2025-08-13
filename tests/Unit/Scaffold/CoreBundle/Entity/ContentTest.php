<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scaffold\CoreBundle\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Scaffold\CoreBundle\Entity\Content;

/**
 * @internal
 */
#[CoversClass(Content::class)]
class ContentTest extends TestCase
{
    public function testAccessors(): void
    {
        $contentObj = new class extends Content {};

        $title = '... title ...';
        self::assertSame($title, $contentObj->setTitle($title)->getTitle());

        $summary = '... summary ...';
        self::assertSame($summary, $contentObj->setSummary($summary)->getSummary());

        $content = '... content ...';
        self::assertSame($content, $contentObj->setContent($content)->getContent());
    }
}
