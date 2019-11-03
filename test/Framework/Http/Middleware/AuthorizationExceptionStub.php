<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\Http\Middleware;

use ExtendsFramework\Authorization\AuthorizationException;
use LogicException;

class AuthorizationExceptionStub extends LogicException implements AuthorizationException
{
}
