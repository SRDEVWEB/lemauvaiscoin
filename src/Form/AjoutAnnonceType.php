<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit')

            ->add('dateDepot')
            ->add('dateUpdate')
            ->add('dateSeil')
            ->add('visible')
            ->add('prix')
            ->add('commentaires')
            ->add('description')
            ->add('couleur')
            ->add('poids')
            ->add('hauteur')
            ->add('largeur')
            ->add('profondeur')
            ->add('dimensions')
            ->add('img')
            ->add('categorie')
            ->add('owner')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
