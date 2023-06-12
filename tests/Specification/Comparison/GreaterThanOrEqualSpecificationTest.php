<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use PHPUnit\Framework\TestCase;

class GreaterThanOrEqualSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that given value is greater than constructor value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\GreaterThanOrEqualToSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\GreaterThanOrEqualToSpecification::isSatisfied()
     *
     * @return void
     */
    public function testIsSatisfied(): void
    {
        $specification = new GreaterThanOrEqualToSpecification('1');

        $this->assertTrue($specification->isSatisfied(1));
        $this->assertTrue($specification->isSatisfied('2'));
        $this->assertFalse($specification->isSatisfied(0));
    }
}
