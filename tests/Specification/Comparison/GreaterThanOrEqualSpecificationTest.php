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
        $this->assertTrue((new GreaterThanOrEqualToSpecification('1', 1))->isSatisfied());
        $this->assertTrue((new GreaterThanOrEqualToSpecification('2', '1'))->isSatisfied());
        $this->assertFalse((new GreaterThanOrEqualToSpecification(0, '1'))->isSatisfied());
    }
}
