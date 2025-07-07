<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Model;

class PageLink
{
    /**
     * @param PageLink[] $children an array of child PageLink objects
     */
    public function __construct(
        public string $title,
        public string $url,
        public array $children = [],
    ) {
    }
}
