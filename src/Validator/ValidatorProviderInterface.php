<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

interface ValidatorProviderInterface
{
    /**
     * Get validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface;
}