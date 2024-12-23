<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function preg_match;

class RegexValidator extends AbstractValidator
{
    /**
     * When value does not match pattern.
     *
     * @const string
     */
    public const string NOT_VALID = 'notValid';

    /**
     * Create new regular expression Validator for pattern.
     *
     * @param string $pattern
     */
    public function __construct(private readonly string $pattern)
    {
    }

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

        if (preg_match($this->pattern, $value) === 1) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_VALID, [
            'value' => $value,
            'pattern' => $this->pattern,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_VALID => 'Value {{value}} must match regular expression {{pattern}}.',
        ];
    }
}
