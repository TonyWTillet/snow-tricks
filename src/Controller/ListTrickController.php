<?php

namespace App\Controller;


use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


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
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $trickRepository->getTrickPaginator($offset);
        return $this->render('list_trick/index.html.twig', [
            'tricks' => $paginator,
            'previous' => $offset - TrickRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + TrickRepository::PAGINATOR_PER_PAGE),
            'currentUrl' => $currentUrl,
        ]);
    }
}
