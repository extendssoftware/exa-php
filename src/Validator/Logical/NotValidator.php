<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class NotValidator extends AbstractLogicalValidator
{
    /**
     * When value is not false.
     *
     * @const string
     */
    public const NOT_FALSE = 'notFalse';

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        $validators = [];
        foreach ($extra['validators'] ?? [] as $validator) {
            $validators[] = $serviceLocator->getService($validator['name'], $validator['options'] ?? []);
        }

        return new NotValidator($validators);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (!$value) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_FALSE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FALSE => 'Value is not equal to false.',
        ];
    }
}
