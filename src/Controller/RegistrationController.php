<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_home');
            
    }
    dump($form);
    return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}