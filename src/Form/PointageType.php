<?php

namespace App\Form;

use App\Entity\Pointage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PointageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Pointage',
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'Check In' => 'CheckedIn',
                    'Check Out' => 'CheckedOut',
                ],
            ])
            ->add('justify', TextareaType::class, [
                'label' => 'Justification',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pointage::class,
        ]);
    }
}
