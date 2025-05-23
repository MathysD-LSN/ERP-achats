<?php

namespace App\Form;

use App\Entity\Achat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateAchat', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('montant', NumberType::class, [
                'scale' => 2,  // Nombre de décimales
                'grouping' => true, // Pour afficher le séparateur de milliers
                'attr' => ['step' => '0.01'], // Autoriser les décimales
            ])
            ->add('fournisseur', TextType::class)
            ->add('id_stock', HiddenType::class, [
                'data' => 1, // Valeur par défaut du champ caché
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}
