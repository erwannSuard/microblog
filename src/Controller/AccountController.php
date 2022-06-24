<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PrivateMessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountController extends AbstractController
{


    //------------------------- Page principale account user -----------------------------
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





    //------------------------- Liste follow par l'user -----------------------------
    #[Route('/account/follow-list', name: 'account-follow-list')]
    public function followList(): Response
    {
        $user = $this->getUser();
        $followers = $user->getFollowedBy();
        $follows = $user->getFollows();
        // dd($follows);
         
        return $this->render('account/follow-list.html.twig', [
            'user' => $user,
            'follows' => $follows,
            'followers' => $followers,
        ]);
    }






    //------------------------- Articles des followed par l'user -----------------------------
    //---------------------------------------------
    //---------------------------------------------
    //---------------------------------------------
    //---------------------------------------------
    //-------------- À AMELIORER !!!!!!!!!!! ------
    //---------------------------------------------
    //---------------------------------------------
    //---------------------------------------------
    //---------------------------------------------
    #[Route('/account/follow-articles', name: 'account-follow-articles')]
    public function followArticles(): Response
    {
        $user = $this->getUser();
        $follows = $user->getFollows();
        $followArticles = [];
        
        foreach($follows as $follower){
            $articles = $follower->getArticles();
            
            foreach($articles as $article){
                array_push($followArticles, $article);
            }
            // $followArticles += $follower->get
        }
        // dd($followArticles);
         
        return $this->render('account/follow-article.html.twig', [
            'user' => $user,
            'follows' => $follows,
            'articles' => $followArticles,
        ]);
    }





    //------------------------- Articles de l'user -----------------------------
    #[Route('/account/your-articles', name: 'account-your-articles')]
    public function yourArticles(Request $request, ArticleRepository $articleRep): Response
    {
        $user = $this->getUser();
        // $articlesFull = $articleRep->findAll();
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRep->getArticleByAuthorPaginator($offset, $user);
         
        return $this->render('account/account-your-articles.html.twig', [
            'user' => $user,
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::ARTICLE_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::ARTICLE_PER_PAGE),
        ]);
    }





    //------------------------- Boite reception de l'user -----------------------------
    #[Route('/account/messages', name: 'account-messages')]
    public function yourMessages(): Response
    {
        $user = $this->getUser();
        $messages = $user->getMessagesReceived(); //Penser à un paginator pour les messages
        
         
        return $this->render('account/account-messages.html.twig', [
            'user' => $user,
            'messages' => $messages
            // 'articles' => $paginator,
            // 'previous' => $offset - ArticleRepository::ARTICLE_PER_PAGE,
            // 'next' => min(count($paginator), $offset + ArticleRepository::ARTICLE_PER_PAGE),
        ]);
    }


        //------------------------- Affichage du message ------------------------- 
        #[Route('/account/message/{slug}', name: 'account-message-page')]
        public function messageDisplay(PrivateMessageRepository $messageRep, string $slug): Response
        {
            //Affichage du message par slug
            $message = $messageRep->findOneBy(['slug' => $slug]);
            return $this->render('account/message-page.html.twig', [
                'message' => $message,
            ]);
        }

        //------------------------- Switch read du message ------------------------- 
        #[Route('/account/message/read/{slug}', name: 'account-message-page-switch')]
        public function messageRead(EntityManagerInterface $em, PrivateMessageRepository $messageRep, string $slug): Response
        {
            //Affichage du message par slug
            $message = $messageRep->findOneBy(['slug' => $slug]);
            if($message->isIsRead() == false){
                $message->setIsRead(true);
                $em->persist($message);
                $em->flush();
            }

            return $this->redirectToRoute('account-message-page', [
                'slug' => $slug,
            ]);
        }
}

