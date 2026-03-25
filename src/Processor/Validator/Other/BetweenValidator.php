<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator;

use function is_null;

class BetweenValidator extends AbstractProcessor
{
    /**
     * When a value is too low.
     *
     * @var string
     */
    public const string TOO_LOW = 'tooLow';

    /**
     * When a value is too low or same as min.
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
     * When a value is too high or same as max.
     *
     * @var string
     */
    public const string TOO_HIGH_INCLUSIVE = 'tooHighInclusive';

    /**
     * Internal processor to validate value type, defaults to IntegerValidator.
     *
     * @var ProcessorInterface
     */
    private ProcessorInterface $processor;

    /**
     * BetweenValidator constructor.
     *
     * @param string|float|int|bool|null $min
     * @param string|float|int|bool|null $max
     * @param bool|null                  $inclusive
     * @param ProcessorInterface|null    $processor
     */
    public function __construct(
        private readonly string|float|int|bool|null $min = null,
        private readonly string|float|int|bool|null $max = null,
        private readonly ?bool $inclusive = null,
        ?ProcessorInterface $processor = null,
    ) {
        $this->processor = $processor ?? new IntegerValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = $this->processor->process($value);
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

        return $this->getValidResult($value);
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
