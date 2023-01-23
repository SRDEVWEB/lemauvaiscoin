<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\FormCompte\AddCompteType;
use App\Repository\AnnonceRepository;
use App\services\AddsService;
use App\services\AnnonceService;
use App\services\CategoryService;
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
    public function index( EntityManagerInterface $em,
                           Request $request,
                           ExampleService $exampleService,
                           AddsService $adds,
                           AnnonceRepository $annonceRepository,
                           AnnonceService $annonceService,
                           CategoryService $categoryService, int $id=1, int $page = 1):
    Response
    {
        $seller = $exampleService->getSeller();
        $add = $adds->getAdds();
        $filters = [];
//
            $user = $this->getUser();
            if (!$user instanceof Owner) {
       //        throw new AccessDeniedHttpException('Vous devez êtes connecté!');
            }

        if ($request->get('query') !== null) {
            $filters['query'] = $request->get('query');
        }

        if ($request->get('categorie') !== null && is_array($request->get('categorie'))) {

            $filters['in_categorie'] = $request->get('categorie');
        }

        if ($request->get('price_sup') !== null) {
            $filters['price_sup'] = (float)$request->get('price_sup');
        }

        if ($request->get('price_inf') !== null) {
            $filters['price_inf'] = (float)$request->get('price_inf');
        }

//        if ($request->get('date_depot') !== null && is_array($request->get('date_depot'))) {
//            $filters['date_depot'] = $request->get('date_depot');
//        }

        $order = [];
        $allowedOrder = ['prix', 'date_depot', 'categorie', 'produit'];
        if ($request->get('order') !== null && str_contains($request->get('order'), ',')) {
            $o_ = explode(',', $request->get('order'));
            if (in_array($o_[0], $allowedOrder, true)) {
                $order[$o_[0]] = strtoupper($o_[1]);
            }
        }

        try {
            $annonces = $annonceService->getAnnonces($filters, $order, $page, $limit);
        } catch (\Throwable $e) {
            if ($e->getCode() === 10) {
                // page does not exists
                throw $this->createNotFoundException('La page n\'existe pas !');
            }
            $annonces = [
                'results' => [],
                'count' => 0,
                'totalPages' => 1,
                'error' => $e->getMessage(),
            ];
        }

        return $this->render('default/index.html.twig', [
            'add' => $add,
            'queryParams' => http_build_query($_GET),
            'annonceQuery' => $annonces,
            'actualPage' => $page,
            'categorie' => $categoryService->getAllCategories(),
            'controller_name' => 'DefaultController',
            'seller' => $seller,
            'id' => $id,
            'listAnnonceForm'=> $annonceRepository->findLastDepot(1),
        ]);
    }

    #[Route('/compteController', name: 'new_compte')]
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
                echo('OK!!!');
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