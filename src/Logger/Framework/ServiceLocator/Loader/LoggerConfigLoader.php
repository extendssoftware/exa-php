<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace\BacktraceDecorator;
use ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter;
use ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware;
use ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Factory\Logger\LoggerFactory;
use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Debug\DebugPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Error\ErrorPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Notice\NoticePriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Warning\WarningPriority;
use ExtendsSoftware\ExaPHP\Logger\Writer\File\FileWriter;
use ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\PdoWriter;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class LoggerConfigLoader implements LoaderInterface
{
    /**
     * Service locator config for Logger component.
     *
     * @return mixed[]
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    LoggerInterface::class => LoggerFactory::class,
                ],
                StaticFactoryResolver::class => [
                    BacktraceDecorator::class => BacktraceDecorator::class,
                    PriorityFilter::class => PriorityFilter::class,
                    AlertPriority::class => AlertPriority::class,
                    CriticalPriority::class => CriticalPriority::class,
                    DebugPriority::class => DebugPriority::class,
                    EmergencyPriority::class => EmergencyPriority::class,
                    ErrorPriority::class => ErrorPriority::class,
                    InformationalPriority::class => InformationalPriority::class,
                    NoticePriority::class => NoticePriority::class,
                    WarningPriority::class => WarningPriority::class,
                    FileWriter::class => FileWriter::class,
                    PdoWriter::class => PdoWriter::class,
                ],
                ReflectionResolver::class => [
                    LoggerMiddleware::class => LoggerMiddleware::class,
                ],
            ],
            LoggerInterface::class => [
                'writers' => [],
            ],
        ];
    }
}
