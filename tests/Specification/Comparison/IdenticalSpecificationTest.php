<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use PHPUnit\Framework\TestCase;

class IdenticalSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that values are identical.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\IdenticalSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\IdenticalSpecification::isSatisfied()
     *
     * @return void
     */
    public function testIsSatisfied(): void
    {
        $specification = new IdenticalSpecification('1');

        $this->assertTrue($specification->isSatisfied('1'));
        $this->assertFalse($specification->isSatisfied(1));
    }
}
