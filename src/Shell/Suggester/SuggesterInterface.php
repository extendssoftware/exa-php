<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Suggester;

use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;

interface SuggesterInterface
{
    /**
     * Find the best matching command in commands to suggest for phrase.
     *
     * @param string           $phrase
     * @param CommandInterface ...$commands
     *
     * @return ?CommandInterface
     */
    public function suggest(string $phrase, CommandInterface ...$commands): ?CommandInterface;
}
