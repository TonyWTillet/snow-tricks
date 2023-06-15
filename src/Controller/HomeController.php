<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    private $urlGenerator;
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('/', name: 'app_home', methods : ['GET'])]
    public function index(TrickRepository $trickRepository, RequestStack $requestStack): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
         $currentUrl = $currentRequest->getPathInfo();
        return $this->render('home/index.html.twig', [
            $tricks = $trickRepository->findTrickWithRelations(20, 0),
            'tricks' => $tricks,
            'currentUrl' => $currentUrl,
        ]);

    }
}
