<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Departments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DepartmentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('department_name', TextType::class, [
                'label' => 'Nom du departement',
                'required' => true,
            ])
            ->add('department_desc', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('manager_id', EntityType::class, [
                'label' => 'Chef de Departement',
                'attr' => ['class' => 'form-control'],
                'class' => Employee::class,
                'choice_label' => function ($chef) {
                    return $chef->getFullName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Departments::class,
        ]);
    }
}
