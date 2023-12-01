<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Request\Method;

enum Method: string
{
    /**
     * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    case OPTIONS = 'OPTIONS';
    case GET = 'GET';
    case HEAD = 'HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
    case TRACE = 'TRACE';
    case CONNECT = 'CONNECT';
}
