<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function count;

class OrValidator extends AbstractLogicalValidator
{
    /**
     * When none of the processors is valid.
     *
     * @const string
     */
    public const string NONE_VALID = 'noneValid';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $result = $processor->process($value, $context);
            if ($result->isValid()) {
                return $this->getValidResult($value);
            }
        }

        return $this->getInvalidResult(self::NONE_VALID, [
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
        ];
    }
}
