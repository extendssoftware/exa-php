<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use PHPUnit\Framework\TestCase;

class LessThanOrEqualSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that given value is less than constructor value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\LessThanOrEqualToSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Comparison\LessThanOrEqualToSpecification::isSatisfied()
     *
     * @return void
     */
    public function testIsSatisfied(): void
    {
        $this->assertTrue((new LessThanOrEqualToSpecification(0, '1'))->isSatisfied());
        $this->assertTrue((new LessThanOrEqualToSpecification('1', '1'))->isSatisfied());
        $this->assertFalse((new LessThanOrEqualToSpecification(2, '1'))->isSatisfied());
    }
}
