<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\Cache;

use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;

class CacheLoader implements LoaderInterface
{
    /**
     * CacheLoader constructor.
     *
     * @param string $filename
     */
    public function __construct(private readonly string $filename)
    {
    }

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $filename = $this->getFilename();
        if (is_file($filename)) {
            return require $filename;
        }

        return [];
    }

    /**
     * Save config to file.
     *
     * @param mixed[] $config
     *
     * @return CacheLoader
     */
    public function save(array $config): CacheLoader
    {
        file_put_contents(
            $this->getFilename(),
            sprintf('<?php return %s;', var_export($config, true))
        );

        return $this;
    }

    /**
     * Get filename.
     *
     * @return string
     */
    private function getFilename(): string
    {
        return $this->filename;
    }
}
