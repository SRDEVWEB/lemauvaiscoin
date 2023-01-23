<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\services\AddsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login')]
    public function login(
        AuthenticationUtils $utils,
        AddsService $adds,
    ): Response {
        $user = $this->getUser();
        $add = $adds->getAdds();
        // si on est deja logger on affiche pas le form
        if ($user instanceof Owner) {
            return $this->redirectToRoute('app.home');
        }

        $error = $utils->getLastAuthenticationError();

        return $this->render('default/login.html.twig', [
            'error' => $error,
            'add' => $add,

        ]);
    }

    #[Route('/logout', name: 'app.logout')]
    public function logout(): Response
    {
        return $this->render('default/login.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
