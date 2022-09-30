<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Framework\ServiceLocator\Factory\Validator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ContainerValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class ValidatorFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $container = new ContainerValidator();
        foreach ($extra['validators'] ?? [] as $config) {
            $validator = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($validator instanceof ValidatorInterface) {
                $container->addValidator($validator, $config['interrupt'] ?? null);
            }
        }

        return $container;
    }
}
