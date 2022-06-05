<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Employee;
use App\Entity\Departments;
use App\Entity\EmployeeCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employee_name', TextType::class, [
                'label' => 'Prénom(s)'
            ])
            ->add('employee_surname', TextType::class, [
                'label' => 'Nom(s)'
            ])
            ->add('employee_sexe', ChoiceType::class, [
                'label' => 'Sexe',
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'Masculin' => 'Masculin',
                    'Féminin' => 'Féminin',
                ],
            ])
            ->add('employee_dob', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date de Naissance'
            ])
            ->add('employee_email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('employee_photo', FileType::class, [
                'label' => 'Photo (PNG, JPEG)',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('employee_phone', IntegerType::class, [
                'label' => 'Téléphone'
            ])
            ->add('hire_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de signature du contrat',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('employee_adress', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('employee_status', ChoiceType::class, [
                'label' => 'Statut',
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'En service' => 'En service',
                    'En vacances' => 'En vacances',
                ],
            ])
            ->add('department', EntityType::class, [
                'label' => 'Departement',
                'attr' => ['class' => 'form-control'],
                'class' => Departments::class,
                'choice_label' => function ($dept) {
                    return $dept->getDepartmentName();
                }
            ])
            ->add('kpa', EntityType::class, [
                'label' => 'Categorie',
                'attr' => ['class' => 'form-control'],
                'class' => EmployeeCategory::class,
                'choice_label' => function ($zone) {
                    return $zone->getName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
