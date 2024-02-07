<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(TrickRepository $trickRepository, RequestStack $requestStack, Request $request): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $trickRepository->getTrickPaginator($offset);

        return $this->render('home/index.html.twig', [
            'tricks' => $paginator,
            'previous' => $offset - TrickRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + TrickRepository::PAGINATOR_PER_PAGE),
            'currentUrl' => $currentUrl,
        ]);

    }
}
