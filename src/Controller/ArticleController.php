<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Form\CommentType;
use App\Repository\CommentRepository;

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
            return $this->redirectToRoute('homepage');
        }

    return $this->renderForm('article/add-article.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/article/{id}', name: 'page-article')]
    function show(int $id, ArticleRepository $articleRep, CommentRepository $commentRep, Request $request): Response
    {

        
        //Affichage de l'article par ID
        $article = $articleRep->findOneBy(['id' => $id]);
        $offset = max(0, $request->query->getInt('offset', 0));
        //On trouve les commentaires
        $paginator = $commentRep->getCommentPaginator($article, $offset);
        // //On trouve les commentaires
        // $comments = $commentRep->findBy(['article' => $id]);
        // dd($comments);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setArticle($article);
            $this->entityManager->persist($comment);
            // dd($comment);
            $this->entityManager->flush();
            //Nouveau commentaire 
            $comments = $commentRep->findBy(['article' => $id]);
            unset($form);
            unset($comment);
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment); 
            $this->redirect($this->generateUrl('page-article', array('id' => $id)));
        }
        return $this->renderForm('article/page-article.html.twig', [
            'article' => $article,
            'form' => $form,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::COMMENT_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::COMMENT_PER_PAGE),
        ]);
    }
}
