<?php

namespace App\Controller;

use App\services\AnnonceService;
use App\Entity\Annonce;
use App\Entity\Owner;
use App\Form\AddType;
use App\Form\ListType;
use App\Repository\AnnonceRepository;
use App\services\AddsService;
use App\services\CategoryService;
use App\services\ExampleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/list', name: 'app_annonce')]
    #[Route('/annonce/list/{page}', name: 'app_annonce_page')]
    public function index( EntityManagerInterface $em,
                                Request $request,
                                AddsService $adds, AnnonceRepository $annonceRepository,
                           AnnonceService $annonceService,
                           CategoryService $categoryService, int $page = 1,
    ): Response
    {
        $add = $adds->getAdds();

//        $page = (int)$request->get('page', 1);

        $limit = (int)$request->get('limit', 15);

        $filters = [];

//
//        if ($request->get('imgcheck')) {
//            $filters['imgcheck'] = $request->get('imgcheck');
//        }

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
//
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

        return $this->render('annonce/listAnnonces.html.twig', [
            'controller_name' => 'Annonces',
            'add' => $add,
// Fonctionne parfaitement: mais retiré de listAnnonces.html.twig pour coller à la demande de Florian
//            'listAnnonceForm'=> $annonceRepository->findByExampleField(1),
            'queryParams' => http_build_query($_GET),
            'annonceQuery' => $annonces,
            'actualPage' => $page,
            'categorie' => $categoryService->getAllCategories(),
        ]);
    }

    #[Route('/annonce/new', name: 'new_annonce')]
    public function addannonce( EntityManagerInterface $em,
                                Request $request, ExampleService $exampleService,
                                AddsService $adds, SluggerInterface $slugger,
    ): Response {
        $add = $adds->getAdds();
        $user = $this->getUser();
        if (!$user instanceof Owner) {
            throw new AccessDeniedHttpException('Vous devez êtes connecté!');
        }

        $annonce = new Annonce();
        $annonce->setVisible('1');
        $annonce->setOwner($user);
        $annonce->setCommentaires("Ajouter un commentaire...");

        $annonce->setPrix('0');
$annonce->setDescription('Ajoutez une description...');
$annonce->setCouleur('orange');
$annonce->setPoids('0');
$annonce->setHauteur('0');
$annonce->setLargeur('0');
$annonce->setProfondeur('0');
$annonce->setDimensions('0');

        $form=$this->createForm(AddType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $annonce_image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($annonce_image) {
                $originalFilename = pathinfo($annonce_image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename .'-' . uniqid('', true).'.'.$annonce_image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $annonce_image->move(
                        $this->getParameter('annonce_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $annonce->setImageFile($newFilename);
            }


            $em->persist($annonce);

            $em->flush();
        }
        return $this->renderForm('annonce/index.html.twig',[
            'add' => $add,
            'form'=> $form,
            "controller_name"=>"Ajouter une nouvelle annonce"
        ]);
    }














}
