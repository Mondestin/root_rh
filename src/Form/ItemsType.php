<?php

namespace App\Form;

use App\Entity\Items;
use App\Entity\Tasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('desciption', TextType::class, [
                'label' => 'Description',
            ])
            ->add('task_id', EntityType::class, [
                'label' => 'Lier à la Tâche',
                'attr' => ['class' => 'form-control'],
                'class' => Tasks::class,
                'choice_label' => function ($task) {
                    return $task->getName();
                }
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'En cours' => 'En cours',
                    'Terminé' => 'Terminé',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Items::class,
        ]);
    }
}
