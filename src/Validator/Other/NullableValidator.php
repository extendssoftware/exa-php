<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

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
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
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
