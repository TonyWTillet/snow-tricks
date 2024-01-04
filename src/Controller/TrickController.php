<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class TrickController extends AbstractController
{

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, private EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    #[Route('/trick/{slug}', name: 'app_trick', methods : ['GET', 'POST'])]
    public function index(Trick $trick, Request $request): Response
    {
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }
        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'currentUrl' => $currentUrl,
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/trick/delete/{id}', name: 'app_delete_trick', methods : ['GET', 'POST'])]
    public function delete(Trick $trick): Response
    {
        if ($this->getUser() !== $trick->getUser()) {
            $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer.');
            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }
        $this->entityManager->remove($trick);
        $this->entityManager->flush();
        $this->addFlash('success', 'La suppression du trick a été effectuée avec succès.');
        return $this->redirectToRoute('app_list_trick');
    }
}
