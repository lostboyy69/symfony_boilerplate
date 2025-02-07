<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le prénom est obligatoire.']),
            ],
            'attr' => ['class' => 'form-input'],
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
            ],
            'attr' => ['class' => 'form-input'],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'constraints' => [
                new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                new Assert\Email(['message' => 'Veuillez entrer un email valide.']),
            ],
            'attr' => ['class' => 'form-input'],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => 'Mot de passe', 'attr' => ['class' => 'form-input']],
            'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['class' => 'form-input']],
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
            ],
        ])
        ->add('submit', SubmitType::class, ['label' => 'S\'inscrire', 'attr' => ['class' => 'btn btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
