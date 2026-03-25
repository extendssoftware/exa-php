<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function ctype_lower;

class LowercaseValidator extends AbstractProcessor
{
    /**
     * When string does not consist of only lowercase characters.
     *
     * @const string
     */
    public const string NOT_LOWERCASE = 'notLowercase';

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

        if (ctype_lower($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_LOWERCASE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_LOWERCASE => 'String can only consist of lowercase characters.',
        ];
    }
}
