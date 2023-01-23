<?php
namespace App\Controller;

use App\FormCompte\AddCompteType;
use App\Repository\AnnonceRepository;
use App\Repository\OwnerRepository;
use App\services\AddsService;
use App\services\AnnonceService;
use App\services\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Annonce;
use App\Entity\Owner;
use App\Entity\Cart;
use App\Entity\OwnerLivraison;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

class CompteController extends AbstractController
{

    #[Route('/compte/moncompte', name: 'app_compte')]

    public function index( EntityManagerInterface $em,
                           Request $request,
                           AddsService $adds,UserPasswordHasherInterface $passwordHasher,
                           SluggerInterface $slugger, OwnerRepository $ownerRepository

    ): Response
    {
        $add = $adds->getAdds();
        $user = $this->getUser();

        if (!$user instanceof Owner) {
            throw new AccessDeniedHttpException('Vous devez êtes connecté!');
        }

        try {
            $utilisateur = $ownerRepository  ->findOneBySomeField($user);
        }
        catch (\Throwable $e) {
    }

        $form=$this->createForm(AddCompteType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword =$form->get('password2')->getData();

            if ($user->getPassword()===$plaintextPassword) {
                $plaintextPassword = $user->getPassword();
                $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
                $user->setPassword($hashedPassword);
                $em->flush();
            }else{
                echo('Le mot de passe ne correspond pas!!!');
            }


        }


            return $this->renderForm('compte/moncompte.html.twig', [
                'add' => $add,
                'form'=> $form,
                "controller_name" => "Visualiser mon compte"
            ]);
    }




}