<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\File;

use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;

class FileLoader implements LoaderInterface
{
    /**
     * Directories.
     *
     * @var mixed[]
     */
    private array $directories = [];

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $loaded = [];
        foreach ($this->directories as [$directory, $regex]) {
            $filenames = scandir($directory);
            if (is_array($filenames)) {
                foreach ($filenames as $filename) {
                    if (!in_array($filename, ['.', '..']) && preg_match($regex, $filename)) {
                        $loaded[] = require $directory . '/' . $filename;
                    }
                }
            }
        }

        return $loaded;
    }

    /**
     * Add glob path.
     *
     * @param string $directory
     * @param string $regex
     *
     * @return FileLoader
     */
    public function addPath(string $directory, string $regex): FileLoader
    {
        $this->directories[] = [$directory, $regex];

        return $this;
    }
}
