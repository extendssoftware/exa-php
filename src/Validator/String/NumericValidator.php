<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use function ctype_digit;

class NumericValidator extends AbstractValidator
{
    /**
     * When string does not consist of only numeric characters.
     *
     * @const string
     */
    public const NOT_NUMERIC = 'notNumeric';

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new NumericValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (ctype_digit($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_NUMERIC);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMERIC => 'String can only consist of numeric characters.',
        ];
    }
}
