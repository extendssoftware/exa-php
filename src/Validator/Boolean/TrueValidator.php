<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\BooleanValidator;

class TrueValidator extends AbstractValidator
{
    /**
     * When value is a boolean, but not true.
     *
     * @var string
     */
    public const string NOT_TRUE = 'notTrue';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new BooleanValidator())->validate($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value !== true) {
            return $this->getInvalidResult(self::NOT_TRUE);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_TRUE => 'Value must equals true.',
        ];
    }
}
