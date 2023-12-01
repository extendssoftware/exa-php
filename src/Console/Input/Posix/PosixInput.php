<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Input\Posix;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use TypeError;

class PosixInput implements InputInterface
{
    /**
     * Resource to read from.
     *
     * @var resource
     */
    private $stream;

    /**
     * PosixInput constructor.
     *
     * @param resource|null $stream
     *
     * @throws TypeError When stream not of type resource.
     */
    public function __construct($stream = null)
    {
        $stream = $stream ?: STDIN;
        if (!is_resource($stream)) {
            throw new TypeError(
                sprintf(
                    'Stream must be of type resource, %s given.',
                    gettype($stream)
                )
            );
        }

        $this->stream = $stream;
    }

    /**
     * @inheritDoc
     */
    public function line(int $length = null): ?string
    {
        if (is_int($length)) {
            // Add 1 to length because PHP reads length - 1 bytes.
            $length = max(1, $length) + 1;
        }

        $line = fgets($this->stream, $length);
        if (is_string($line)) {
            $line = trim($line);
            if (strlen($line) > 0) {
                return $line;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function character(string $allowed = null): ?string
    {
        $character = $this->line();
        if (is_string($character)) {
            $character = substr($character, 0, 1);
            if (is_string($allowed) && !str_contains($allowed, $character)) {
                return null;
            }
        }

        return $character;
    }
}
