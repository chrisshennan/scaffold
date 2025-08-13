<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BlogContentRepository;
use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Entity\BlogContent as ScaffoldBlogContent;

#[ORM\Entity(repositoryClass: BlogContentRepository::class)]
#[ORM\Table(name: 'scaffold_blog')]
class BlogContent extends ScaffoldBlogContent
{
}
