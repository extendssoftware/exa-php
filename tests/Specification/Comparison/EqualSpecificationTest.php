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
        $this->assertTrue((new EqualSpecification('1', 1))->isSatisfied());
        $this->assertFalse((new EqualSpecification('1', '2'))->isSatisfied());
    }
}
