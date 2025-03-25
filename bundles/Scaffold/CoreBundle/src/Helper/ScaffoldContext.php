<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Helper;

class ScaffoldContext
{
    /**
     * @var array<string, mixed>
     */
    private array $kvStore = [];

    public function get(string $key): mixed
    {
        return $this->kvStore[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->kvStore[$key] = $value;
    }
}
