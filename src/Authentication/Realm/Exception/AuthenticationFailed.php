<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Realm\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Authentication\Realm\RealmException;

class AuthenticationFailed extends Exception implements RealmException
{
}
