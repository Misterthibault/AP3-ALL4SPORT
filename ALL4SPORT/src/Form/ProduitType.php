<?php

namespace App\Form;

use App\Entity\Entrepot;
use App\Entity\Magasin;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('prix_vente')
            ->add('nom_fournisseur')
            ->add('description')
            ->add('nom')
            ->add('magasins', EntityType::class, [
                'class' => Magasin::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('entrepots', EntityType::class, [
                'class' => Entrepot::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
