<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

use function is_null;

class BetweenValidator extends AbstractValidator
{
    /**
     * When value is too low.
     *
     * @var string
     */
    public const string TOO_LOW = 'tooLow';

    /**
     * When value is too low or same as min.
     *
     * @var string
     */
    public const string TOO_LOW_INCLUSIVE = 'tooLowInclusive';

    /**
     * When value is too high.
     *
     * @var string
     */
    public const string TOO_HIGH = 'tooHigh';

    /**
     * When value is too high or same as max.
     *
     * @var string
     */
    public const string TOO_HIGH_INCLUSIVE = 'tooHighInclusive';

    /**
     * Internal validator to validate value type, defaults to IntegerValidator.
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * BetweenValidator constructor.
     *
     * @param string|float|int|bool|null $min
     * @param string|float|int|bool|null $max
     * @param bool|null                  $inclusive
     * @param ValidatorInterface|null    $validator
     */
    public function __construct(
        private readonly string|float|int|bool|null $min = null,
        private readonly string|float|int|bool|null $max = null,
        private readonly ?bool $inclusive = null,
        ValidatorInterface $validator = null,
    ) {
        $this->validator = $validator ?? new IntegerValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = $this->validator->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $inclusive = $this->inclusive ?? true;
        if (!is_null($this->min)) {
            if ($inclusive) {
                if ($value < $this->min) {
                    return $this->getInvalidResult(self::TOO_LOW_INCLUSIVE, [
                        'min' => $this->min,
                        'value' => $value,
                    ]);
                }
            } elseif ($value <= $this->min) {
                return $this->getInvalidResult(self::TOO_LOW, [
                    'min' => $this->min,
                    'value' => $value,
                ]);
            }
        }

        if (!is_null($this->max)) {
            if ($inclusive) {
                if ($value > $this->max) {
                    return $this->getInvalidResult(self::TOO_HIGH_INCLUSIVE, [
                        'max' => $this->max,
                        'value' => $value,
                    ]);
                }
            } elseif ($value >= $this->max) {
                return $this->getInvalidResult(self::TOO_HIGH, [
                    'max' => $this->max,
                    'value' => $value,
                ]);
            }
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::TOO_LOW => 'Value must be greater than or equal to {{min}}, got {{value}}.',
            self::TOO_HIGH => 'Value must be less than or equal to {{max}}, got {{value}}.',
            self::TOO_LOW_INCLUSIVE => 'Value must be greater than {{min}}, got {{value}}.',
            self::TOO_HIGH_INCLUSIVE => 'Value must be less than {{max}}, got {{value}}.',
        ];
    }
}
