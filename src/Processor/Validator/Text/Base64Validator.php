<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function base64_decode;
use function base64_encode;
use function preg_match;
use function rtrim;
use function str_ends_with;
use function str_replace;

class Base64Validator extends AbstractProcessor
{
    /**
     * When string contains invalid base64 characters.
     *
     * @const string
     */
    public const string INVALID_CHARACTERS = 'invalidCharacters';

    /**
     * When padding is not allowed.
     *
     * @const string
     */
    public const string PADDING_NOT_ALLOWED = 'paddingNotAllowed';

    /**
     * When failed to decode string.
     *
     * @const string
     */
    public const string DECODE_FAILED = 'decodeFailed';

    /**
     * When encoded string not the same as initial string.
     *
     * @const string
     */
    public const string ENCODE_FAILED = 'encodeFailed';

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
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
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
            $nonUrlSafeValue = str_replace(['-', '_'], ['/', '+'], $value); // Convert URL safe base64 to normal base64.
        }

        $decoded = base64_decode($nonUrlSafeValue ?? $value, true);
        if ($decoded === false) {
            return $this->getInvalidResult(self::DECODE_FAILED);
        }

        $encoded = base64_encode($decoded);
        if ($this->noPadding === true) {
            $encoded = rtrim($encoded, '=');
        }

        if ($encoded !== ($nonUrlSafeValue ?? $value)) {
            return $this->getInvalidResult(self::DECODE_FAILED);
        }

        return $this->getValidResult($value);
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
