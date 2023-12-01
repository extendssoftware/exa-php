<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\ArrayValidator;

class SizeValidator extends AbstractValidator
{
    /**
     * When there are too few items.
     *
     * @var string
     */
    public const TOO_FEW = 'tooFew';

    /**
     * When there are too many items.
     *
     * @var string
     */
    public const TOO_MANY = 'tooMany';

    /**
     * SizeValidator constructor.
     *
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(private readonly ?int $min = null, private readonly ?int $max = null)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new ArrayValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $count = count($value);
        if (is_int($this->min) && $count < $this->min) {
            return $this->getInvalidResult(self::TOO_FEW, [
                'min' => $this->min,
                'count' => $count,
            ]);
        }

        if (is_int($this->max) && $count > $this->max) {
            return $this->getInvalidResult(self::TOO_MANY, [
                'max' => $this->max,
                'count' => $count,
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
            self::TOO_FEW => 'Collection size must be at least {{min}} items, got {{count}}.',
            self::TOO_MANY => 'Collection size can be up to {{max}} items, got {{count}}.',
        ];
    }
}
