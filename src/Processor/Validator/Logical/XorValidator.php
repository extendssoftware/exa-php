<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function count;

class XorValidator extends AbstractLogicalValidator
{
    /**
     * When none of the processors are valid.
     *
     * @const string
     */
    public const string NONE_VALID = 'noneValid';

    /**
     * When multiple of the processors are valid.
     *
     * @const string
     */
    public const string MULTIPLE_VALID = 'multipleValid';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $valid = 0;
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $result = $processor->process($value, $context);
            if ($result->isValid()) {
                $valid++;
            }
        }

        if ($valid === 1) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult($valid === 0 ? self::NONE_VALID : self::MULTIPLE_VALID, [
            'count' => count($processors),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NONE_VALID => 'None of the {{count}} processor(s) are valid.',
            self::MULTIPLE_VALID => 'Multiple of the {{count}} processor(s) are valid.',
        ];
    }
}
