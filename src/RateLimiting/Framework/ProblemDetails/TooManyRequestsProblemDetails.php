<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;

class TooManyRequestsProblemDetails extends ProblemDetails
{
    /**
     * TooManyRequestsProblemDetails constructor.
     *
     * @param RequestInterface $request
     * @param QuotaInterface $quota
     */
    public function __construct(RequestInterface $request, QuotaInterface $quota)
    {
        parent::__construct(
            '/problems/rate-limiting/too-many-requests',
            'Too Many Requests',
            'Rate limit exceeded.',
            429,
            $request->getUri()->toRelative(),
            [
                'X-RateLimit-Limit' => $quota->getLimit(),
                'X-RateLimit-Remaining' => $quota->getRemaining(),
                'X-RateLimit-Reset' => $quota->getReset(),
            ]
        );
    }
}
