<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function call_user_func;

class LengthValidator extends AbstractValidator
{
    /**
     * When text is too short.
     *
     * @var string
     */
    public const TOO_SHORT = 'tooShort';

    /**
     * When text is too long.
     *
     * @var string
     */
    public const TOO_LONG = 'tooLong';

    /**
     * When text contains whitespace which is not allowed.
     *
     * @var string
     */
    public const WHITESPACE_NOT_ALLOWED = 'whitespaceNotAllowed';

    /**
     * LengthValidator constructor.
     *
     * @param int|null  $min
     * @param int|null  $max
     * @param bool|null $allowNewLine
     * @param bool|null $multibyte
     */
    public function __construct(
        private readonly ?int  $min = null,
        private readonly ?int  $max = null,
        private readonly ?bool $allowNewLine = null,
        private readonly ?bool $multibyte = null
    ) {
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

        if (($this->multibyte ?? true) === true) {
            $length = mb_strlen($value);
        } else {
            $length = strlen($value);
        }

        if (is_int($this->min) && $length < $this->min) {
            return $this->getInvalidResult(self::TOO_SHORT, [
                'min' => $this->min,
                'length' => $length,
            ]);
        }
        if (is_int($this->max) && $length > $this->max) {
            return $this->getInvalidResult(self::TOO_LONG, [
                'max' => $this->max,
                'length' => $length,
            ]);
        }
        if ($this->allowNewLine === false && preg_match('/\R/', $value)) {
            return $this->getInvalidResult(self::WHITESPACE_NOT_ALLOWED);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::TOO_SHORT => 'String length must be at least {{min}} characters, got {{length}}.',
            self::TOO_LONG => 'String length can be up to {{max}} characters, got {{length}}.',
            self::WHITESPACE_NOT_ALLOWED => 'Whitespace it not allowed in string.',
        ];
    }
}
