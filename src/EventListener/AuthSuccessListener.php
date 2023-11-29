<?php

namespace App\EventListener;


use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthSuccessListener
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $currentUser = $this->userRepository->findOneByEmail($user->getUserIdentifier());
        $pseudo = $currentUser->getFirstname();
        $roles = $currentUser->getRoles();
        
        $data["logged"] = true;
        $data["pseudo"] = $pseudo;
        $data["roles"] = $roles;

        $event->setData($data);
    }
}