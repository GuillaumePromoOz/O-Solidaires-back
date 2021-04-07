<?php

namespace App\Security\Voter;

use App\Entity\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RequestVoter extends Voter
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
        if (!in_array($attribute, ['patchRequest'])) {
            return false;
        }

        // only vote on `Request` objects
        if (!$subject instanceof Request) {
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
            case 'patchRequest':

                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                // logic to determine if the user can EDIT
                // return true or false
                return $user === $subject->getUser();
                break;
        }

        return false;
    }
}
