<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate\LatitudeValidator;
use ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate\LongitudeValidator;

class CoordinatesValidator extends AbstractProcessor
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
    public function __construct(?string $latitude = null, ?string $longitude = null)
    {
        $this->latitude = $latitude ?: 'latitude';
        $this->longitude = $longitude ?: 'longitude';
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $validator = new PropertiesProcessor([
            $this->latitude => new LatitudeValidator(),
            $this->longitude => new LongitudeValidator(),
        ]);

        return $validator->process($value);
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
