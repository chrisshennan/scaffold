<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Helper\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Helper\TestHelper
 *
 * @internal
 */
class TestHelperTest extends TestCase
{
    /**
     * @covers ::stub
     */
    public function testStub(): void
    {
        $helper = new TestHelper();
        self::assertEquals(2, $helper->stub());
    }
}