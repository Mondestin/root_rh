<?php
/**
 * Author: Johan Mickael
 * Description: This class is used to describe an EmployeeCategoryType
 *              We will build dynamic form with
 */

namespace App\Form;

use App\Entity\EmployeeCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeCategoryType extends AbstractType
{
    // Generating an Employee Category FormType
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TypeTextType::class, ['label' => 'Nom', 'required' => true, 'invalid_message' => 'Entrez une catégorie d\'employé valide. ex: chauffeur'])
            ->add('base_salary', NumberType::class, ['html5' => true, 'label' => 'Salaire de base mensuel', 'required' => true, 'invalid_message' => 'Entrez un salaire valide. Ex: 1200', 'attr' => ['step' => '.01']])
            ->add('default_hour', NumberType::class, ['html5' => true, 'label' => 'Heures de travail hebdomadaire', 'required' => true, 'invalid_message' => 'Entrez une heure valide. Ex: 10', 'attr' => ['step' => '.01']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeCategory::class,
        ]);
    }
}
