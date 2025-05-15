<?php

declare(strict_types=1);

namespace Acme;

class Container
{
    /** @var array<string, callable(Container): mixed> */
    private array $services = [];

    public function register(string $id, callable $factory): void
    {
        $this->services[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (!isset($this->services[$id])) {
            throw new \RuntimeException("Service $id not found");
        }
        return $this->services[$id]($this);
    }
}