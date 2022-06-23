<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

class JournalController extends AbstractController
{
    #[Route('/journal', name: 'journal')]
    public function index(Request $request, ArticleRepository $articleRep): Response
    {
        $user = $this->getUser();
        // $articlesFull = $articleRep->findAll();
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRep->getArticlePaginator($offset);
        // dd($articleRep->findAll());
        return $this->render('journal/journal.html.twig', [
            // 'articlesFull' => $articlesFull,
            'user' => $user,
            'articlesFull' => $paginator,
            'previous' => $offset - ArticleRepository::ARTICLE_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::ARTICLE_PER_PAGE),
        ]);
    }
}
