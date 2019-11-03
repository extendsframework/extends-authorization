<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Permission;

use ExtendsFramework\Authorization\Permission\Exception\InvalidPermissionNotation;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    /**
     * Implies.
     *
     * Test that implies method will return correct boolean.
     *
     * @covers \ExtendsFramework\Authorization\Permission\Permission::__construct()
     * @covers \ExtendsFramework\Authorization\Permission\Permission::implies()
     * @covers \ExtendsFramework\Authorization\Permission\Permission::getSections()
     */
    public function testImpliesExactMatches(): void
    {
        // Exact matches.
        $this->assertTrue((new Permission('foo:bar:baz'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo:bar'))->implies(new Permission('foo:bar')));
        $this->assertTrue((new Permission('foo:*'))->implies(new Permission('foo')));
        $this->assertTrue((new Permission('foo'))->implies(new Permission('foo')));

        // Stronger matches.
        $this->assertTrue((new Permission('foo:bar:*'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo:bar'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo:*'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo'))->implies(new Permission('foo:bar:baz')));

        // Weaker matches.
        $this->assertFalse((new Permission('foo:bar:baz'))->implies(new Permission('foo:bar:*')));
        $this->assertFalse((new Permission('foo:bar:baz'))->implies(new Permission('foo:bar')));
        $this->assertFalse((new Permission('foo:baz'))->implies(new Permission('foo:bar')));
    }

    /**
     * Invalid permission string.
     *
     * Test that an invalid permission notation is not allowed.
     *
     * @covers \ExtendsFramework\Authorization\Permission\Permission::__construct()
     * @covers \ExtendsFramework\Authorization\Permission\Exception\InvalidPermissionNotation::__construct()
     */
    public function testInvalidPermission(): void
    {
        $this->expectException(InvalidPermissionNotation::class);
        $this->expectExceptionMessage('Invalid permission notation detected, got "foo,:bar".');

        new Permission('foo,:bar');
    }

    /**
     * Not same instance.
     *
     * Test that permission can not imply other instance of PermissionInterface.
     *
     * @covers \ExtendsFramework\Authorization\Permission\Permission::__construct()
     * @covers \ExtendsFramework\Authorization\Permission\Permission::implies()
     */
    public function testNotSameInstance(): void
    {
        $this->assertFalse((new Permission('*'))->implies(new PermissionStub()));
    }
}
