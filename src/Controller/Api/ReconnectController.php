<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/api", name="api_")
 */
class ReconnectController extends AbstractController
{

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }
    
    /**
     * @Route("/reconnect", name="reconnect")
     */
    public function reconnect (UserRepository $userRepository): Response
    {
        // on décode le token pour récupérer les données
        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        // on récupère l'utilisateur en fonction de son identifiant (email en ce cas là)
        $currentUser = $userRepository->findOneByEmail($decodedJwtToken['username']);

        // on renvoie une réponse avec les données en json de l'utilisateur connecté
        return $this->json([
            'user' => $currentUser,
        ], Response::HTTP_OK, [], []);        
    }
}
