<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderException;

class LinkNotEmbeddable extends Exception implements BuilderException
{
    /**
     * LinkNotEmbeddable constructor.
     *
     * @param string $rel
     */
    public function __construct(private readonly string $rel)
    {
        parent::__construct(
            sprintf(
                'Link with rel "%s" is not embeddable.',
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
