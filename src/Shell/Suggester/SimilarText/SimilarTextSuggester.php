<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Suggester\SimilarText;

use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;

class SimilarTextSuggester implements SuggesterInterface
{
    /**
     * Create new suggester.
     *
     * @param int $percentage
     */
    public function __construct(private readonly int $percentage = 60)
    {
    }

    /**
     * @inheritDoc
     */
    public function suggest(string $phrase, CommandInterface ...$commands): ?CommandInterface
    {
        $closest = null;
        $shortest = 0;

        foreach ($commands as $command) {
            similar_text($phrase, $command->getName(), $percentage);
            if ($percentage >= $this->percentage) {
                if ($percentage === 100.0) {
                    return $command;
                }

                if ($percentage > $shortest) {
                    $closest = $command;
                    $shortest = $percentage;
                }
            }
        }

        return $closest;
    }
}
