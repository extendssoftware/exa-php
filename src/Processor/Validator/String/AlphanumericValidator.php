<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function ctype_alnum;

class AlphanumericValidator extends AbstractProcessor
{
    /**
     * When string does not consist of only alphanumeric characters.
     *
     * @const string
     */
    public const string NOT_ALPHANUMERIC = 'notAlphanumeric';

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

        if (ctype_alnum($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_ALPHANUMERIC);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ALPHANUMERIC => 'String can only consist of alphanumeric characters.',
        ];
    }
}
