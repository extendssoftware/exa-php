<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function filter_var;

use const FILTER_VALIDATE_IP;

class IpAddressValidator extends AbstractValidator
{
    /**
     * When value is not an IP address.
     *
     * @const string
     */
    public const string NOT_IP_ADDRESS = 'notIpAddress';

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

        if (filter_var($value, FILTER_VALIDATE_IP) !== false) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_IP_ADDRESS, [
            'value' => $value,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_IP_ADDRESS => 'Value {{value}} must be a valid IP address.',
        ];
    }
}
