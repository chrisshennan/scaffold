<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Form\Waitlist;

use Scaffold\CoreBundle\Entity\WaitlistEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class WaitlistEntryEmbedType extends AbstractType
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, options: [
                'attr' => [
                    'placeholder' => 'Enter your email address',
                ],
            ])
            ->setAction($this->router->generate('scaffold_core_waitlist_embedajax'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WaitlistEntry::class,
            'csrf_token_id' => WaitlistEntry::class,
        ]);
    }
}
