<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function ctype_cntrl;

class ControlValidator extends AbstractProcessor
{
    /**
     * When string does not consist of only control characters.
     *
     * @const string
     */
    public const string NOT_CONTROL = 'notControl';

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

        if (ctype_cntrl($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_CONTROL);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_CONTROL => 'String can only consist of control characters.',
        ];
    }
}
