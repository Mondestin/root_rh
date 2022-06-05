<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Controller\EntityManagerInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')

            ->add('username')
            ->add('role', EntityType::class, [
                'label' => 'Roles',
                'class' => Role::class,
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'row ml-3'],
                'choice_label' => function ($role) {
                    return $role->getAllRoles();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
