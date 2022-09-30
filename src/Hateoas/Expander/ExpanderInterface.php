<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Expander;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;

interface ExpanderInterface
{
    /**
     * Expand resource from link.
     *
     * @param LinkInterface $link
     *
     * @return BuilderInterface
     * @throws ExpanderException
     */
    public function expand(LinkInterface $link): BuilderInterface;
}
