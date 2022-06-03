<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/article', name: 'article')]
    public function add(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $article = $form->getData();
            $user = $this->getUser();
            $article->setAuthor($user);
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            dd();
            return $this->redirectToRoute('homepage');
        }

    return $this->renderForm('article/add-article.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/article/{id}', name: 'page-article')]
    function show(int $id, ArticleRepository $articleRep): Response
    {
        $article = $articleRep->findOneBy(['id' => $id]);
        // dd($article);
        return $this->render('article/page-article.html.twig', [
            'article' => $article,
        ]);
    }
}
