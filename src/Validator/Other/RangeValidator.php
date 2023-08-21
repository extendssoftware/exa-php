<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

use function is_scalar;

class RangeValidator extends AbstractValidator
{
    /**
     * When left is greater than right.
     *
     * @var string
     */
    public const INVALID_RANGE_INCLUSIVE = 'invalidRangeInclusive';

    /**
     * When left is greater than right or the same.
     *
     * @var string
     */
    public const INVALID_RANGE_NON_INCLUSIVE = 'invalidRangeNonInclusive';

    /**
     * RangeValidator constructor.
     *
     * @param ValidatorInterface $leftValidator
     * @param ValidatorInterface $rightValidator
     * @param string             $leftKey
     * @param string             $rightKey
     * @param bool               $inclusive
     */
    public function __construct(
        private readonly ValidatorInterface $leftValidator,
        private readonly ValidatorInterface $rightValidator,
        private readonly string $leftKey = 'left',
        private readonly string $rightKey = 'right',
        private readonly bool $inclusive = false,
    ) {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new PropertiesValidator([
            $this->leftKey => $this->leftValidator,
            $this->rightKey => $this->rightValidator,
        ]))->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $leftValue = $value->{$this->leftKey};
        $rightValue = $value->{$this->rightKey};
        if (is_scalar($leftValue) && is_scalar($rightValue)) {
            if ($this->inclusive) {
                if ($leftValue > $rightValue) {
                    return $this->getInvalidResult(self::INVALID_RANGE_INCLUSIVE, [
                        $this->leftKey => $leftValue,
                        $this->rightKey => $rightValue,
                    ]);
                }
            } else {
                if ($leftValue >= $rightValue) {
                    return $this->getInvalidResult(self::INVALID_RANGE_NON_INCLUSIVE, [
                        $this->leftKey => $leftValue,
                        $this->rightKey => $rightValue,
                    ]);
                }
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
            self::INVALID_RANGE_INCLUSIVE => sprintf(
                'Value {{%s}} can not be greater than {{%s}}.',
                $this->leftKey,
                $this->rightKey,
            ),
            self::INVALID_RANGE_NON_INCLUSIVE => sprintf(
                'Value {{%s}} can not be greater than or equals {{%s}}.',
                $this->leftKey,
                $this->rightKey,
            ),
        ];
    }
}
