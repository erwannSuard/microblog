<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Entity Manager et UserRepo pour trouver le destinataire avec le Slug
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
//Le formulaire de message et la request
use App\Form\PrivateMessageType;
use Symfony\Component\HttpFoundation\Request;
//Entities
use App\Entity\User;
use App\Entity\PrivateMessage;
class MessageController extends AbstractController
{
    //Global entityManager
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //Création du message
    #[Route('/message/{username}', name: 'message')]
    public function creationMessage(Request $request, UserRepository $userRep, string $username): Response
    {
        //Expéditeur, destinataire et message
        $sender = $this->getUSer();
        $receiver = $userRep->findOneBy(['username' => $username]);
        $message = new PrivateMessage();
        //formulaire
        $form = $this->createForm(PrivateMessageType::class);
        $form->handleRequest($request);

        //Si soumis et valide 
        if($form->isSubmitted() && $form->isValid())
        {
            //Créer le message et remplir ses attributs
            $message = $form->getData();
            $message->setAuthor($sender);
            $message->setReceiver($receiver);
            $message->setIsRead(false);
            //Entrée dans la DB
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->redirectToRoute('blog', [
                'username' => $receiver->getUsername(),
            ]);
        }
        return $this->renderForm('message/message-creation.html.twig', [
            'form' => $form,
        ]);
    }
}