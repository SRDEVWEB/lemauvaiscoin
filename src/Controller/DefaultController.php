<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Owner;
use App\services\AddsService;
use App\services\CityService;
use App\services\ExampleService;
use App\Type\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app.home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/add/annonce', name: 'app.home.new')]
    public function addAnnonce(
        EntityManagerInterface $em,
        Request $request,
    ): Response {
        $user = $em->getRepository(Owner::class)
            ->findOneBy(['id' => 1]);
        if (!$user instanceof Owner) {
            throw new AccessDeniedHttpException();
        }

        $annonce = new Annonce(
            $user, 'Ma nouvelle annonce',
            0, false
        );
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // example of not mapped data
            $notes = $form->get('notes')?->getData();
            $em->persist($annonce);
            $em->flush();
        }

        return $this->render('default/add.annonce.html.twig', [
            'formulaireAddAnnonce' => $form->createView(),
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