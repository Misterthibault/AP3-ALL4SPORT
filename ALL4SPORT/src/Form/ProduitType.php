<?php

namespace App\Form;

use App\Entity\Entrepot;
use App\Entity\Magasin;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

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
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => '.png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp',
                ],
                'constraints' => [
                    new Image(
                        maxSize: '5M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Veuillez choisir une image JPEG, PNG ou WebP.',
                    ),
                ],
            ])
            ->add('magasins', EntityType::class, [
                'class' => Magasin::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
            ])
            ->add('entrepots', EntityType::class, [
                'class' => Entrepot::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
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
