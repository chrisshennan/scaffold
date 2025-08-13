<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Author;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Author>
 */
final class AuthorFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Author::class;
    }

    /**
     * @return array{
     *   biography: string,
     *   email: string,
     *   name: string
     * }
     */
    protected function defaults(): array
    {
        return [
            'biography' => self::faker()->text(1024),
            'email' => self::faker()->text(255),
            'name' => self::faker()->text(255),
        ];
    }
}
