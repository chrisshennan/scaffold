<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Form\Author;

use App\Entity\Author;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('jobTitle')
            ->add('biography', TextareaType::class, options: [
                'attr' => [
                    'rows' => 5,
                ],
            ])
            ->add('email')
            ->add('website')
        ;
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
