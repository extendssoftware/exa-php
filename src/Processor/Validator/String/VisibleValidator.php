<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function ctype_print;

class VisibleValidator extends AbstractProcessor
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
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (ctype_print($value)) {
            return $this->getValidResult($value);
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
