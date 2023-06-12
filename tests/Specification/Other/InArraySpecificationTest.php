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
        $inArraySpecification = new InArraySpecification(['1', 2], true);

        $this->assertTrue($inArraySpecification->isSatisfied('1'));
        $this->assertTrue($inArraySpecification->isSatisfied(2));
        $this->assertFalse($inArraySpecification->isSatisfied('2'));
    }
}
