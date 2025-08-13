<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Form\Blog;

use Override;
use Scaffold\CoreBundle\Entity\BlogContent;
use Scaffold\CoreBundle\Form\Core\MarkdownTextAreaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogContentType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('summary')
            ->add('content', MarkdownTextAreaType::class, [
                'attr' => [
                    'class' => 'h-full w-full',
                    'data-scaffold--markdown-textarea-target' => 'content',
                ],
            ])
            ->add('slug')
            ->add('mainImage')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('canonicalUrl')
        ;
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogContent::class,
        ]);
    }
}
