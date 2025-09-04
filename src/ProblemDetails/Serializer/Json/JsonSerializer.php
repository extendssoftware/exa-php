<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\Json;

use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetailsInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\SerializerInterface;
use stdClass;

use function json_encode;

use const JSON_UNESCAPED_SLASHES;

class JsonSerializer implements SerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(ProblemDetailsInterface $problem): string
    {
        return strval(
            json_encode(
                [
                    'type' => $problem->getType(),
                    'title' => $problem->getTitle(),
                    'detail' => $problem->getDetail(),
                    'status' => $problem->getStatus(),
                    'instance' => $problem->getInstance(),
                    'metadata' => $problem->getMetadata() ?: new stdClass()
                ],
                JSON_UNESCAPED_SLASHES
            )
        );
    }
}
