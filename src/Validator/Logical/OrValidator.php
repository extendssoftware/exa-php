<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class OrValidator extends AbstractLogicalValidator
{
    /**
     * When none of the Validators is valid.
     *
     * @const string
     */
    public const NONE_VALID = 'noneValid';

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

        return new OrValidator($validators);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $validators = $this->getValidators();
        foreach ($validators as $validator) {
            $result = $validator->validate($value, $context);
            if ($result->isValid()) {
                return $this->getValidResult();
            }
        }

        return $this->getInvalidResult(self::NONE_VALID, [
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
        ];
    }
}
