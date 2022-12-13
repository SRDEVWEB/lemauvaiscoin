<?php

namespace App\Form;

use App\Entity\Annonce;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDepot', \Symfony\Component\Form\Extension\Core\Type\DateType::class,[
                'label' => "Date du dépôt de l'annonce : ",
                    'data' => new \DateTime()
            ])
            ->add('dateUpdate', \Symfony\Component\Form\Extension\Core\Type\DateType::class,[
                'data' => new \DateTime(),
                'label' => "Date de la dernière mise à jour de l'annonce : ",
            ])
            ->add('visible')
            ->add('prix')
            ->add('commentaires', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'attr'=>['placeholder'=>'Faites un commentaire...']])
            ->add('description',\Symfony\Component\Form\Extension\Core\Type\TextType::class,['attr'=>['placeholder'=>'Ajouter la description du produit']])
            ->add('couleur', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
        'attr'=>['placeholder'=>'None']])
            ->add('poids', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('hauteur', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('largeur', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('profondeur', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('dimensions', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('produit')
            ->add('img')
            ->add('categorie')
            ->add('owner')
            ->add('notes')
            ->add('Envoyer', SubmitType::class,["label"=>"Valider"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
