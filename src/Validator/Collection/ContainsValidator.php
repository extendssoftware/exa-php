<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class ContainsValidator extends AbstractValidator
{
    /**
     * Validator to validate collection values.
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * CollectionValidator constructor.
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
        $result = (new IterableValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $container = new ArrayContainerResult();
        foreach ($value as $inner) {
            $container->addItem(
                $this->validator->validate($inner, $context),
            );
        }

        return $container;
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
