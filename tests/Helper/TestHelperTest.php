<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Helper\TestHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TestHelper::class)]
class TestHelperTest extends TestCase
{
    public function testStub(): void
    {
        $helper = new TestHelper();
        self::assertEquals(2, $helper->stub());
    }
}
