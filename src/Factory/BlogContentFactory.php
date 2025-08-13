<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\BlogContent;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<BlogContent>
 */
final class BlogContentFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return BlogContent::class;
    }

    /**
     * @return array{
     *   author: AuthorFactory,
     *   content: string,
     *   slug: string,
     *   summary: string,
     *   title: string
     * }
     */
    protected function defaults(): array
    {
        return [
            'author' => AuthorFactory::new(),
            'content' => self::faker()->text(),
            'slug' => self::faker()->text(255),
            'summary' => self::faker()->text(1024),
            'title' => self::faker()->text(255),
        ];
    }
}
