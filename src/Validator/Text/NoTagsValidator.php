<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;

class NoTagsValidator extends AbstractValidator
{
    /**
     * When text contains tags.
     *
     * @var string
     */
    public const TAGS = 'tags';

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

        return $this->getInvalidResult(self::TAGS);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::TAGS => 'Text can not contain tags.',
        ];
    }
}
