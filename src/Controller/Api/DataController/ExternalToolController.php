<?php

namespace App\Controller\Api\DataController;

use App\Entity\ExternalTool;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ExternalToolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/data", name="data_external_tool_")
 */
class ExternalToolController extends AbstractController
{
    /**
     * @Route("/external-tools", name="browse", methods = {"GET"})
     */
    public function list(ExternalToolRepository $externalToolRepository): JsonResponse
    {
        $externalTools = $externalToolRepository->findAll();

        return $this->json([
            'externalTools' => $externalTools,
        ], Response::HTTP_OK, [], ["groups" => "external_tool"]);
    }

    /**
     * @Route("/external-tools", name="add", methods = {"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $json = $request->getContent();

        $externalTool = $serializer->deserialize($json, ExternalTool::class, 'json');

        $errorList = $validator->validate($externalTool);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($externalTool);
        $em->flush();

        return $this->json($externalTool, Response::HTTP_CREATED, [], ["groups" => "external_tool"]);
    }

    /**
     * @Route("/external-tools/{id<\d+>}", name="edit", methods = "PATCH")
     */
    public function edit($id, ExternalToolRepository $externalToolRepository, EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $externalTool = $externalToolRepository->find($id);

        if ($externalTool === null)
        {
            $errorMessage = [
                'message' => "Outil externe non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        $json = $request->getContent();

        $serializer->deserialize($json, ExternalTool::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $externalTool]);

        $em->flush();

        return $this->json($externalTool, Response::HTTP_OK, [], ["groups" => "external_tool"]);
    }

    /**
     * @Route("/external-tools/{id<\d+>}", name="delete", methods = {"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, SupportRepository $supportRepository): JsonResponse
    {
        {
            $externalTool = $em->find(ExternalTool::class, $id);
    
            $externalToolSupports = $externalTool->getSupports();
    
            foreach($externalToolSupports as $externalToolSupport){
                $support = new \App\Entity\Support();
                $support = $externalToolSupport->removeExternalTool($externalTool);
    
                $em->persist($support);
                $em->flush();
            }
    
            
            if ($externalTool === null)
            {
                $errorMessage = [
                    'message' => "Outil externe non trouvé",
                ];
                return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
            }
    
    
            $em->remove($externalTool);
            $em->flush();
    
            // on renvoit une réponse
            return $this->json("Outil externe supprimé", Response::HTTP_OK, [], []);
        }
    }
}
