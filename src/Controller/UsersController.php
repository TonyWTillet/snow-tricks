<?php

namespace App\Controller;

use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/users/edit', name: 'app_users_edit')]
    public function edit(RequestStack $requestStack, Request $request,  EntityManagerInterface $entityManager): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();

        $user = $this->getUser();
        $form = $this->createForm(UserUpdateType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());

            $entityManager->persist($user); // Necessary when updating an existing entity
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été mises à jours.');

            return $this->redirectToRoute('app_users_edit');
        }

        return $this->render('users/edit.html.twig', [
            'currentUrl' => $currentUrl,
            'userEditForm' => $form->createView(),
        ]);
    }


}
