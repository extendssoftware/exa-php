<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Transformer\String;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function strip_tags;

class StripHtmlTagsTransformer extends AbstractProcessor implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        return $this->getValidResult(strip_tags($value));
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getTemplates(): array
    {
        return [];
    }
}
