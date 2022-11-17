<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Iterator;

use Iterator;

interface PeekIteratorInterface extends Iterator
{
    /**
     * Peek.
     *
     * Look amount of positions ahead.
     *
     * @param int        $positions Amount of positions to peek, absolute positions are added to the internal pointer.
     * @param mixed|null $default   Default value to return when iterator position does not exist.
     *
     * @return mixed
     */
    public function peek(int $positions, mixed $default = null): mixed;
}
