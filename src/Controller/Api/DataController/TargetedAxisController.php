<?php

namespace App\Controller\Api\DataController;

use App\Entity\TargetedAxis;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TargetedAxisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/data", name="data_targeted_axis_")
 */
class TargetedAxisController extends AbstractController
{
    /**
     * @Route("/targeted-axes", name="browse", methods = {"GET"})
     */
    public function list(TargetedAxisRepository $targetedAxisRepository): JsonResponse
    {
        $targetedAxes = $targetedAxisRepository->findAll();

        return $this->json([
            'targetedAxes' => $targetedAxes,
        ], Response::HTTP_OK, [], ["groups" => "targeted_axis"]);
    }

    /**
     * @Route("/targeted-axes", name="add", methods = {"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $json = $request->getContent();

        $targetedAxis = $serializer->deserialize($json, TargetedAxis::class, 'json');

        $errorList = $validator->validate($targetedAxis);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($targetedAxis);
        $em->flush();

        return $this->json($targetedAxis, Response::HTTP_CREATED, [], ["groups" => "targeted_axis"]);
    }

    /**
     * @Route("/targeted-axes/{id<\d+>}", name="edit", methods = "PATCH")
     */
    public function edit($id, TargetedAxisRepository $targetedAxisRepository, EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $targetedAxis = $targetedAxisRepository->find($id);

        if ($targetedAxis === null)
        {
            $errorMessage = [
                'message' => "Outil externe non trouvée",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        $json = $request->getContent();

        $serializer->deserialize($json, TargetedAxis::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $targetedAxis]);

        $em->flush();

        return $this->json($targetedAxis, Response::HTTP_OK, [], ["groups" => "targeted_axis"]);
    }

    /**
     * @Route("/targeted-axes/{id<\d+>}", name="delete", methods = {"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em): JsonResponse
    {
        {
            $targetedAxis = $em->find(TargetedAxis::class, $id);
    
            if ($targetedAxis === null)
            {
                $errorMessage = [
                    'message' => "Outil externe non trouvé",
                ];
                return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
            }

            $em->remove($targetedAxis);
            $em->flush();
    
            // on renvoit une réponse
            return $this->json("Outil externe supprime", Response::HTTP_OK, [], []);
        }
    }
}
