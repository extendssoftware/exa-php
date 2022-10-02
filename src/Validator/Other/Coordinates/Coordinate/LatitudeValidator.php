<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\NumberValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class LatitudeValidator extends AbstractValidator
{
    /**
     * When latitude is lower than min or greater than max.
     *
     * @var string
     */
    public const LATITUDE_OUT_OF_BOUND = 'latitudeOutOfBound';

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
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new LatitudeValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new NumberValidator())->validate($value);
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

        return $this->getValidResult();
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
