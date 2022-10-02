<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class IdenticalValidator extends AbstractValidator
{
    /**
     * When value is not identical to context.
     *
     * @const string
     */
    public const NOT_IDENTICAL = 'notIdentical';

    /**
     * IdenticalValidator constructor.
     *
     * @param mixed $subject
     */
    public function __construct(private readonly mixed $subject)
    {
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new IdenticalValidator(
            /** @phpstan-ignore-next-line */
            $extra['subject']
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if ($value === $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_IDENTICAL, [
            'value' => $value,
            'subject' => $this->subject,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_IDENTICAL => 'Value {{value}} is not identical to subject {{subject}}.',
        ];
    }
}
