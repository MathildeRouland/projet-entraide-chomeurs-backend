<?php

namespace App\Controller\Api\DataController;

use App\Entity\ReleaseReason;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReleaseReasonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/data", name="data_release_reason_")
 */
class ReleaseReasonController extends AbstractController
{

    /**
     * @Route("/release-reasons", name="browse", methods = {"GET"})
     */
    public function list(ReleaseReasonRepository $releaseReasonRepository): JsonResponse
    {
        $releaseReason = $releaseReasonRepository->findAll();

        return $this->json([
            'releaseReason' => $releaseReason,
        ], Response::HTTP_OK, [], ["groups" => "release_reason"]);
    }

    /**
     * @Route("/release-reasons", name="add", methods = {"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $json = $request->getContent();

        $releaseReason = $serializer->deserialize($json, ReleaseReason::class, 'json');

        $errorList = $validator->validate($releaseReason);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($releaseReason);
        $em->flush();

        return $this->json($releaseReason, Response::HTTP_CREATED, [], ["groups" => "release_reason"]);
    }

    /**
     * @Route("/release-reasons/{id<\d+>}", name="edit", methods = "PATCH")
     */
    public function edit($id, ReleaseReasonRepository $releaseReasonRepository, EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $releaseReason = $releaseReasonRepository->find($id);

        if ($releaseReason === null)
        {
            $errorMessage = [
                'message' => "Raison de la sortie non trouvée",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        $json = $request->getContent();

        $serializer->deserialize($json, ReleaseReason::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $releaseReason]);

        $em->flush();

        return $this->json($releaseReason, Response::HTTP_OK, [], ["groups" => "release_reason"]);
    }

    /**
     * @Route("/release-reasons/{id<\d+>}", name="delete", methods = {"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em): JsonResponse
    {
        {
            $releaseReason = $em->find(ReleaseReason::class, $id);
    
            
            if ($releaseReason === null)
            {
                $errorMessage = [
                    'message' => "Raison de la sortie non trouvée",
                ];
                return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
            }
    
    
            $em->remove($releaseReason);
            $em->flush();
    
            // on renvoit une réponse
            return $this->json("Raison de la sortie supprimée", Response::HTTP_OK, [], []);
        }
    }
}

