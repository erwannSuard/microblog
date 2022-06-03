<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
        }

    return $this->renderForm('article/add-article.html.twig', [
            'form' => $form,
        ]);
    }
}
