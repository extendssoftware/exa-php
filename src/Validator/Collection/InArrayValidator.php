<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class InArrayValidator extends AbstractValidator
{
    /**
     * When value not in array.
     *
     * @var string
     */
    public const NOT_IN_ARRAY = 'notInArray';

    /**
     * InArrayValidator constructor.
     *
     * @param mixed[] $values
     */
    public function __construct(private readonly array $values)
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
        return new InArrayValidator(
            $extra['values'] ?? []
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (!in_array($value, $this->values)) {
            return $this->getInvalidResult(self::NOT_IN_ARRAY, [
                'value' => $value,
            ]);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_IN_ARRAY => 'Value {{value}} is not allowed in array.',
        ];
    }
}
