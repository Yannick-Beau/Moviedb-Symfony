<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Service\MessageGenerator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/back/user')]
class UserController extends AbstractController
{
    #[Route('/browse', name: 'back_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'back_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MessageGenerator $messageGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On hash le password
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            // On le remplace dans $user->password
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', $messageGenerator->getSuccessMessage());
            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/read/{id<\d+>}', name: 'back_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'back_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, MessageGenerator $messageGenerator, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Le form va transmettre les données de la requête
        // à l'entité, sauf le password qui est non mappé
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashage du mot de passe que si on a renseigné le champ mot de passe
             if (!empty($form->get('password')->getData())) {
                // C'est là qu'on encode le mot de passe du User (qui se trouve dans $user)
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                // On réassigne le mot passe encodé dans le User
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();
            $this->addFlash('success', $messageGenerator->getSuccessMessage());
            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id<\d+>}', name: 'back_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, MessageGenerator $messageGenerator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', $messageGenerator->getSuccessMessage());
        }

        return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
