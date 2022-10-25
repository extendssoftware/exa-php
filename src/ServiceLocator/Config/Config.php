<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Config;

class Config implements ConfigInterface
{
    /**
     * Config constructor.
     *
     * @param mixed[] $config
     */
    public function __construct(private array $config)
    {
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, mixed $default = null): mixed
    {
        $reference = &$this->config;
        $parts = explode('.', $path);
        foreach ($parts as $part) {
            if (!is_array($reference) || !array_key_exists($part, $reference)) {
                return $default;
            }

            $reference = &$reference[$part];
        }

        return $reference;
    }
}
