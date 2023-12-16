<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, RequestStack $requestStack): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'currentUrl' => $currentUrl,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/reset', name: 'app_reset_password')]
    public function forgottenPassword(RequestStack $requestStack, Request $request,  EntityManagerInterface $entityManager): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();

        return $this->render('security/reset_password_request.html.twig', [
            'currentUrl' => $currentUrl,
        ]);
    }
}
