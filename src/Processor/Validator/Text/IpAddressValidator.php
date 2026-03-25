<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function filter_var;

use const FILTER_VALIDATE_IP;

class IpAddressValidator extends AbstractProcessor
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
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (filter_var($value, FILTER_VALIDATE_IP) !== false) {
            return $this->getValidResult($value);
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
