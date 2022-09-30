<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception;

use PHPUnit\Framework\TestCase;

class AttributeNotFoundTest extends TestCase
{
    /**
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound::getProperty()
     */
    public function testGetMethods(): void
    {
        $exception = new AttributeNotFound('comments');

        $this->assertSame('Attribute with property "comments" does not exists.', $exception->getMessage());
        $this->assertSame('comments', $exception->getProperty());
    }
}
