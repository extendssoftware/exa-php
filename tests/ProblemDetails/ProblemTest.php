<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails;

use PHPUnit\Framework\TestCase;

class ProblemTest extends TestCase
{
    /**
     * Getters.
     *
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::getType()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::getTitle()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::getDetail()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::getStatus()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::getInstance()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails::getAdditional()
     */
    public function testGetters(): void
    {
        $problem = new ProblemDetails(
            '/foo/type',
            'Problem title',
            'Problem detail',
            400,
            '/foo/instance',
            [
                'foo' => 'bar',
            ]
        );

        $this->assertSame('/foo/type', $problem->getType());
        $this->assertSame('Problem title', $problem->getTitle());
        $this->assertSame('Problem detail', $problem->getDetail());
        $this->assertSame(400, $problem->getStatus());
        $this->assertSame('/foo/instance', $problem->getInstance());
        $this->assertSame(['foo' => 'bar'], $problem->getAdditional());
    }
}
