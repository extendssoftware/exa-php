<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

use function gettype;
use function is_int;

class IntegerValidator extends AbstractValidator
{
    /**
     * When value is not an integer.
     *
     * @const string
     */
    public const NOT_INTEGER = 'notInteger';

    /**
     * When value is not an unsigned integer.
     *
     * @const string
     */
    public const NOT_UNSIGNED = 'notUnsigned';

    /**
     * IntegerValidator constructor.
     *
     * @param bool|null $unsigned If an unsigned integer (negative value) is allowed, defaults to false.
     */
    public function __construct(private readonly ?bool $unsigned = null)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_int($value)) {
            if ($this->unsigned === true && $value < 0) {
                return $this->getInvalidResult(self::NOT_UNSIGNED);
            }

            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_INTEGER, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_INTEGER => 'Value must be an integer, got {{type}}.',
            self::NOT_UNSIGNED => 'Value must be an unsigned integer, got negative value.',
        ];
    }
}
