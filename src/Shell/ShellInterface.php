<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

interface ShellInterface
{
    /**
     * Match arguments to corresponding command.
     *
     * When arguments can not be matched, null will be returned.
     *
     * @param mixed[] $arguments
     *
     * @return ShellResultInterface|null
     */
    public function process(array $arguments): ?ShellResultInterface;
}
