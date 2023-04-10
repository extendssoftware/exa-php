<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\StringValidator;
use function ctype_alpha;

class AlphabeticValidator extends AbstractValidator
{
    /**
     * When string does not consist of only alphabetic characters.
     *
     * @const string
     */
    public const NOT_ALPHABETIC = 'notAlphabetic';

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

        if (ctype_alpha($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_ALPHABETIC);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ALPHABETIC => 'String can only consist of alphabetic characters.',
        ];
    }
}
