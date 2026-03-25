<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use DateTime;
use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use const DATE_ATOM;

class DateTimeValidator extends AbstractProcessor
{
    /**
     * When value is not a valid date time notation.
     *
     * @const string
     */
    public const string NOT_DATE_TIME = 'notDateTime';

    /**
     * DateTimeValidator constructor.
     *
     * @param string|null $format
     */
    public function __construct(private readonly ?string $format = null)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        $format = $this->format ?? DATE_ATOM;
        $dateTime = DateTime::createFromFormat($format, $value);
        if ($dateTime && $dateTime->format($format) === $value) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_DATE_TIME, [
            'value' => $value,
            'format' => $format,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_DATE_TIME => 'Value {{value}} must be a valid date and/of time notation as {{format}}.',
        ];
    }
}
