<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use Attribute;
use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class Route implements RouteInterface
{
    /**
     * Route constructor.
     *
     * @param string                            $path
     * @param array<string, ValidatorInterface> $validators
     * @param array<string, mixed>              $parameters
     * @param string|null                       $name
     * @param Method                            $method
     */
    public function __construct(
        private readonly string  $path,
        private readonly array   $validators = [],
        private readonly array   $parameters = [],
        private readonly ?string $name = null,
        private readonly Method  $method = Method::GET,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): Method
    {
        return $this->method;
    }
}
