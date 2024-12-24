<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function ctype_print;

class VisibleValidator extends AbstractValidator
{
    /**
     * When string does not consist of only characters that create visible output.
     *
     * @const string
     */
    public const string NOT_VISIBLE = 'notVisible';

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

        if (ctype_print($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_VISIBLE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_VISIBLE => 'String can only consist of characters that create visible output.',
        ];
    }
}
