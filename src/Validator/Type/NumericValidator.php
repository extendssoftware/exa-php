<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class NumericValidator extends AbstractValidator
{
    /**
     * When value is not numeric.
     *
     * @const string
     */
    public const NOT_NUMERIC = 'notNumeric';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_numeric($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_NUMERIC, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMERIC => 'Value must be numeric, got {{type}}.',
        ];
    }
}
