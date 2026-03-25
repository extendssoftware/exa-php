<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\BooleanValidator;

class TrueValidator extends AbstractProcessor
{
    /**
     * When value is a boolean, but not true.
     *
     * @var string
     */
    public const string NOT_TRUE = 'notTrue';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new BooleanValidator())->process($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value !== true) {
            return $this->getInvalidResult(self::NOT_TRUE);
        }

        return $this->getValidResult(true);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_TRUE => 'Value must equals true.',
        ];
    }
}
