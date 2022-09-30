<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception;

use PHPUnit\Framework\TestCase;

class LinkNotFoundTest extends TestCase
{
    /**
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound::getRel()
     */
    public function testGetMethods(): void
    {
        $exception = new LinkNotFound('comments');

        $this->assertSame('Link with rel "comments" does not exists.', $exception->getMessage());
        $this->assertSame('comments', $exception->getRel());
    }
}
