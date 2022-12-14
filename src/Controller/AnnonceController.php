<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\Form\AddType;
use App\Type\AnnonceType;
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
            'controller_name' => 'AnnonceController',
        ]);
    }

    #[Route('/annonce/new', name: 'new_annonce')]
    public function addannonce( EntityManagerInterface $em,
                                Request $request,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof Owner) {
            throw new AccessDeniedHttpException('You need to be logged');
        }

        $annonce = new Annonce();

        $form=$this->createForm(AddType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($annonce);
            $em->flush();
        }
        return $this->renderForm('annonce/index.html.twig',[
            'form'=> $form,
            "controller_name"=>"Ajouter une nouvelle annonce"
        ]);
    }














}
