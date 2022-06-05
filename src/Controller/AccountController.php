<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(Request $request, ArticleRepository $articleRep): Response
    {
        $user = $this->getUser();
         // $articlesFull = $articleRep->findAll();
         $offset = max(0, $request->query->getInt('offset', 0));
         $paginator = $articleRep->getArticleByAuthorPaginator($offset, $user);
         
        return $this->render('account/account.html.twig', [
            'user' => $user,
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::ARTICLE_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::ARTICLE_PER_PAGE),
        ]);
    }
}
