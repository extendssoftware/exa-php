<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\NumberValidator;

class LongitudeValidator extends AbstractValidator
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
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new NumberValidator())->validate($value);
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

        return $this->getValidResult();
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
