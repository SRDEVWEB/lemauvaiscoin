<?php
namespace App\Controller;
use App\FormPanier\ViewPanierType;
use App\Repository\CartRepository;
use App\Repository\OwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    #[Route('/compte/monpanier', name: 'app_monpanier')]
    public function index(EntityManagerInterface $em,
                          Request $request, CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        $utilisateur=0;


            $utilisateur = $cartRepository  ->findOneBySomeField($user);



        $form=$this->createForm(ViewPanierType::class, $utilisateur);
        $form->handleRequest($request);
        return $this->renderForm('compte/monpanier.html.twig', [
            "controller_name" => "Visualiser mon panier"
        ]);
    }
}
