<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class BooleanValidator extends AbstractValidator
{
    /**
     * When value is not boolean.
     *
     * @const string
     */
    public const NOT_BOOLEAN = 'notBoolean';

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new BooleanValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_bool($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_BOOLEAN, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_BOOLEAN => 'Value must be a boolean, got {{type}}.',
        ];
    }
}
