<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function preg_match;

class NoWhitespaceValidator extends AbstractValidator
{
    /**
     * When whitespace is not allowed.
     *
     * @var string
     */
    public const WHITESPACE_NOT_ALLOWED = 'whitespaceNotAllowed';

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

        if (preg_match('/\s/', $value)) {
            return $this->getInvalidResult(self::WHITESPACE_NOT_ALLOWED);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::WHITESPACE_NOT_ALLOWED => 'Whitespace is not allowed in text.',
        ];
    }
}
