<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Factory\Logger;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\Logger;
use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class LoggerFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string $class,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null,
    ): LoggerInterface {
        $config = $serviceLocator
            ->getContainer()
            ->find(LoggerInterface::class, []);
        $logger = new Logger();

        foreach ($config['writers'] ?? [] as $writer) {
            /** @var class-string<WriterInterface> $name */
            $name = $writer['name'];
            $interrupt = $writer['options']['interrupt'] ?? null;

            $writer = $serviceLocator->getService($name, $writer['options'] ?? []);
            $logger->addWriter($writer, $interrupt);
        }

        foreach ($config['decorators'] ?? [] as $decorator) {
            /** @var class-string<DecoratorInterface> $name */
            $name = $decorator['name'];

            $decorator = $serviceLocator->getService($name, $decorator['options'] ?? []);
            $logger->addDecorator($decorator);
        }

        return $logger;
    }
}
