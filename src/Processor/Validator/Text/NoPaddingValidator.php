<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function preg_match;

class NoPaddingValidator extends AbstractProcessor
{
    /**
     * When padding is not allowed.
     *
     * @var string
     */
    public const string PADDING_NOT_ALLOWED = 'paddingNotAllowed';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (preg_match('/^\s|\s$/', $value)) {
            return $this->getInvalidResult(self::PADDING_NOT_ALLOWED);
        }

        return $this->getValidResult($value);
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
