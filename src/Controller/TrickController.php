<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    #[Route('/trick/{slug}', name: 'app_trick', methods : ['GET'])]
    public function index(Trick $trick): Response
    {
        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
        ]);
    }
}
