<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\About;

readonly class About implements AboutInterface
{
    /**
     * About constructor.
     *
     * @param string $name
     * @param string $program
     * @param string $version
     */
    public function __construct(private string $name, private string $program, private string $version)
    {
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
