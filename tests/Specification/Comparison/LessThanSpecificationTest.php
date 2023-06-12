<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use PHPUnit\Framework\TestCase;

class LessThanSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that given value is less than constructor value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\LessThanSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\LessThanSpecification::isSatisfied()
     *
     * @return void
     */
    public function testIsSatisfied(): void
    {
        $specification = new LessThanSpecification('1');

        $this->assertTrue($specification->isSatisfied(0));
        $this->assertFalse($specification->isSatisfied('1'));
        $this->assertFalse($specification->isSatisfied(2));
    }
}
