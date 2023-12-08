<?php

namespace App\Controller;

use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset', name: 'app_reset_password')]
    public function edit(RequestStack $requestStack, Request $request,  EntityManagerInterface $entityManager): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();

        return $this->render('reset/index.html.twig', [
            'currentUrl' => $currentUrl,
        ]);
    }
}
