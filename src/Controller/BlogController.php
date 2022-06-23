<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/blog/{username}', name: 'blog')]
    public function index(UserRepository $userRep, ArticleRepository $articleRep , string $username, Request $request): Response
    {
        // Utilisateur Connecté
        $currentUser = $this->getUser();
        // Utilisateur du profil visité
        $user = $userRep->findOneBy(['username' => $username]);
        //Vérification if follows ou followed by
        $checkFollow = false;
        $checkFollowedBy = false;
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRep->getArticleByAuthorPaginator($offset, $user);
        if($currentUser->getFollows()->contains($user))
        {
            $checkFollow = true;
        }
        if($user->getFollows()->contains($currentUser))
        {
            $checkFollowedBy = true;
        }
        //REDIRECTION VERS ACCOUNT SI MÊME PERSONNE
        if($currentUser->getUsername() == $user->getUsername())
        {
            return $this->redirectToRoute('account');
        }
        return $this->render('blog/blog.html.twig', [
            'user' => $user,
            'checkFollow' => $checkFollow,
            'checkFollowedBy' => $checkFollowedBy,
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::ARTICLE_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::ARTICLE_PER_PAGE),
        ]);
    }


    #[Route('/blog/{username}/follow', name: 'blog-follow')]
    public function follow(UserRepository $userRep, string $username): Response
    {
        $currentUser = $this->getUser();
        $user = $userRep->findOneBy(['username' => $username]);

        $currentUser->addFollows($user);
        $user->addFollowedBy($currentUser);
        $this->entityManager->persist($currentUser);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('blog', [
            'username' => $user,
        ]);
    }
}
