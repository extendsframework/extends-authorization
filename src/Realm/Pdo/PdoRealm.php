<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Realm\Pdo;

use ExtendsFramework\Authorization\AuthorizationInfo;
use ExtendsFramework\Authorization\AuthorizationInfoInterface;
use ExtendsFramework\Authorization\Permission\Permission;
use ExtendsFramework\Authorization\Realm\RealmInterface;
use ExtendsFramework\Authorization\Role\Role;
use ExtendsFramework\Identity\IdentityInterface;
use PDO;

class PdoRealm implements RealmInterface
{
    /**
     * PDO.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * PdoRealm constructor.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorizationInfo(IdentityInterface $identity): AuthorizationInfoInterface
    {
        $info = new AuthorizationInfo();

        $statement = $this->pdo->prepare('
            SELECT p.*
            FROM permission AS p
            INNER JOIN identity_permission AS ip USING (permission_id)
            INNER JOIN identity AS i USING (identity_id)
            WHERE i.identifier = ?
            
            UNION DISTINCT
            
            SELECT p.*
            FROM permission AS p
            INNER JOIN role_permission AS rp USING (permission_id)
            INNER JOIN role AS r USING (role_id)
            INNER JOIN identity_role AS ir USING (role_id)
            INNER JOIN identity AS i USING (identity_id)
            WHERE i.identifier = ?
        ');
        $statement->execute([
            $identity->getIdentifier(),
            $identity->getIdentifier(),
        ]);

        foreach ($statement->fetchAll() as $permission) {
            $info->addPermission(
                new Permission($permission['notation'])
            );
        }

        $statement = $this->pdo->prepare('
            SELECT r.*
            FROM role AS r
            INNER JOIN identity_role AS ir USING (role_id)
            INNER JOIN identity AS i USING (identity_id)
            WHERE i.identifier = ?
        ');
        $statement->execute([
            $identity->getIdentifier(),
        ]);

        foreach ($statement->fetchAll() as $role) {
            $info->addRole(
                new Role($role['name'])
            );
        }

        return $info;
    }
}
