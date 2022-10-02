<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

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
     * LengthValidator constructor.
     *
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(private readonly ?int $min = null, private readonly ?int $max = null)
    {
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        return new LengthValidator(
            $extra['min'] ?? null,
            $extra['max'] ?? null
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $length = strlen($value);
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
        ];
    }
}
