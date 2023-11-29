<?php

namespace App\Controller\Api\DataController;

use App\Entity\Place;
use App\Repository\PlaceRepository;
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
 * @Route("/api/data", name="data_place_")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/places", name="browse", methods = {"GET"})
     */
    public function browse(PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository->findAll();
        // 2.envoi en json pour l'api
        return $this->json([
            'places' => $places,
        ], Response::HTTP_OK, [], ["groups" => "place"]);
    }

    /**
     * @Route("/places", name="add", methods = {"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        // on veut directement traiter les données recues en json

        // ? 1. récupérer les données au format json
        $json = $request->getContent();    

        // ? 2. deserialisation des données json pour obtenir un objet place
        $place = $serializer->deserialize($json, Place::class, 'json');

        $errorList = $validator->validate($place);
        if (count($errorList) > 0)
        {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }
        

        // ? 3. on persist et on flush
        $em->persist($place);
        $em->flush();

        // on renvoit une réponse
        return $this->json($place, Response::HTTP_CREATED, [], ["groups" => 'place']);
    }

    /**
     * @Route("/places/{id<\d+>}", name="edit", methods = {"PATCH"})
     */
    public function edit($id, PlaceRepository $placeRepository, EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $place = $placeRepository->find($id);

        if ($place === null)
        {
            $errorMessage = [
                'message' => "Lieu non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        $json = $request->getContent();

        $serializer->deserialize($json, Place::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $place]);

        $em->flush();

        return $this->json($place, Response::HTTP_OK, [], ["groups" => "place"]);

    }

    /**
     * @Route("/places/{id<\d+>}", name="delete", methods = {"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, PlaceRepository $placeRepository): JsonResponse
    {
        // on peut utiliser l'entity manager directement pour faire un find 
        $place = $em->find(Place::class, $id);


        
        if ($place === null)
        {
            $errorMessage = [
                'message' => "Lieu non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }


        $em->remove($place);
        $em->flush();

        // on renvoit une réponse
        return $this->json("lieu supprimé", Response::HTTP_OK, [], ["groups" => 'place']);
    }
}
