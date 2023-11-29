<?php

namespace App\Controller\Api\DataController;

use App\Entity\EndSupport;
use App\Repository\DifficultyRepository;
use App\Repository\EndSupportRepository;
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
 * @Route("/api/data", name="data_end_support_")
 */
class EndSupportController extends AbstractController
{
    /**
     * @Route("/end-supports", name="browse", methods = {"GET"})
     */
    public function browse(EndSupportRepository $endSupportRepository): JsonResponse
    {
        $endSupports = $endSupportRepository->findAll();

        return $this->json([
            'endSupports' => $endSupports,
        ], Response::HTTP_OK, [], ["groups" => "end_support"]);
    }

    /**
     * @Route("/end-supports", name="add", methods = {"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, ReleaseReasonRepository $releaseReasonRepository, DifficultyRepository $difficultyRepository): JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        $endSupport = $serializer->deserialize($json, EndSupport::class, 'json');

        $endSupport->setReleaseReason($releaseReasonRepository->find($data['release_reason']));

        $endSupport->getDifficulty()->clear();
        foreach($data['difficulty'] as $difficulty => $difficultyId) {
                $endSupport->addDifficulty($difficultyRepository->find($difficultyId));
        }

        $errorList = $validator->validate($endSupport);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($endSupport);
        $em->flush();

        return $this->json($endSupport, Response::HTTP_CREATED, [], ["groups" => "end_support"]);
    }

    /**
     * @Route("/end-supports/{id<\d+>}", name="edit", methods = "PATCH")
     */
    public function edit($id, EndSupportRepository $endSupportRepository, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, DifficultyRepository $difficultyRepository, ReleaseReasonRepository $releaseReasonRepository): JsonResponse
    {
        $endSupport = $endSupportRepository->find($id);

        if ($endSupport === null)
        {
            $errorMessage = [
                'message' => "Fin d'accompagnement non trouvée",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        $json = $request->getContent();
        $data = json_decode($json, true);

        $serializer->deserialize($json, EndSupport::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $endSupport]);

        $endSupport->setReleaseReason($releaseReasonRepository->find($data['release_reason']));

        if (isset($data['difficulty']))
        {
            $endSupport->getDifficulty()->clear();
            foreach($data['difficulty'] as $difficulty => $difficultyId) {
                    $endSupport->addDifficulty($difficultyRepository->find($difficultyId));
            }
        }


        $em->flush();

        return $this->json($endSupport, Response::HTTP_OK, [], ["groups" => "end_support"]);
    }

    /**
     * @Route("/end-supports/{id<\d+>}", name="delete", methods = {"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, EndSupportRepository $endSupportRepository): JsonResponse
    {
        {
            $endSupport = $em->find(EndSupport::class, $id);
    

            if ($endSupport === null)
            {
                $errorMessage = [
                    'message' => "Fin d'accompagnement non trouvé",
                ];
                return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
            }
    
    
            $em->remove($endSupport);
            $em->flush();
    
            // on renvoit une réponse
            return $this->json("Fin d'accompagnement supprimé", Response::HTTP_OK, [], []);
        }
        }
}
    

