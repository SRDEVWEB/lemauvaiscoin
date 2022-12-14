<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceController extends AbstractController
{
    #[Route('/price', name: 'app_price')]
    public function pricing(): Response
    {
        return $this->render('price/price.html.twig', [
            'controller_name' => 'PriceController',
        ]);
    }
}
