<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickFormType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\PictureService;
use App\Service\VideoService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function index(Trick $trick, Request $request, CommentRepository $commentRepository): Response
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

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($offset, $trick->getId());
        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/add-trick', name: 'app_add_trick', methods : ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,VideoService $videoService, PictureService $pictureService): Response
    {
        if (empty($this->getUser())) {
            $this->addFlash('danger', 'Vous devez être connecté pour ajouter un trick.');
            return $this->redirectToRoute('app_login');
        }
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $pictures = $form->get('pictures')->getData();
                $videos = $form->get('videos')->getData();
                foreach ($pictures as $picture) {
                    $folder = 'tricks';
                    $file = $pictureService->add($picture, $folder, 300, 300);
                    $img = new Picture();
                    $img->setName($file);
                    $img->setAlt($trick->getName());
                    $trick->addPicture($img);
                }
                foreach ($videos as $video) {
                    if (!$videoService->verifyPost($video)) {
                        $this->addFlash('warning', 'La vidéo ('.$video.') n\'est pas valide.');
                        continue;
                    }
                    $vid = new Video();
                    $vid->setName($video);
                    $trick->addVideo($vid);
                }
                $slug = $slugger->slug($trick->getName())->lower();
                $trick->setSlug($slug);
                $trick->setUser($this->getUser());
                if (!empty($pictures)) {
                    $trick->setDefaultPicture($trick->getPictures()->first());
                }
                $entityManager->persist($trick);
                $entityManager->flush();
                $this->addFlash('success', 'Le trick a été ajouté avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout du trick.');
                return $this->render('trick/add.html.twig', [
                    'trickForm' => $form->createView(),
                    'currentUrl' => $currentUrl,
                ]);
            }
            return $this->redirectToRoute('app_edit_trick', ['id' => $trick->getId()]);
        }

        return $this->render('trick/add.html.twig', [
            'trickForm' => $form->createView(),
            'currentUrl' => $currentUrl,
        ]);
    }

    #[Route('/trick/edit/{id}', name: 'app_edit_trick', methods : ['GET', 'POST'])]
    public function edit(Trick $trick, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PictureService $pictureService, VideoService $videoService): Response
    {
        if (empty($this->getUser())) {
            $this->addFlash('danger', 'Vous devez être connecté pour modifier un trick.');
            return $this->redirectToRoute('app_login');
        }
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $pictures = $form->get('pictures')->getData();
                $videos = $form->get('videos')->getData();
                $defaultPictureId = $form->get('default_picture')->getData();
                foreach ($pictures as $picture) {
                    $folder = 'tricks';
                    $file = $pictureService->add($picture, $folder, 300, 300);
                    $img = new Picture();
                    $img->setName($file);
                    $img->setAlt($trick->getName());
                    $trick->addPicture($img);
                }
                foreach ($videos as $video) {
                    if (empty($video)) {
                        continue;
                    }
                    if (!$videoService->verifyPost($video)) {
                        $this->addFlash('warning', 'La vidéo ('.$video.') n\'est pas valide.');
                        continue;
                    }
                    $vid = new Video();
                    $vid->setName($video);
                    $trick->addVideo($vid);
                }
                $slug = $slugger->slug($trick->getName())->lower();
                $trick->setSlug($slug);
                if (!empty($defaultPictureId)) {
                    $defaultPicture = $entityManager->getRepository(Picture::class)->find($defaultPictureId);
                    $trick->setDefaultPicture($defaultPicture);
                }
                $trick->setUpdatedAt(new \DateTime());
                $trick->setUser($this->getUser());
                $entityManager->persist($trick);
                $entityManager->flush();
                $this->addFlash('success', 'Le trick a été modifié avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de la modification du trick.');
                return $this->render('trick/edit.html.twig', [
                    'trickForm' => $form->createView(),
                    'currentUrl' => $currentUrl,
                ]);
            }
            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'trickForm' => $form->createView(),
            'currentUrl' => $currentUrl,
        ]);
    }

    #[Route('/trick/delete/image/{id}', name: 'app_delete_trick_image', methods : ['DELETE', 'GET'])]
    public function deleteImage(Picture $picture, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): JsonResponse|Response
    {
        if (empty($this->getUser())) {
            $this->addFlash('danger', 'Vous devez être connecté pour supprimer une image.');
            return $this->redirectToRoute('app_login');
        }

        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $data['_token'])) {
            $name = $picture->getName();
            if ($pictureService->delete($name, 'tricks')) {
                $entityManager->remove($picture);
                $entityManager->flush();
                return new JsonResponse(['success' => 'L\'image a été supprimée avec succès.'], 200);
            }
            return new JsonResponse(['danger' => 'Erreur de suppression'], 400);

        }

       return new JsonResponse(['danger' => 'Token invalide'], 400);
    }

    #[Route('/trick/delete/video/{id}', name: 'app_delete_trick_video', methods : ['DELETE', 'GET'])]
    public function deleteVideo(Video $video, Request $request, EntityManagerInterface $entityManager): JsonResponse|Response
    {
        if (empty($this->getUser())) {
            $this->addFlash('danger', 'Vous devez être connecté pour supprimer une image.');
            return $this->redirectToRoute('app_login');
        }

        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$video->getId(), $data['_token'])) {
            $name = $video->getName();
            if (!empty($name)) {
                $entityManager->remove($video);
                $entityManager->flush();
                return new JsonResponse(['success' => 'La vidéo a été supprimée avec succès.'], 200);
            }
            return new JsonResponse(['danger' => 'Erreur de suppression'], 400);

        }

        return new JsonResponse(['danger' => 'Token invalide'], 400);
    }

}
