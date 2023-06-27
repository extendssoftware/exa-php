<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;
use PHPUnit\Framework\TestCase;

class XorSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that specification is satisfied when both inner left and right specifications are satisfied.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\XorSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\XorSpecification::isSatisfied()
     */
    public function testIsSatisfied(): void
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification
            ->expects($this->exactly(4))
            ->method('isSatisfied')
            ->with('foo')
            ->willReturnOnConsecutiveCalls(true, false, true, false);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification
            ->expects($this->exactly(4))
            ->method('isSatisfied')
            ->with('foo')
            ->willReturnOnConsecutiveCalls(true, false, false, true);

        /**
         * @param SpecificationInterface $leftSpecification
         * @param SpecificationInterface $rightSpecification
         */
        $orSpecification = new XorSpecification($leftSpecification, $rightSpecification);

        $this->assertFalse($orSpecification->isSatisfied('foo'));
        $this->assertFalse($orSpecification->isSatisfied('foo'));
        $this->assertTrue($orSpecification->isSatisfied('foo'));
        $this->assertTrue($orSpecification->isSatisfied('foo'));
    }
}
