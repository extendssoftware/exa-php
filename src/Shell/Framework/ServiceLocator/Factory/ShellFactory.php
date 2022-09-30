<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionException;
use ExtendsSoftware\ExaPHP\Shell\ShellBuilder;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;

class ShellFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws DefinitionException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getConfig();
        $config = $config[ShellInterface::class] ?? [];

        $builder = (new ShellBuilder())
            ->setName($config['name'] ?? null)
            ->setProgram($config['program'] ?? null)
            ->setVersion($config['version'] ?? null);

        foreach ($config['commands'] ?? [] as $command) {
            $builder->addCommand(
                $command['name'],
                $command['description'],
                $command['operands'] ?? [],
                $command['options'] ?? [],
                $command['parameters'] ?? []
            );
        }

        return $builder->build();
    }
}
