<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // https://symfony.com/doc/current/security/voters.html

        // if the attribute isn't one we support, return false
        if (!in_array($attribute, ['patchUser'])) {
            return false;
        }

        // only vote on `User` objects
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'patchUser':

                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                // logic to determine if the user can EDIT
                // return true or false
                // in this case since User doesn't have a property $user hence not getter (no getUser)
                // we simply say that $user equals the entity/object User
                return $user === $subject;
                break;
        }

        return false;
    }
}
