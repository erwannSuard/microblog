<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(UserRepository $ur): Response
    {
        $user = $this->getUser();
        // dd($user);
        $userArticles = $user->getArticles();
        // dd($userArticles);
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'articles' => $userArticles,
        ]);
    }
}
