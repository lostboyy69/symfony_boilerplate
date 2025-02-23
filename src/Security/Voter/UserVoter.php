<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    public function __construct(private Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
    $user = $token->getUser();
    
    dump('Attribut reçu :', $attribute);
    dump('Objet reçu :', $subject);
    dump('Utilisateur connecté :', $user);
    dump('Rôles de l\'utilisateur :', $user instanceof User ? $user->getRoles() : 'Utilisateur non connecté');


    if (!$user instanceof User) {
        return false;
    }

    // Si l'utilisateur est admin, il peut modifier tout le monde
    if ($this->security->isGranted('ROLE_ADMIN')) {
        return true;
    }
    return $user === $subject;
}

    
}
