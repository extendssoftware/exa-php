<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\NullValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class NullableValidator extends AbstractValidator
{
    /**
     * Inner validator.
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * NullableValidator constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        /** @var ValidatorInterface $validator */
        /** @phpstan-ignore-next-line */
        $validator = $serviceLocator->getService($extra['name'], $extra['options'] ?? []);

        return new NullableValidator($validator);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new NullValidator())->validate($value, $context);
        if ($result->isValid()) {
            return $result;
        }

        return $this->validator->validate($value, $context);
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
