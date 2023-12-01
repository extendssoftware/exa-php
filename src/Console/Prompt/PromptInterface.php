<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt;

use ExtendsSoftware\ExaPHP\Console\Input\InputException;
use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputException;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;

interface PromptInterface
{
    /**
     * Show prompt.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null|string
     * @throws PromptException|InputException|OutputException
     */
    public function prompt(InputInterface $input, OutputInterface $output): ?string;
}
