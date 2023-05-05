<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListTrickController extends AbstractController
{
    #[Route('/tricks', name: 'app_list_trick')]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('list_trick/index.html.twig', [
            $tricks = $trickRepository->findTrickWithRelations(99, 0),
            'tricks' => $tricks,
        ]);
    }
}
