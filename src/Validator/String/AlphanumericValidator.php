<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function ctype_alnum;

class AlphanumericValidator extends AbstractValidator
{
    /**
     * When string does not consist of only alphanumeric characters.
     *
     * @const string
     */
    public const string NOT_ALPHANUMERIC = 'notAlphanumeric';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (ctype_alnum($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_ALPHANUMERIC);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ALPHANUMERIC => 'String can only consist of alphanumeric characters.',
        ];
    }
}
