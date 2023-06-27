<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;
use PHPUnit\Framework\TestCase;

class AndSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that specification is satisfied when both inner left and right specifications are satisfied.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\AndSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\AndSpecification::isSatisfied()
     */
    public function testIsSatisfied(): void
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification
            ->expects($this->exactly(3))
            ->method('isSatisfied')
            ->willReturnOnConsecutiveCalls(true, false, true);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification
            ->expects($this->exactly(2))
            ->method('isSatisfied')
            ->willReturnOnConsecutiveCalls(true, false);

        /**
         * @param SpecificationInterface $leftSpecification
         * @param SpecificationInterface $rightSpecification
         */
        $andSpecification = new AndSpecification($leftSpecification, $rightSpecification);

        $this->assertTrue($andSpecification->isSatisfied());
        $this->assertFalse($andSpecification->isSatisfied());
        $this->assertFalse($andSpecification->isSatisfied());
    }
}
