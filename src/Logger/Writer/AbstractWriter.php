<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer;

use ExtendsSoftware\ExaPHP\Logger\Filter\FilterInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;

abstract class AbstractWriter implements WriterInterface, StaticFactoryInterface
{
    /**
     * Filters.
     *
     * @var FilterInterface[]
     */
    private array $filters = [];

    /**
     * Add filter.
     *
     * @param FilterInterface $filter
     *
     * @return AbstractWriter
     */
    public function addFilter(FilterInterface $filter): AbstractWriter
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Check if log must be filtered.
     *
     * @param LogInterface $log
     *
     * @return bool
     */
    protected function filter(LogInterface $log): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->filter($log)) {
                return true;
            }
        }

        return false;
    }
}
