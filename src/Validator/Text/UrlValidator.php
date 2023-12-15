<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function array_map;
use function filter_var;
use function in_array;
use function parse_url;
use function strtolower;

class UrlValidator extends AbstractValidator
{
    /**
     * When value is not a valid URL.
     *
     * @const string
     */
    public const NO_URL = 'noUrl';

    /**
     * When scheme is not allowed.
     *
     * @const string
     */
    public const SCHEME_NOT_ALLOWED = 'schemeNotAllowed';

    /**
     * UrlValidator constructor.
     *
     * @param array<string>|null $allowedSchemes
     */
    public function __construct(private readonly ?array $allowedSchemes = null)
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

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return $this->getInvalidResult(self::NO_URL, [
                'value' => $value,
            ]);
        }

        if ($this->allowedSchemes) {
            $parse = parse_url($value);
            $scheme = $parse['scheme'] ?? 'http';
            if (!in_array(strtolower($scheme), array_map('strtolower', $this->allowedSchemes))) {
                return $this->getInvalidResult(self::SCHEME_NOT_ALLOWED, [
                    'scheme' => $scheme,
                    'schemes' => $this->allowedSchemes,
                ]);
            }
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NO_URL => 'Value {{value}} is not an valid URL.',
            self::SCHEME_NOT_ALLOWED => 'Scheme {{scheme}} is not allowed, only {{schemes}} is/are allowed for URL.'
        ];
    }
}
