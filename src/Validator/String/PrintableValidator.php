<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function ctype_graph;

class PrintableValidator extends AbstractValidator
{
    /**
     * When string does not consist of only printable characters except space.
     *
     * @const string
     */
    public const NOT_PRINTABLE = 'notPrintable';

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

        if (ctype_graph($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_PRINTABLE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_PRINTABLE => 'String can only consist of visible printable characters except space.',
        ];
    }
}
