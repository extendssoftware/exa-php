<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator;
use ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LatitudeValidator;
use ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LongitudeValidator;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class CoordinatesValidator extends AbstractValidator
{
    /**
     * Latitude object key.
     *
     * @var string
     */
    private string $latitude;

    /**
     * Longitude object key.
     *
     * @var string
     */
    private string $longitude;

    /**
     * CoordinatesValidator constructor.
     *
     * @param string|null $latitude
     * @param string|null $longitude
     */
    public function __construct(string $latitude = null, string $longitude = null)
    {
        $this->latitude = $latitude ?: 'latitude';
        $this->longitude = $longitude ?: 'longitude';
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new CoordinatesValidator(
            $extra['latitude'] ?? null,
            $extra['longitude'] ?? null
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $validator = new PropertiesValidator([
            $this->latitude => new LatitudeValidator(),
            $this->longitude => new LongitudeValidator(),
        ]);

        return $validator->validate($value);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getTemplates(): array
    {
        return [];
    }
}
