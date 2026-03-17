<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator;

use function array_diff_assoc;
use function array_unique;

class UniqueValidator extends AbstractValidator
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
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new IterableValidator())->validate($value);
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
