<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class ListTrickController extends AbstractController
{
    private $urlGenerator;
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    #[Route('/tricks', name: 'app_list_trick')]
    public function index(TrickRepository $trickRepository): Response
    {
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $tricks = $trickRepository->findTrickWithRelations(99, 0);
        return $this->render('list_trick/index.html.twig', [
            'tricks' => $tricks,
            'currentUrl' => $currentUrl,
        ]);
    }
}
