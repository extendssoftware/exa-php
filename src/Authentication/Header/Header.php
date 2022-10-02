<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Header;

class Header implements HeaderInterface
{
    /**
     * Header constructor.
     *
     * @param string $scheme
     * @param string $credentials
     */
    public function __construct(private readonly string $scheme, private readonly string $credentials)
    {
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }
}
