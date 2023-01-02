<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\Form\AddType;
use App\services\AddsService;
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
    #[Route('/annonce', name: 'app_annonce')]
    public function index(): Response
    {
        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'Annonces',
        ]);
    }

    #[Route('/annonce/new', name: 'new_annonce')]
    public function addannonce( EntityManagerInterface $em,
                                Request $request, ExampleService $exampleService,
                                AddsService $adds, SluggerInterface $slugger
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
