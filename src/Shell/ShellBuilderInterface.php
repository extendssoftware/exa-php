<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

interface ShellBuilderInterface
{
    /**
     * Build shell.
     *
     * @return ShellInterface
     */
    public function build(): ShellInterface;
}
