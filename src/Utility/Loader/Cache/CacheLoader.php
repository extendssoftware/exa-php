<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Loader\Cache;

use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

use function file_put_contents;
use function is_file;
use function sprintf;
use function var_export;

readonly class CacheLoader implements LoaderInterface
{
    /**
     * CacheLoader constructor.
     *
     * @param string $filename
     */
    public function __construct(private string $filename)
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
     * Save to file.
     *
     * @param mixed[] $loaded
     *
     * @return CacheLoader
     */
    public function save(array $loaded): CacheLoader
    {
        file_put_contents(
            $this->getFilename(),
            sprintf('<?php return %s;', var_export($loaded, true))
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
