<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function preg_match;

class NoNewlineValidator extends AbstractProcessor
{
    /**
     * When newline is not allowed.
     *
     * @var string
     */
    public const string NEWLINE_NOT_ALLOWED = 'newlineNotAllowed';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (preg_match('/\n/', $value)) {
            return $this->getInvalidResult(self::NEWLINE_NOT_ALLOWED);
        }

        return $this->getValidResult($value);
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
