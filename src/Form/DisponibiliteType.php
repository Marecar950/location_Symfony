<?php

namespace App\Form;

use App\Entity\Disponibilite;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vehicule', EntityType::class, [
                  'class' => Vehicule::class,
                  'choice_label' => function ($vehicule) {
                    return $vehicule->getMarque() . ' - ' . $vehicule->getMarque();
                },
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
            ])
            ->add('dateFin', null, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
            ])
            ->add('prixParJour', IntegerType::class, [
                'label' => 'Prix par jour',
                'attr' => ['min' => 0]            
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => true, 
                    'Non disponible' => false
                ],
                'placeholder' => 'Sélectionner un statut'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
