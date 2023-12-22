<?php

namespace App\Controller;

use App\Entity\Token;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/reset', name: 'app_reset_password')]
    public function forgottenPassword(RequestStack $requestStack, Request $request,  UserRepository $userRepository, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $entityManagerInterface, TokenRepository $tokenRepository, SendMailService $sendMailService): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();

        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                $token = $tokenGeneratorInterface->generateToken();
                $token = (new Token())->setValue($token)->setUser($user)->setCreatedAt(new \DateTime());

                $user->setToken($token);
                try {
                    $entityManagerInterface->persist($user);
                    $entityManagerInterface->flush();
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer.');
                    return $this->redirectToRoute('app_login');
                }

                $url = $this->generateUrl('app_new_password', ['token' => $token->getValue()], UrlGeneratorInterface::ABSOLUTE_URL);

                $context = [
                    'url' => $url,
                    'user' => $user,
                    'token' => $token,
                ];

                try {
                    $sendMailService->send(
                        'no-reply@snowtricks.com',
                        $user->getEmail(),
                        'Réinitialisation de votre mot de passe',
                        'reset_password',
                        $context
                    );
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer.');
                    return $this->redirectToRoute('app_login');
                }

                $this->addFlash('success', 'Un email vous a été envoyé pour réinitialiser votre mot de passe.');
                return $this->redirectToRoute('app_login');

            }

            $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'currentUrl' => $currentUrl,
            'requestPassForm' => $form->createView(),
        ]);
    }

    #[Route('/reset/{token}', name: 'app_new_password')]
    public function resetPass(RequestStack $requestStack,string $token, Request $request, UserRepository $userRepository, TokenRepository $tokenRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $currentUrl = $currentRequest->getPathInfo();

        try {
            $token = $tokenRepository->findBy(['value' => $token])[0];
            $user = $token->getUser();

            if ($user) {
                $form = $this->createForm(ResetPasswordFormType::class);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $userRepository->removeToken($token, $entityManager);
                    $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
                    return $this->redirectToRoute('app_login');
                }
                return $this->render('security/reset_password.html.twig', [
                    'resetForm' => $form->createView(),
                    'currentUrl' => $currentUrl,
                ]);
            }
            $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer.');
            return $this->redirectToRoute('app_login');

        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer.');
            return $this->redirectToRoute('app_login');
        }

    }
}
