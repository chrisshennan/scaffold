<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Form\Core;

use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MarkdownTextAreaType extends AbstractType
{
    #[Override]
    public function getParent(): string
    {
        return TextareaType::class;
    }

    #[Override]
    public function getBlockPrefix(): string
    {
        return 'markdown_textarea';
    }
}
