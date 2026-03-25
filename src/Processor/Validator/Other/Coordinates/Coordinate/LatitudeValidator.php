<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\NumberValidator;

class LatitudeValidator extends AbstractProcessor
{
    /**
     * When latitude is lower than min or greater than max.
     *
     * @var string
     */
    public const string LATITUDE_OUT_OF_BOUND = 'latitudeOutOfBound';

    /**
     * Minimal latitude value.
     *
     * @var int
     */
    private int $min = -180;

    /**
     * Maximum latitude value.
     *
     * @var int
     */
    private int $max = 180;

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
            return $this->getInvalidResult(self::LATITUDE_OUT_OF_BOUND, [
                'min' => $this->min,
                'max' => $this->max,
                'latitude' => $value,
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
            self::LATITUDE_OUT_OF_BOUND =>
                'Latitude is out of bound and must be between {{min}} and {{max}} inclusive, got {{latitude}}.',
        ];
    }
}
