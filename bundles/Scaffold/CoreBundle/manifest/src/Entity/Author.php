<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Entity\Author as ScaffoldAuthor;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'scaffold_author')]
class Author extends ScaffoldAuthor
{
}
