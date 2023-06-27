<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Other;

use PHPUnit\Framework\TestCase;

class InArraySpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that specification is satisfied when value is in inner array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Other\InArraySpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Other\InArraySpecification::isSatisfied()
     */
    public function testIsSatisfied(): void
    {
        $this->assertTrue((new InArraySpecification(['1', 2], '1', true))->isSatisfied());
        $this->assertTrue((new InArraySpecification(['1', 2], 2, true))->isSatisfied());
        $this->assertFalse((new InArraySpecification(['1', 2], '2', true))->isSatisfied());
    }
}
