<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

use function strip_tags;

class NoTagsValidator extends AbstractValidator
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
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value === strip_tags($value)) {
            return $this->getValidResult();
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
