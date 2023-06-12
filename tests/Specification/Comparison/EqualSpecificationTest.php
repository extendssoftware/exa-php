<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use PHPUnit\Framework\TestCase;

class EqualSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that values are equal.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\EqualSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\EqualSpecification::isSatisfied()
     *
     * @return void
     */
    public function testIsSatisfied(): void
    {
        $specification = new EqualSpecification('1');

        $this->assertTrue($specification->isSatisfied(1));
        $this->assertFalse($specification->isSatisfied('2'));
    }
}
