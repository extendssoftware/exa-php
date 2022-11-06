<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Loader;

interface LoaderInterface
{
    /**
     * Load multiple files and return them all in one indexed array.
     *
     * @return mixed[]
     * @throws LoaderException
     */
    public function load(): array;
}
