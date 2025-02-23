<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        if (!$user->getRoles()) {
            // Ajout d'un rôle par défaut si l'admin n'en met pas
            $user->setRoles(['ROLE_USER']); 
        }

        if ($user->getPassword()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user_index');
    }

    return $this->render('user/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
    

    #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{
    

    // $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

    $form = $this->createForm(UserType::class, $user, [
        'is_editing' => true, // Mode édition activé
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if ($user->getPassword()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }
    
        $entityManager->flush();
    
        return $this->redirectToRoute('admin_user_index');
    }
    return $this->render('user/edit.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
    
}


    #[Route('/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/profile/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($this->getUser()); die; 
        $user = $this->getUser();
        
        // $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);
    
        $form = $this->createForm(UserType::class, $user, [
            'is_editing' => true, // Activation du mode édition
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('profile_edit');
        }
    
        return $this->render('user/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
