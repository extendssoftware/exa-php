<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function is_scalar;
use function sprintf;

class RangeValidator extends AbstractProcessor
{
    /**
     * When left is greater than right.
     *
     * @var string
     */
    public const string INVALID_RANGE_INCLUSIVE = 'invalidRangeInclusive';

    /**
     * When left is greater than right or the same.
     *
     * @var string
     */
    public const string INVALID_RANGE_NON_INCLUSIVE = 'invalidRangeNonInclusive';

    /**
     * RangeValidator constructor.
     *
     * @param ProcessorInterface $leftProcessor
     * @param ProcessorInterface $rightProcessor
     * @param string             $leftKey
     * @param string             $rightKey
     * @param bool               $inclusive
     */
    public function __construct(
        private readonly ProcessorInterface $leftProcessor,
        private readonly ProcessorInterface $rightProcessor,
        private readonly string $leftKey = 'left',
        private readonly string $rightKey = 'right',
        private readonly bool $inclusive = false,
    ) {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new PropertiesProcessor([
            $this->leftKey => $this->leftProcessor,
            $this->rightKey => $this->rightProcessor,
        ]))->process($value);
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
            } elseif ($leftValue >= $rightValue) {
                return $this->getInvalidResult(self::INVALID_RANGE_NON_INCLUSIVE, [
                    $this->leftKey => $leftValue,
                    $this->rightKey => $rightValue,
                ]);
            }
        }

        return $this->getValidResult($value);
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
