<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure;

use Closure;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class ClosureResolver implements ResolverInterface
{
    /**
     * An associative array which holds the closures.
     *
     * @var Closure[]
     */
    private array $closures = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new ClosureResolver();
        foreach ($services as $key => $closure) {
            $resolver->addClosure($key, $closure);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->closures[$key]);
    }

    /**
     * The closure will be called with the parameters key and serviceLocator in specified order.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return $this->closures[$key]($key, $serviceLocator, $extra);
    }

    /**
     * Register closure for key.
     *
     * @param string  $key
     * @param Closure $closure
     *
     * @return ClosureResolver
     */
    public function addClosure(string $key, Closure $closure): ClosureResolver
    {
        $this->closures[$key] = $closure;

        return $this;
    }
}
