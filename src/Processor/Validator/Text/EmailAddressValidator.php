<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function filter_var;

use const FILTER_VALIDATE_EMAIL;

class EmailAddressValidator extends AbstractProcessor
{
    /**
     * When value is not an email address.
     *
     * @const string
     */
    public const string NO_EMAIL_ADDRESS = 'noEmailAddress';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->getValidResult($value);
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
