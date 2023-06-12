<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;
use PHPUnit\Framework\TestCase;

class OrSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that specification is satisfied when both inner left and right specifications are satisfied.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\OrSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\OrSpecification::isSatisfied()
     */
    public function testIsSatisfied(): void
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification
            ->expects($this->exactly(3))
            ->method('isSatisfied')
            ->with('foo')
            ->willReturnOnConsecutiveCalls(true, false, false);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification
            ->expects($this->exactly(2))
            ->method('isSatisfied')
            ->with('foo')
            ->willReturnOnConsecutiveCalls(true, false);

        /**
         * @param SpecificationInterface $leftSpecification
         * @param SpecificationInterface $rightSpecification
         */
        $orSpecification = new OrSpecification($leftSpecification, $rightSpecification);

        $this->assertTrue($orSpecification->isSatisfied('foo'));
        $this->assertTrue($orSpecification->isSatisfied('foo'));
        $this->assertFalse($orSpecification->isSatisfied('foo'));
    }
}
