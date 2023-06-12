<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use PHPUnit\Framework\TestCase;

class GreaterThanSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that given value is greater than constructor value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\GreaterThanSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\GreaterThanSpecification::isSatisfied()
     *
     * @return void
     */
    public function testIsSatisfied(): void
    {
        $specification = new GreaterThanSpecification('1');

        $this->assertTrue($specification->isSatisfied(2));
        $this->assertFalse($specification->isSatisfied('1'));
        $this->assertFalse($specification->isSatisfied(0));
    }
}
