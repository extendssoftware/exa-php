<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class XorValidator extends AbstractLogicalValidator
{
    /**
     * When none of the Validators are valid.
     *
     * @const string
     */
    public const NONE_VALID = 'noneValid';

    /**
     * When multiple of the Validators are valid.
     *
     * @const string
     */
    public const MULTIPLE_VALID = 'multipleValid';

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

        return new XorValidator($validators);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $valid = 0;
        $validators = $this->getValidators();
        foreach ($validators as $validator) {
            $result = $validator->validate($value, $context);
            if ($result->isValid()) {
                $valid++;
            }
        }

        if ($valid === 1) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult($valid === 0 ? self::NONE_VALID : self::MULTIPLE_VALID, [
            'count' => count($validators),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NONE_VALID => 'None of the {{count}} validator(s) are valid.',
            self::MULTIPLE_VALID => 'Multiple of the {{count}} validator(s) are valid.',
        ];
    }
}
