<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails\Serializer;

use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetailsInterface;

interface SerializerInterface
{
    /**
     * Serialize problem.
     *
     * @param ProblemDetailsInterface $problem
     *
     * @return string
     */
    public function serialize(ProblemDetailsInterface $problem): string;
}
