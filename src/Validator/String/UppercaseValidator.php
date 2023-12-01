<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function ctype_upper;

class UppercaseValidator extends AbstractValidator
{
    /**
     * When string does not consist of only uppercase characters.
     *
     * @const string
     */
    public const NOT_LOWERCASE = 'notUppercase';

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

        if (ctype_upper($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_LOWERCASE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_LOWERCASE => 'String can only consist of uppercase characters.',
        ];
    }
}
