<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function preg_match;

class NoPaddingValidator extends AbstractValidator
{
    /**
     * When padding is not allowed.
     *
     * @var string
     */
    public const PADDING_NOT_ALLOWED = 'paddingNotAllowed';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (preg_match('/^\s|\s$/', $value)) {
            return $this->getInvalidResult(self::PADDING_NOT_ALLOWED);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::PADDING_NOT_ALLOWED => 'Whitespace padding is not allowed.',
        ];
    }
}
