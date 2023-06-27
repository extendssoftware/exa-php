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
        $this->assertTrue((new GreaterThanSpecification(2, '1'))->isSatisfied());
        $this->assertFalse((new GreaterThanSpecification('1', '1'))->isSatisfied());
        $this->assertFalse((new GreaterThanSpecification(0, '1'))->isSatisfied());
    }
}
