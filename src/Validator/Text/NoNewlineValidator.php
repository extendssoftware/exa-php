<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function preg_match;

class NoNewlineValidator extends AbstractValidator
{
    /**
     * When newline is not allowed.
     *
     * @var string
     */
    public const NEWLINE_NOT_ALLOWED = 'newlineNotAllowed';

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

        if (preg_match('/\n/', $value)) {
            return $this->getInvalidResult(self::NEWLINE_NOT_ALLOWED);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NEWLINE_NOT_ALLOWED => 'Newline is not allowed in text.',
        ];
    }
}
