<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class EmailAddressValidator extends AbstractValidator
{
    /**
     * When value is not an email address.
     *
     * @const string
     */
    public const NO_EMAIL_ADDRESS = 'noEmailAddress';

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new EmailAddressValidator();
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

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NO_EMAIL_ADDRESS, [
            'value' => $value,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NO_EMAIL_ADDRESS => 'Value {{value}} is not an valid email address.',
        ];
    }
}
