<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Shell\Descriptor\DescriptorInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserInterface;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ShellFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an ShellInterface instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Factory\ShellFactory::createService()
     */
    public function testCreateService(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('find')
            ->with(ShellInterface::class)
            ->willReturn(
                [
                    'name' => 'Fancy shell name.',
                    'program' => 'run',
                    'version' => '1.3',
                    'commands' => [
                        [
                            'name' => 'task1',
                            'description' => 'Fancy task 1',
                            'operands' => [
                                [
                                    'name' => 'first_name',
                                ],
                            ],
                            'options' => [
                                [
                                    'name' => 'force',
                                    'description' => 'Force creation.',
                                    'short' => 'f',
                                ],
                            ],
                        ],
                        [
                            'name' => 'task2',
                            'description' => 'Fancy task 2',
                            'operands' => [
                                [
                                    'name' => 'last_name',
                                ],
                            ],
                            'options' => [
                                [
                                    'name' => 'activate',
                                    'description' => 'Activate user.',
                                    'long' => 'activate',
                                ],
                            ],
                        ],
                    ],
                ]
            );

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->method('getContainer')
            ->willReturn($container);

        $serviceLocator
            ->expects($this->exactly(3))
            ->method('getService')
            ->willReturnCallback(fn($class) => match ([$class]) {
                [DescriptorInterface::class] => $this->createMock(DescriptorInterface::class),
                [ParserInterface::class] => $this->createMock(ParserInterface::class),
                [SuggesterInterface::class] => $this->createMock(SuggesterInterface::class)
            });

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new ShellFactory();
        $shell = $factory->createService(ShellInterface::class, $serviceLocator, []);

        $this->assertInstanceOf(ShellInterface::class, $shell);
    }
}
