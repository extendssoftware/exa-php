<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function ctype_space;

class WhitespaceValidator extends AbstractValidator
{
    /**
     * When string does not consist of only whitespace characters.
     *
     * @const string
     */
    public const NOT_WHITESPACE = 'notWhitespace';

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

        if (ctype_space($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_WHITESPACE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_WHITESPACE => 'String can only consist of whitespace characters.',
        ];
    }
}
