<?php
namespace App\FormCompte;


use App\Entity\Owner;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('first_name')
            ->add('adress1')
            ->add('adress2')
            ->add('code_postal')
            ->add('city')
            ->add('tel')
            //->add('visible')
            ->add('email')
            ->add('password',PasswordType::class)
            ->add('password2', PasswordType::class,["mapped"=> false])
//            ->add('roles')
            ->add('Envoyer', SubmitType::class,[
                "label"=>"Valider"])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Owner::class,
        ]);
    }
}