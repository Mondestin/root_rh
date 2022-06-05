<?php

namespace App\Form;

use App\Entity\Pointage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GestionPointageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comments', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('validate', ChoiceType::class, [
                'label' => 'Statut',
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'validé' => 'validé',
                    'rejeté' => 'rejeté',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pointage::class,
        ]);
    }
}
