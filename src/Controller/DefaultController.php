<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\FormCompte\AddCompteType;
use App\Repository\AnnonceRepository;
use App\services\AddsService;
use App\services\CityService;
use App\services\ExampleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
//    Page d'accueil'
    #[Route('/', name: 'app.home')]
    public function index( ExampleService $exampleService,
                           AddsService $adds,
                           AnnonceRepository $annonceRepository, int $id=1): Response
    {
        $seller = $exampleService->getSeller();
        $add = $adds->getAdds();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'seller' => $seller,
            'add' => $add,
            'id' => $id,
            'listAnnonceForm'=> $annonceRepository->findLastDepot(1),
            'queryParams' => http_build_query($_GET),
        ]);
    }

    #[Route('/compte', name: 'new_compte')]
    public function addCompte( EntityManagerInterface $em,
                               Request $request,UserPasswordHasherInterface $passwordHasher, ExampleService $exampleService,
                               AddsService $adds,
    ): Response
    {

        $compte= new Owner();
        $compte->setVisible('1');
        $compte->setRoles(["ROLE_USER"]);

        $form=$this->createForm(AddCompteType::class,$compte);
        $form->handleRequest($request);
        $add = $adds->getAdds();

        if($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword =$form->get('password2')->getData();

            if ($compte->getPassword()===$plaintextPassword) {
                $plaintextPassword = $compte->getPassword();
                $hashedPassword = $passwordHasher->hashPassword($compte, $plaintextPassword);
                $compte->setPassword($hashedPassword);

                $em->persist($compte);
                $em->flush();
            }else{
                echo('Le mot de passe ne correspond pas!!!');
           }
        }
        return $this->renderForm('compte/index.html.twig', [
            'formCompte'=>$form,
            'add' => $add,
            'controller_name' => 'Créer un compte'
        ]);
    }



    #[Route('/annonce/id', name: 'ads.display.simple', requirements: ['id' => '^\d+'])]
    public function displaySimple(
        ExampleService $exampleService,
        AddsService $adds,
        int $id=1,

    ): Response {
        $seller = $exampleService->getSeller();
        $add = $adds->getAdds();

        //dump($seller);
        //die;

        return $this->render('default/ad.display.html.twig', [
            'controller_name' => 'DefaultController',
            'seller' => $seller,
            'add' => $add,
            'id' => $id
        ]);
    }

    #[Route('/annonce/{cat}/{id}', name: 'ads.display', requirements: ['id' => '^\d+', 'cat' => '[a-z][a-z0-9_-]+'])]
    public function display(string $cat, int $id): Response
    {
        return $this->render('default/ad.display.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}