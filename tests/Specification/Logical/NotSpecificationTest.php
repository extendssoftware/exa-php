<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;
use PHPUnit\Framework\TestCase;

class NotSpecificationTest extends TestCase
{
    /**
     * Is satisfied.
     *
     * Test that specification is satisfied when inner specification is not satisfied.
     *
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\NotSpecification::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Specification\Logical\NotSpecification::isSatisfied()
     */
    public function testIsSatisfied(): void
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $specification
            ->expects($this->exactly(2))
            ->method('isSatisfied')
            ->willReturnOnConsecutiveCalls(true, false);

        /**
         * @param SpecificationInterface $specification
         */
        $notSpecification = new NotSpecification($specification);

        $this->assertFalse($notSpecification->isSatisfied());
        $this->assertTrue($notSpecification->isSatisfied());
    }
}
