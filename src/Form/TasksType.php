<?php

namespace App\Form;

use App\Entity\Tasks;
use App\Entity\Customers;
use App\Entity\Departments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descriptions',
            ])
            ->add('price', NumberType::class, [
                'label' => "Prix"
            ])
            ->add('start_date', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date de fin',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'attr' => ['class' => 'form-control required'],
                'choices'  => [
                    'En cours' => 'En cours',
                ],
            ])
            ->add('department_id', EntityType::class, [
                'class' => Departments::class,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Departements',
                'choice_label' => function ($depts) {
                    return $depts->getDepartmentName();
                }
            ])
            ->add('customer', EntityType::class, [
                'label' => 'Client',
                'attr' => ['class' => 'form-control'],
                'class' => Customers::class,
                'choice_label' => function ($chef) {
                    return $chef->getFullName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
