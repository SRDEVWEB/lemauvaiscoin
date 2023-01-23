<?php

namespace App\Form;

use App\Entity\Annonce;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\FloatType;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class AddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('produit')
            ->add('categorie')
            ->add('prix')
            ->add('commentaires', TextType::class,[
                'attr'=>['placeholder'=>'Faites un commentaire...']])
            ->add('description',TextType::class,['attr'=>['placeholder'=>'Ajouter la description du produit']])
            ->add('couleur', TextType::class,[
        'attr'=>['placeholder'=>'None']])
            ->add('poids', TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('hauteur', TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('largeur', TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('profondeur', TextType::class,[
                'attr'=>['placeholder'=>'0']])
            ->add('dimensions', TextType::class,[
                'attr'=>['placeholder'=>'0']])
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
            ->add('Envoyer', SubmitType::class, ['label' => 'Envoyer',
                'attr' => ['class'=>'subbutt']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
