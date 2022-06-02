<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authUtils): Response
    {
        //Get Login error if exists
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
