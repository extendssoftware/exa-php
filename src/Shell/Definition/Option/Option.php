<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition\Option;

use ExtendsSoftware\ExaPHP\Shell\Definition\Option\Exception\NoShortAndLongName;

class Option implements OptionInterface
{
    /**
     * Create new option.
     *
     * @param string      $name
     * @param string      $description
     * @param string|null $short
     * @param string|null $long
     * @param bool|null   $isFlag
     * @param bool|null   $isMultiple
     *
     * @throws NoShortAndLongName When both short and long name are not given.
     */
    public function __construct(
        private readonly string  $name,
        private readonly string  $description,
        private readonly ?string $short = null,
        private readonly ?string $long = null,
        private readonly ?bool   $isFlag = null,
        private readonly ?bool   $isMultiple = null
    ) {
        if ($short === null && $long === null) {
            throw new NoShortAndLongName($name);
        }
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getShort(): ?string
    {
        return $this->short;
    }

    /**
     * @inheritDoc
     */
    public function getLong(): ?string
    {
        return $this->long;
    }

    /**
     * @inheritDoc
     */
    public function isFlag(): bool
    {
        return $this->isFlag ?? true;
    }

    /**
     * @inheritDoc
     */
    public function isMultiple(): bool
    {
        return $this->isMultiple ?? false;
    }
}
