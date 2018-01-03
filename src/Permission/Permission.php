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
    protected $wildcard = '*';

    /**
     * Character to divide notation sections.
     *
     * @var string
     */
    protected $divider = ':';

    /**
     * Character to divide parts in a section.
     *
     * @var string
     */
    protected $separator = ',';

    /**
     * Permission notation.
     *
     * @var string
     */
    protected $notation;

    /**
     * Case sensitive regular expression to verify notation.
     *
     * @var string
     */
    protected $pattern = '/^(\*|\w+(,\w+)*)(:(\*|\w+(,\w+)*))*$/';

    /**
     * Permission constructor.
     *
     * @param string $notation
     * @throws InvalidPermissionNotation
     */
    public function __construct(string $notation)
    {
        if ((bool)preg_match($this->getPattern(), $notation) === false) {
            throw new InvalidPermissionNotation($notation);
        }

        $this->notation = $notation;
    }

    /**
     * @inheritDoc
     */
    public function implies(PermissionInterface $permission): bool
    {
        if (! $permission instanceof static) {
            return false;
        }

        $left = $this->getSections();
        $right = $permission->getSections();
        $wildcard = $this->getWildcard();

        foreach ($right as $index => $section) {
            if (array_key_exists($index, $left) === false) {
                return true;
            }

            if (array_intersect($section, $left[$index]) === []
                && in_array($wildcard, $left[$index], true) === false) {
                return false;
            }
        }

        foreach (array_slice($left, count($right)) as $section) {
            if (in_array($wildcard, $section, true) === false) {
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
    protected function getSections(): array
    {
        $sections = explode($this->getDivider(), $this->getNotation());
        foreach ($sections as $index => $section) {
            $sections[$index] = explode($this->getSeparator(), $section);
        }

        return $sections;
    }

    /**
     * Get wildcard.
     *
     * @return string
     */
    protected function getWildcard(): string
    {
        return $this->wildcard;
    }

    /**
     * Get divider.
     *
     * @return string
     */
    protected function getDivider(): string
    {
        return $this->divider;
    }

    /**
     * Get separator.
     *
     * @return string
     */
    protected function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * Get notation.
     *
     * @return string
     */
    protected function getNotation(): string
    {
        return $this->notation;
    }

    /**
     * Get pattern.
     *
     * @return string
     */
    protected function getPattern(): string
    {
        return $this->pattern;
    }
}
