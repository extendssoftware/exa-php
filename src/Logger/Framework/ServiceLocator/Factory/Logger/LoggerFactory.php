<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Factory\Logger;

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
        string                  $class,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): LoggerInterface {
        $config = $serviceLocator->getContainer()->get(LoggerInterface::class, []);
        $logger = new Logger();
        foreach ($config['writers'] ?? [] as $config) {
            $writer = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($writer instanceof WriterInterface) {
                $logger->addWriter($writer);
            }
        }

        return $logger;
    }
}
