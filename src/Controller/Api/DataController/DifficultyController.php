<?php

namespace App\Controller\Api\DataController;

use App\Entity\Difficulty;
use App\Repository\MemberRepository;
use App\Repository\DifficultyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/data", name="data_difficulty_")
 */
class DifficultyController extends AbstractController
{
    /**
     * @Route("/difficulties", name="browse", methods = {"GET"})
     */
    public function browse(DifficultyRepository $difficultyRepository): JsonResponse
    {
        //On récupère toutes les difficultés
        $difficulties = $difficultyRepository->findAll();

        //On les envoie en json
        return $this->json([
            'difficulties' => $difficulties,
        ], Response::HTTP_OK, [], ["groups" => "difficulty"]);
    }

    /**
     * @Route("/difficulties", name="add", methods = {"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        //On récupère le contenu de la requête
        $json = $request->getContent();

        //On déserialise le contenu de la requête pour obtenir l'objet difficulté
        $difficulty = $serializer->deserialize($json, Difficulty::class, 'json');

        //On vérifie si il y ades erreurs
        $errorList = $validator->validate($difficulty);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        //On envoie à la BDD
        $em->persist($difficulty);
        $em->flush();

        //On retourne les données au format json
        return $this->json($difficulty, Response::HTTP_CREATED, [], ["groups" => "difficulty"]);
    }

    /**
     * @Route("/difficulties/{id<\d+>}", name="edit", methods = "PATCH")
     */
    public function edit($id, DifficultyRepository $difficultyRepository, EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        // On Récupère la difficulté grâce à l'id
        $difficulty = $difficultyRepository->find($id);

        // Si la difficulté n'existe pas, on envoie un message d'erreur
        if ($difficulty === null)
        {
            $errorMessage = [
                'message' => "Difficulté non trouvée",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On récupère le contenu de la requête
        $json = $request->getContent();

        // On déserialise le contenu de la requête pour mettre à jour l'objet existant difficulty
        $serializer->deserialize($json, Difficulty::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $difficulty]);

        // On enregistre la modification dans la BDD
        $em->flush();

        // On retourne une réponse en json
        return $this->json($difficulty, Response::HTTP_OK, [], ["groups" => "difficulty"]);
    }

    /**
     * @Route("/difficulties/{id<\d+>}", name="delete", methods = {"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, MemberRepository $memberRepository): JsonResponse
    {
        {
            // On récupère la difficulté grâce à l'id
            $difficulty = $em->find(Difficulty::class, $id);
            
            // A cause de la relation Many To Many, on va chercher le membre lié à la difficulté
            $difficultyMembers = $difficulty->getMembers();
    
            // Pour chaque membre on retire la difficulté pour éviter des entrée vide/null dans la table pivot
            foreach($difficultyMembers as $difficultyMember){
                $member = new \App\Entity\Member();
                $member = $difficultyMember->removeDifficulty($difficulty);
    
                $em->persist($member);
                $em->flush();
            }
            // On recommence pour le support.
            $difficultyEndSupports = $difficulty->getEndSupports();

            foreach($difficultyEndSupports as $difficultyEndSupport){
                $endSupport = new \App\Entity\EndSupport();
                $endSupport = $difficultyEndSupport->removeDifficulty($difficulty);
    
                $em->persist($endSupport);
                $em->flush();
            }
    
            // Si la difficulté n'existe pas, on envoie un message d'erreur
            if ($difficulty  === null)
            {
                $errorMessage = [
                    'message' => "Difficulté non trouvée",
                ];
                return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
            }
    
            // On supprime de la base de donnée
            $em->remove($difficulty);
            $em->flush();
    
            // On renvoit une réponse 
            return $this->json("Difficulté supprimée", Response::HTTP_OK, [], []);
        }
    }
}
