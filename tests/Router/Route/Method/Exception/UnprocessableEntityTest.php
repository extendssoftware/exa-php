<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Method\Exception;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class UnprocessableEntityTest extends TestCase
{
    /**
     * Get result.
     *
     * Test that correct result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\InvalidRequestBody::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\InvalidRequestBody::getResult()
     */
    public function testGetResult(): void
    {
        $result = $this->createMock(ResultInterface::class);

        /**
         * @var ResultInterface $result
         */
        $exception = new InvalidRequestBody($result);

        $this->assertSame($result, $exception->getResult());
    }
}
