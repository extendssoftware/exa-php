<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Container;

use ExtendsSoftware\ExaPHP\Validator\Result\Exception\ResultNotValid;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class AbstractContainerResultTest extends TestCase
{
    /**
     * Dummy abstract container result.
     *
     * @var AbstractContainerResult
     */
    private AbstractContainerResult $container;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->container = new class extends AbstractContainerResult {
            /**
             * @inheritDoc
             */
            public function getValue(): mixed
            {
                $this->assertValid();

                return $this->results;
            }

            /**
             * Add result to container.
             *
             * @param ResultInterface $result
             *
             * @return AbstractContainerResult
             */
            public function addResult(ResultInterface $result): AbstractContainerResult
            {
                $this->updateValidFlag($result);

                $this->results[] = $result;

                return $this;
            }
        };
    }

    /**
     * Valid.
     *
     * Test that container is valid when all results are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::updateValidFlag()
     */
    public function testIsValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(3))
            ->method('isValid')
            ->willReturn(true);

        $this->container
            ->addResult($result)
            ->addResult($result)
            ->addResult($result);

        $this->assertTrue($this->container->isValid());
    }

    /**
     * Invalid.
     *
     * Test that container is valid when all results are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::updateValidFlag()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                true,
                false,
            );

        $this->container
            ->addResult($result)
            ->addResult($result)
            ->addResult($result);

        $this->assertFalse($this->container->isValid());
    }

    /**
     * JSON serialize.
     *
     * Test that methods will correct data.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::jsonSerialize()
     */
    public function testJsonSerialize(): void
    {
        $result1 = $this->createMock(ResultInterface::class);
        $result2 = $this->createMock(ResultInterface::class);

        $this->container
            ->addResult($result1)
            ->addResult($result2);

        $this->assertSame([
            $result1,
            $result2,
        ], $this->container->jsonSerialize());
    }

    /**
     * Get value invalid container
     *
     * Tes that method will throw an exception when the container is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult::assertValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Exception\ResultNotValid::__construct()
     */
    public function testGetValueInvalidContainer(): void
    {
        $this->expectException(ResultNotValid::class);
        $this->expectExceptionMessage('Can not get value from an invalid result.');

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->container
            ->addResult($result)
            ->addResult($result);

        $this->container->getValue();
    }
}
