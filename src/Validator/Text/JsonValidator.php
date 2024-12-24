<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function json_last_error_msg;
use function json_validate;

class JsonValidator extends AbstractValidator
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
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $valid = json_validate($value);
        if (!$valid) {
            return $this->getInvalidResult(self::DECODE_FAILED, [
                'reason' => json_last_error_msg(),
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
            self::DECODE_FAILED => 'JSON decode failed with reason {{reason}}.',
        ];
    }
}
