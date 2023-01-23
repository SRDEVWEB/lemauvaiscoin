<?php
namespace App\FormCompte;


use App\Entity\Owner;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\FloatType;
//use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom : ',
                'attr' => ['class'=>'form-control w-75'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('first_name',TextType::class, ['label' => 'Prénom : ',
                'attr' => ['class'=>''], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('adress1',TextType::class, ['label' => 'Adresse : ',
                'attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('adress2',TextType::class, ['label' => 'Adresse (suite) : ',
                'data' => '-','attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('code_postal',TextType::class, ['label' => 'Code postal : ',
                'attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('city',TextType::class, ['label' => 'Ville : ',
                'attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('tel', TextType::class, ['label' => 'Téléphone : ',
        'attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            //->add('visible')
            ->add('email', TextType::class, ['label' => 'E-mail : ',
                'attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('password',PasswordType::class, ['label' => 'Mot de passe : ',
                'attr' => ['class'=>'p-1'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('password2', PasswordType::class,['label' => 'Vérification du Mot de passe : ', "mapped"=> false,'attr' => ['class'=>'form-control w-75'], 'label_attr' => ['class' =>'form-label m-1 p-2 text-white']])
            ->add('Envoyer', SubmitType::class, ['label' => 'Envoyer',
                'attr' => ['class'=>'subbutt']])
//         <button class="subbutt" type="submit"><h6 class="px-1"><p class="Penvoie m-1">Se connecter</p></h6>
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Owner::class,
        ]);
    }
}