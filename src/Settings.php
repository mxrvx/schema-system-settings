<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

/**
 * Manage the set of settings associated with the schema
 *
 * @implements \IteratorAggregate<string, Setting>
 */
final class Settings implements \IteratorAggregate, \Countable
{
    /** @var Setting[] */
    private array $settings = [];

    public function count(): int
    {
        return \count($this->settings);
    }

    public function has(string $key): bool
    {
        return isset($this->settings[$key]);
    }

    /**
     * Get setting by key
     */
    public function get(string $key): ?Setting
    {
        if ($this->has($key)) {
            return $this->settings[$key];
        }

        return null;
    }

    public function set(string $key, Setting $setting): self
    {
        if (!$this->has($key)) {
            $this->settings[$key] = $setting;
        }
        return $this;
    }

    public function remove(string $key): self
    {
        unset($this->settings[$key]);
        return $this;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->settings);
    }

    /**
     * Get property keys
     */
    public function getKeys(): array
    {
        return \array_keys($this->settings);
    }

    /**
     * Cloning.
     */
    public function __clone()
    {
        foreach ($this->settings as $name => $field) {
            $this->settings[$name] = clone $field;
        }
    }
}
