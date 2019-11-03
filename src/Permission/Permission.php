<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Permission;

use ExtendsFramework\Authorization\Permission\Exception\InvalidPermissionNotation;

class Permission implements PermissionInterface
{
    /**
     * Character to match everything in a section of the notation.
     *
     * @var string
     */
    private $wildcard = '*';

    /**
     * Character to divide notation sections.
     *
     * @var string
     */
    private $divider = ':';

    /**
     * Character to divide parts in a section.
     *
     * @var string
     */
    private $separator = ',';

    /**
     * Permission notation.
     *
     * @var string
     */
    private $notation;

    /**
     * Case sensitive regular expression to verify notation.
     *
     * @var string
     */
    private $pattern = '/^(\*|\w+(,\w+)*)(:(\*|\w+(,\w+)*))*$/';

    /**
     * Permission constructor.
     *
     * @param string $notation
     * @throws InvalidPermissionNotation
     */
    public function __construct(string $notation)
    {
        if (!(bool)preg_match($this->pattern, $notation)) {
            throw new InvalidPermissionNotation($notation);
        }

        $this->notation = $notation;
    }

    /**
     * @inheritDoc
     */
    public function implies(PermissionInterface $permission): bool
    {
        if (!$permission instanceof static) {
            return false;
        }

        $left = $this->getSections();
        $right = $permission->getSections();
        $wildcard = $this->wildcard;

        foreach ($right as $index => $section) {
            if (!isset($left[$index])) {
                return true;
            }

            if (array_intersect($section, $left[$index]) === [] && !in_array($wildcard, $left[$index], true)) {
                return false;
            }
        }

        foreach (array_slice($left, count($right)) as $section) {
            if (!in_array($wildcard, $section, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get exploded notation string.
     *
     * @return array
     */
    private function getSections(): array
    {
        $sections = explode($this->divider, $this->notation);
        foreach ($sections as $index => $section) {
            $sections[$index] = explode($this->separator, $section);
        }

        return $sections;
    }
}
