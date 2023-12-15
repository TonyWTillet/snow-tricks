<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UsersAuthenticator;
use App\Service\JwtService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class RegistrationController extends AbstractController
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'app_register')]
    public function register(JwtService $jwtService,SendMailService $sendMailService,Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $currentUrl = $this->urlGenerator->generate(
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route'),
            $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate jwt token
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];
            $payload = [
                'user_id' => $user->getId(),
            ];

            $token = $jwtService->generateToken(
                $header,
                $payload,
                $this->getParameter('app.jwtsecret'),
                10800
            );

            // do anything else you need here, like send an email
            $sendMailService->send(
                'noreply@snowtricks.com',
                $user->getEmail(),
                'Activation de votre compte Snowtricks',
                'registration',
                ['user' => $user, 'token' => $token]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'currentUrl' => $currentUrl,
        ]);
    }

    #[Route('/activate/{token}', name: 'app_activate')]
    public function activate($token, JwtService $jwtService, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if(!$jwtService->isExpired($token) && $jwtService->verifyToken($token)  && $jwtService->getPayload($token)['user_id'] && $jwtService->checkToken($token, $this->getParameter('app.jwtsecret')) ){
            $payload = $jwtService->getPayload($token);
            $user = $userRepository->find($payload['user_id']);
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $this->addFlash('success', 'Votre compte a bien été activé !');
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                $this->addFlash('info', 'Votre compte est déjà activé !');
            }
            return $this->redirectToRoute('app_users_edit');
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré!');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/resend-token', name: 'app_resend_token')]
    public function resendToken(JwtService $jwtService, SendMailService $sendMailService, UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour effectuer cette action !');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('info', 'Votre compte est déjà activé !');
            return $this->redirectToRoute('app_users_edit');
        }

        // generate jwt token
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $payload = [
            'user_id' => $user->getId(),
        ];

        $token = $jwtService->generateToken(
            $header,
            $payload,
            $this->getParameter('app.jwtsecret'),
            10800
        );

        // do anything else you need here, like send an email
        $sendMailService->send(
            'noreply@snowtricks.com',
            $user->getEmail(),
            'Activation de votre compte Snowtricks',
            'registration',
            ['user' => $user, 'token' => $token]
        );

        $this->addFlash('success', 'Un nouveau token a été envoyé à votre adresse mail !');
        return $this->redirectToRoute('app_users_edit');
    }

}
