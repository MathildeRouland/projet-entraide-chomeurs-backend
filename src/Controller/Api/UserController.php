<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Support;
use App\Repository\UserRepository;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/api", name="api_users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        // on récupère tous les users
        $userList = $userRepository->findAll();
                       
        // On les envoie en json pour l'api
        return $this->json([
            'users' => $userList,
        
    
        ], Response::HTTP_OK, [], ["groups" => "user_group"]);
    }

    /**
     * @Route("/users/names", name="browse_names", methods={"GET"})
     * Methode dont la route est publique pour l'affichage des users sur une page du site vitrine, 
     * elle est sécurisée grâce à l'utilisation du group user_public pour ne pas envoyer des données sensibles 
     */
    public function browseNames(UserRepository $userRepository): Response
    {
        // on récupère tous les users
        $users = $userRepository->findAll();
                       
        // On les envoie en json pour l'api
        // le group user_public nous permet d'envoyer certaines données en excluant les données sensibles
        return $this->json([
            'users' => $users,
        
        ], Response::HTTP_OK, [], ["groups" => "user_public"]);
    }

    /**
     * @Route("/users", name="add", methods={"POST"})
     */
    public function add(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        
        // On récupére le contenu JSON de la requête
        $json = $request->getContent();

        // On désérialise les données JSON pour obtenir l'objet user
        $user = $serializer->deserialize($json, User::class, 'json');

        //On récupère le mot de passe en clair
        $clearPassword = $user->getPassword();
        //on hash le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
        //On ajoute le mot de passe hashé
        $user->setPassword($hashedPassword);

        // On valide les données
        $errorList = $validator->validate($user);
        // Si il y a des erreurs on renvoie une réponse HTTP avec la liste d'erreurs
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        // on enregistre dans la BDD
        $em->persist($user);
        $em->flush();

        // on renvoie une réponse
        return $this->json($user, Response::HTTP_CREATED, [], ["groups" => "user_group"]);

    }

    /**
     * @Route("/users/{id}", name="edit", methods={"PATCH"})
     */
    public function edit($id, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        //On récupère l'utilisateur grâce à l'id
        $user = $em->find(User::class, $id);

        //si l'utilisateur n'existe pas on envoie un message d'erreur
        if ($user === null)
        {
            $errorMessage = [
                'message' => "Utilisateur non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On récupère le contenu JSON de la requête
        $json = $request->getContent();
        $userData = json_decode($json, true);

        // On désérialise le contenu de la requête pour mettre à jour l'objet existant user
        $serializer->deserialize($json, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        //on récupère le nouveau mot de passe de la requête et on l'ajoute au user si il y a un nouveau mot de passe, sinon on ne fait rien
        if(isset($userData['password'])){
            // On hash le nouveau mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);
        }

        //on envoie à la BDD
        $em->flush();

        // on renvoie une réponse
        return $this->json($user, Response::HTTP_OK, [], ["groups" => "user_group"]);

    }

    /**
     * @Route("/users/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, SupportRepository $supportRepository): JsonResponse
    {
        // On récupère le user grâce à l'id
        $user = $em->find(User::class, $id);

        //on recupere les supports du user
        $userSupports = $user->getSupports();

        //pour chaque support lié au user on supprime le user
        foreach($userSupports as $userSupport){
            $support = new \App\Entity\Support();
            $support = $userSupport->setUser(null);

            //on enregistre la suppression
            $em->persist($support);
            $em->flush();
        }
 
        //Si le user n'existe pas, on envoie un message d'erreur
        if ($user === null)
        {
            $errorMessage = [
                'message' => "Utilisateur non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        //On supprime de la BDD
        $em->remove($user);
        $em->flush();

        // on renvoit une réponse
        return $this->json("utilisateur supprime", Response::HTTP_OK, [], ["groups" => 'users_delete']);
    }

}
