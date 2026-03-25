<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function json_last_error_msg;
use function json_validate;

class JsonValidator extends AbstractProcessor
{
    /**
     * When failed to decode JSON string.
     *
     * @const string
     */
    public const string DECODE_FAILED = 'decodeFailed';

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

        $valid = json_validate($value);
        if (!$valid) {
            return $this->getInvalidResult(self::DECODE_FAILED, [
                'reason' => json_last_error_msg(),
            ]);
        }

        return $this->getValidResult($value);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::DECODE_FAILED => 'JSON decode failed with reason {{reason}}.',
        ];
    }
}
