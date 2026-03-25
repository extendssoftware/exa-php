<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\NumberValidator;

class LongitudeValidator extends AbstractProcessor
{
    /**
     * When longitude is lower than min or greater than max.
     *
     * @var string
     */
    public const string LONGITUDE_OUT_OF_BOUND = 'longitudeOutOfBound';

    /**
     * Minimal longitude value.
     *
     * @var int
     */
    private int $min = -90;

    /**
     * Maximum longitude value.
     *
     * @var int
     */
    private int $max = 90;

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new NumberValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value < $this->min || $value > $this->max) {
            return $this->getInvalidResult(self::LONGITUDE_OUT_OF_BOUND, [
                'min' => $this->min,
                'max' => $this->max,
                'longitude' => $value,
            ]);
        }

        return $this->getValidResult($value);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::LONGITUDE_OUT_OF_BOUND =>
                'Longitude is out of bound and must be between {{min}} and {{max}} inclusive, got {{longitude}}.',
        ];
    }
}
