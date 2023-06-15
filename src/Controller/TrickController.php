<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickController extends AbstractController
{

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    #[Route('/trick/{slug}', name: 'app_trick', methods : ['GET'])]
    public function index(Trick $trick): Response
    {
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'currentUrl' => $currentUrl,
        ]);
    }
}
