<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\BooleanValidator;

class FalseValidator extends AbstractValidator
{
    /**
     * When value is a boolean, but not false.
     *
     * @var string
     */
    public const NOT_FALSE = 'notFalse';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new BooleanValidator())->validate($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value !== false) {
            return $this->getInvalidResult(self::NOT_FALSE);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FALSE => 'Value must equals false.',
        ];
    }
}
