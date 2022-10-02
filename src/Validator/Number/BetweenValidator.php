<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Number;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class BetweenValidator extends AbstractValidator
{
    /**
     * When number is too low.
     *
     * @var string
     */
    public const TOO_LOW = 'tooLow';

    /**
     * When number is too low or same as min.
     *
     * @var string
     */
    public const TOO_LOW_INCLUSIVE = 'tooLowInclusive';

    /**
     * When number is too high.
     *
     * @var string
     */
    public const TOO_HIGH = 'tooHigh';

    /**
     * When number is too high or same as max.
     *
     * @var string
     */
    public const TOO_HIGH_INCLUSIVE = 'tooHighInclusive';

    /**
     * SizeValidator constructor.
     *
     * @param int|null  $min
     * @param int|null  $max
     * @param bool|null $inclusive
     */
    public function __construct(
        private readonly ?int  $min = null,
        private readonly ?int  $max = null,
        private readonly ?bool $inclusive = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new BetweenValidator(
            $extra['min'] ?? null,
            $extra['max'] ?? null,
            $extra['inclusive'] ?? null
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new NumericValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $inclusive = $this->inclusive ?? true;
        if (is_int($this->min)) {
            if ($inclusive) {
                if ($value < $this->min) {
                    return $this->getInvalidResult(self::TOO_LOW_INCLUSIVE, [
                        'min' => $this->min,
                        'number' => $value,
                    ]);
                }
            } elseif ($value <= $this->min) {
                return $this->getInvalidResult(self::TOO_LOW, [
                    'min' => $this->min,
                    'number' => $value,
                ]);
            }
        }

        if (is_int($this->max)) {
            if ($inclusive) {
                if ($value > $this->max) {
                    return $this->getInvalidResult(self::TOO_HIGH_INCLUSIVE, [
                        'max' => $this->max,
                        'number' => $value,
                    ]);
                }
            } elseif ($value >= $this->max) {
                return $this->getInvalidResult(self::TOO_HIGH, [
                    'max' => $this->max,
                    'number' => $value,
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
            self::TOO_LOW => 'Number must be greater than or equal to {{min}}, got {{number}}.',
            self::TOO_HIGH => 'Number must be less than or equal to {{max}}, got {{number}}.',
            self::TOO_LOW_INCLUSIVE => 'Number must be greater than {{min}}, got {{number}}.',
            self::TOO_HIGH_INCLUSIVE => 'Number must be less than {{max}}, got {{number}}.',
        ];
    }
}
