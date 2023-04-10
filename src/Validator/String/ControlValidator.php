<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use function ctype_cntrl;

class ControlValidator extends AbstractValidator
{
    /**
     * When string does not consist of only control characters.
     *
     * @const string
     */
    public const NOT_CONTROL = 'notControl';

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

        if (ctype_cntrl($value)) {
            return $this->getValidResult();
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
