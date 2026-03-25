<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator;

use function strip_tags;

class NoTagsValidator extends AbstractProcessor
{
    /**
     * When text contains tags.
     *
     * @var string
     */
    public const string TAGS_NOT_ALLOWED = 'tagsNotAllowed';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value === strip_tags($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::TAGS_NOT_ALLOWED);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::TAGS_NOT_ALLOWED => 'Text can not contain HTML and PHP tags.',
        ];
    }
}
