<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function base64_decode;
use function base64_encode;
use function preg_match;
use function rtrim;
use function str_ends_with;
use function str_replace;

class Base64Validator extends AbstractValidator
{
    /**
     * When string contains invalid base64 characters.
     *
     * @const string
     */
    public const INVALID_CHARACTERS = 'invalidCharacters';

    /**
     * When padding is not allowed.
     *
     * @const string
     */
    public const PADDING_NOT_ALLOWED = 'paddingNotAllowed';

    /**
     * When failed to decode string.
     *
     * @const string
     */
    public const DECODE_FAILED = 'decodeFailed';

    /**
     * When encoded string not the same as initial string.
     *
     * @const string
     */
    public const ENCODE_FAILED = 'encodeFailed';

    /**
     * Base64Validator constructor.
     *
     * @param bool|null $urlSafe
     * @param bool|null $noPadding
     */
    public function __construct(private readonly ?bool $urlSafe = null, private readonly ?bool $noPadding = null)
    {
    }

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

        if ($this->urlSafe === true) {
            $pattern = '/^[a-zA-Z0-9\-_]*={0,2}$/';
        } else {
            $pattern = '/^[a-zA-Z0-9\/+]*={0,2}$/';
        }

        if (!preg_match($pattern, $value)) {
            return $this->getInvalidResult(self::INVALID_CHARACTERS);
        }

        if ($this->noPadding === true && str_ends_with($value, '=')) {
            return $this->getInvalidResult(self::PADDING_NOT_ALLOWED);
        }

        if ($this->urlSafe) {
            $value = str_replace(['-', '_'], ['/', '+'], $value); // Convert URL safe base64 to normal base64.
        }

        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return $this->getInvalidResult(self::DECODE_FAILED);
        }

        $encoded = base64_encode($decoded);
        if ($this->noPadding === true) {
            $encoded = rtrim($encoded, '=');
        }

        if ($encoded !== $value) {
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
            self::PADDING_NOT_ALLOWED => 'Padding characters are not allowed at the end of the string.',
            self::DECODE_FAILED => 'Base64 string decode failed.',
            self::ENCODE_FAILED => 'Decoded/encoded base64 string not the same as initial string.',
        ];
    }
}
