<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

class Base64Validator extends AbstractValidator
{
    /**
     * When string contains invalid base64 characters.
     *
     * @const string
     */
    public const INVALID_CHARACTERS = 'invalidCharacters';

    /**
     * When failed to decode string.
     */
    public const DECODE_FAILED = 'decodeFailed';

    /**
     * When encoded string not the same as initial string.
     */
    public const ENCODE_FAILED = 'encodeFailed';

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

        if (!preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $value)) {
            return $this->getInvalidResult(self::INVALID_CHARACTERS);
        }

        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return $this->getInvalidResult(self::DECODE_FAILED);
        }

        if (base64_encode($decoded) !== $value) {
            return $this->getInvalidResult(self::DECODE_FAILED);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::INVALID_CHARACTERS => 'String contains invalid base 64 characters.',
            self::DECODE_FAILED => 'Base64 string decode failed.',
            self::ENCODE_FAILED => 'Decoded/encoded base64 string not the same as initial string.',
        ];
    }
}
