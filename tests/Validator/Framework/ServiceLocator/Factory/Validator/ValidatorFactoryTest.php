<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Framework\ServiceLocator\Factory\Validator;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatorFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Framework\ServiceLocator\Factory\Validator\ValidatorFactory::createService()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ValidatorInterface::class, [])
            ->willReturn($this->createMock(ValidatorInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new ValidatorFactory();
        $validator = $factory->createService(ValidatorInterface::class, $serviceLocator, [
            'validators' => [
                [
                    'name' => ValidatorInterface::class,
                ],
            ],
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
