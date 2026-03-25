<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Collection;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\IterableValidator;

use function array_diff_assoc;
use function array_unique;
use function array_values;

class UniqueValidator extends AbstractProcessor
{
    /**
     * When iterable contains non-unique values.
     *
     * @var string
     */
    public const string NOT_UNIQUE = 'notUnique';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new IterableValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        $values = [];
        foreach ($value as $inner) {
            $values[] = $inner;
        }

        $duplicates = array_values(array_diff_assoc($values, array_unique($values)));
        if ($duplicates) {
            return $this->getInvalidResult(self::NOT_UNIQUE, [
                'duplicates' => $duplicates,
            ]);
        }

        return $this->getValidResult($value);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_UNIQUE => 'Collection must contain unique values. Duplicate values found: {{duplicates}}.',
        ];
    }
}
