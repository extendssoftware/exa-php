<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Config;

interface ConfigInterface
{
    /**
     * Get value from config for path.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $path, mixed $default = null): mixed;
}
