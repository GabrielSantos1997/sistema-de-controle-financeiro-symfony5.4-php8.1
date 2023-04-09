<?php

namespace App\Form\TypeFactory;

use App\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as CoreType;

class ResourceTypeFactory extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', CoreType\TextType::class, [
                'required' => true,
            ])
            ->add('description', CoreType\TextType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
            'action' => '#',
            'csrf_protection' => false,
        ]);
    }
}
