<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use function ctype_print;

class VisibleValidator extends AbstractValidator
{
    /**
     * When string does not consist of only characters that create visible output.
     *
     * @const string
     */
    public const NOT_VISIBLE = 'notVisible';

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new VisibleValidator();
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

        if (ctype_print($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_VISIBLE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_VISIBLE => 'String can only consist of characters that create visible output.',
        ];
    }
}
