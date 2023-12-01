<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderException;

class LinkNotFound extends Exception implements BuilderException
{
    /**
     * LinkNotFound constructor.
     *
     * @param string $rel
     */
    public function __construct(private readonly string $rel)
    {
        parent::__construct(
            sprintf(
                'Link with rel "%s" does not exists.',
                $rel
            )
        );
    }

    /**
     * Get link rel.
     *
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }
}
