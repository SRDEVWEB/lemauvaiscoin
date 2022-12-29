<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\Form\AddType;
use App\services\AddsService;
use App\services\ExampleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
                                AddsService $adds
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
