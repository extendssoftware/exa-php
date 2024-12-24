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
    public const string NOT_INTEGER = 'notInteger';

    /**
     * When value is not an unsigned integer.
     *
     * @const string
     */
    public const string NOT_UNSIGNED = 'notUnsigned';

    /**
     * IntegerValidator constructor.
     *
     * @param bool|null $unsigned    If an unsigned integer (negative value) is allowed, defaults to false.
     * @param bool|null $allowString If a string representation of an integer is allowed, defaults to false.
     */
    public function __construct(private readonly ?bool $unsigned = null, private readonly ?bool $allowString = null)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        // This will check if the string-int-string value is the same as the original value, thus '5' -> 5 -> '5' can
        // be seen as a string representation of an integer. If so, and if allowed, convert string to an integer.
        if ($this->allowString && $value === (string)(int)$value) {
            $value = (int)$value;
        }

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
