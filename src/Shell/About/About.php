<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\About;

class About implements AboutInterface
{
    /**
     * About constructor.
     *
     * @param string $name
     * @param string $program
     * @param string $version
     */
    public function __construct(
        private readonly string $name,
        private readonly string $program,
        private readonly string $version
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getProgram(): string
    {
        return $this->program;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
