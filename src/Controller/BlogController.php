<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class BlogController extends AbstractController
{
    #[Route('/blog/{username}', name: 'blog')]
    public function index(UserRepository $userRep, ArticleRepository $articleRep ,string $username): Response
    {
        $user = $userRep->findOneBy(['username' => $username]);
        return $this->render('blog/index.html.twig', [
            'user' => $user,
        ]);
    }
}
