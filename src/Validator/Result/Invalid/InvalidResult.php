<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Invalid;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

use function sprintf;

readonly class InvalidResult implements ResultInterface
{
    /**
     * InvalidResult constructor.
     *
     * @param string               $code
     * @param string               $message
     * @param array<string, mixed> $parameters
     */
    public function __construct(private string $code, private string $message, private array $parameters)
    {
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'parameters' => $this->getParameters(),
        ];
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get parameters.
     *
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Return result as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $replacement = [];
        foreach ($this->getParameters() as $key => $parameter) {
            $replacement[sprintf('{{%s}}', $key)] = $parameter;
        }

        return strtr($this->getMessage(), $replacement);
    }
}
