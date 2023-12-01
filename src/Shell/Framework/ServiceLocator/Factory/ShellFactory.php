<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionException;
use ExtendsSoftware\ExaPHP\Shell\Descriptor\DescriptorInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellBuilder;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;

class ShellFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws DefinitionException
     * @throws ServiceLocatorException
     */
    public function createService(
        string $class,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): ShellInterface {
        /** @var DescriptorInterface $descriptor */
        $descriptor = $serviceLocator->getService(DescriptorInterface::class);

        /** @var ParserInterface $parser */
        $parser = $serviceLocator->getService(ParserInterface::class);

        /** @var SuggesterInterface $suggester */
        $suggester = $serviceLocator->getService(SuggesterInterface::class);

        $config = $serviceLocator->getContainer()->find(ShellInterface::class, []);
        $builder = (new ShellBuilder())
            ->setName($config['name'] ?? null)
            ->setProgram($config['program'] ?? null)
            ->setVersion($config['version'] ?? null)
            ->setDescriptor($descriptor)
            ->setParser($parser)
            ->setSuggester($suggester);

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
