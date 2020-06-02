<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Policy;

use ExtendsFramework\Identity\IdentityInterface;

interface PolicyInterface
{
    /**
     * If identity is allowed by policy.
     *
     * @param IdentityInterface $identity
     *
     * @return bool
     */
    public function isAllowed(IdentityInterface $identity): bool;
}
