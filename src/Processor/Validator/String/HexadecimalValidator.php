<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function ctype_xdigit;

class HexadecimalValidator extends AbstractProcessor
{
    /**
     * When string does not consist of only hexadecimal digits.
     *
     * @const string
     */
    public const string NOT_HEXADECIMAL = 'notHexadecimal';

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

        if (ctype_xdigit($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_HEXADECIMAL);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_HEXADECIMAL => 'String can only consist of hexadecimal digits.',
        ];
    }
}
