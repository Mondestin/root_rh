<?php

namespace App\Form;

use App\Entity\Customers;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CustomersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company_name', TextType::class, [
                'label' => 'Société',
                'required' => true
            ])
            ->add('customer_name', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('customer_surname', TextType::class, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('customer_email', EmailType::class, [
                'label' => 'Email',
                'required' => true
            ])
            ->add('customer_phone_number', IntegerType::class, [
                'label' => 'Téléphone',
                'required' => true
            ])
            ->add('customer_adress', TextType::class, [
                'label' => 'Adresse',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customers::class,
        ]);
    }
}
