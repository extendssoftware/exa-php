<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Permission;

use ExtendsSoftware\ExaPHP\Authorization\Permission\Exception\InvalidPermissionNotation;

class Permission implements PermissionInterface
{
    /**
     * Character to match everything in a section of the notation.
     *
     * @var string
     */
    private string $wildcard = '*';

    /**
     * Character to divide notation sections.
     *
     * @var string
     */
    private string $divider = '/';

    /**
     * Character to divide parts in a section.
     *
     * @var string
     */
    private string $separator = ',';

    /**
     * Case sensitive regular expression to verify notation.
     *
     * @var string
     */
    private string $pattern = '~^(\*|\w+(,\w+)*)(/(\*|\w+(,\w+)*))*$~';

    /**
     * Permission constructor.
     *
     * @param string $notation
     *
     * @throws InvalidPermissionNotation
     */
    public function __construct(private readonly string $notation)
    {
        if (!preg_match($this->pattern, $notation)) {
            throw new InvalidPermissionNotation($notation);
        }
    }

    /**
     * @inheritDoc
     */
    public function getNotation(): string
    {
        return $this->notation;
    }

    /**
     * @inheritDoc
     */
    public function implies(PermissionInterface $permission): bool
    {
        if (!$permission instanceof static) {
            return false;
        }

        $left = $this->getSections();
        $right = $permission->getSections();
        $wildcard = $this->wildcard;

        foreach ($right as $index => $section) {
            if (!isset($left[$index])) {
                return true;
            }

            if (array_intersect($section, $left[$index]) === [] && !in_array($wildcard, $left[$index], true)) {
                return false;
            }
        }

        foreach (array_slice($left, count($right)) as $section) {
            if (!in_array($wildcard, $section, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get exploded notation string.
     *
     * @return mixed[]
     */
    private function getSections(): array
    {
        $sections = [];

        if (!empty($this->divider)) {
            $sections = explode($this->divider, $this->notation);
            if (is_array($sections)) {
                foreach ($sections as $index => $section) {
                    if (is_string($section)) {
                        if (!empty($this->separator)) {
                            $sections[$index] = explode($this->separator, $section);
                        }
                    }
                }
            }
        }

        return $sections;
    }
}
