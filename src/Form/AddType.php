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
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class AddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

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
            ->add('image', FileType::class, [
                'label' => 'Photo du produit',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('categorie')

            ->add('Envoyer', SubmitType::class,)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
