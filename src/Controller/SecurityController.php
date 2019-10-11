<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $email = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login-page.html.twig', [
            'last_username' => $email,
            'error' => $error
        ]);
    }

    /**
     * @Route("/sign-up", name="sign-up")
     */
    public function signUp()
    {
        return $this->render('security/sign-up.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }
}
