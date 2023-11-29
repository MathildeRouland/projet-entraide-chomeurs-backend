<?php

namespace App\Controller\Api;

use App\Entity\Member;
use App\Entity\Support;
use App\Entity\ArchivedMember;
use App\Entity\ArchivedSupport;
use App\Repository\UserRepository;
use App\Service\JsonDecodeService;
use App\Service\MemberDataService;
use App\Service\SupportDataService;
use App\Repository\MemberRepository;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="members_support_")
 */
class MemberSupportController extends AbstractController
{
    private $memberDataService;
    private $supportDataService;
    private $jsonDecodeService;

    // On créer une fonction construct pour appeler les services.
    public function __construct(MemberDataService $memberDataService, SupportDataService $supportDataService, JsonDecodeService $jsonDecodeService)
    {
        $this->memberDataService = $memberDataService;
        $this->supportDataService = $supportDataService;
        $this->jsonDecodeService = $jsonDecodeService;
    }

    /**
     * @Route("/members-support", name="browse", methods={"GET"})
     */
    public function browse(MemberRepository $memberRepository): Response
    {
        // On récupère tout les membres
        $memberList = $memberRepository->findAll();

        // On les envoie en json pour l'api
        return $this->json([
            'members' => $memberList,
        ], Response::HTTP_OK, [], ["groups" => "member"]);
    }

    /**
     * @Route("/members-support", name="add", methods={"POST"})
     */
    public function add(EntityManagerInterface $em, Request $request, ValidatorInterface $validator): JsonResponse
    {
        // On récupére le contenu JSON de la requête
        $json = $request->getContent();

        // On désérialise les données JSON pour obtenir les objets Member et Support
        $member = $this->jsonDecodeService->deserializeMember($json, $request);
        $support = $this->jsonDecodeService->deserializeSupport($json, $request);

        // On fait la liaison de member vers support
        $member->setSupport($support);

        // On appelle la méthode du sevice MemberDataService pour ajouter les vulnérabilités et les difficultés au member
        // Dans les paramètres de la méthode on renvoi $member qui correspond à l'objet créé et en deuxième paramètre on envoi la partie member du contenu de la requête décodé
        $this->memberDataService->setDataMember($member, $this->jsonDecodeService->dataDecode($json)['member'], $request);

        // On fait la liaison de support vers member
        $support->setMember($member);

        // On appelle la méthode du service supportDataService pour ajouter les données au support
        // Dans les paramètres de la méthode on renvoi $support qui correspond à l'objet créé et en deuxième paramètre on envoi la partie support du contenu de la requête décodé
        $this->supportDataService->setDataSupport($support, $this->jsonDecodeService->dataDecode($json)['support'], $request);

        // On valide les données
        $errorListMember = $validator->validate($member);
        $errorListSupport = $validator->validate($support);

        if (count($errorListMember) > 0 || count($errorListSupport) > 0) {
            // Si il y a des erreurs dans la liste on fusionne les deux tableaux d'erreurs
            $errors = array_merge($errorListMember, $errorListSupport);
            // Puis on renvoie une réponse HTTP avec la liste d'erreur
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        // On enregistre dans la base de donnée
        $em->persist($member);
        $em->persist($support);
        $em->flush();

        // On renvoie une réponse
        return $this->json([
            'member' => $member,
            'support' => $support
        ], Response::HTTP_CREATED, [], ["groups" => "member"]);
    }

    /**
     * @Route("/members-support/{id}", name="edit", methods="PATCH")
     */
    public function edit($id, EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        //On utilise l'entity manager pour récupérer les membres
        $member = $em->find(Member::class, $id);

        //On récupére le support lié au membre
        $support = $member->getSupport();
    
        // Si le member n'existe pas, on envoie un message d'erreur
        if ($member === null) {
            $errorMessage = [
                'message' => "Adhérent non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // Récupérer le contenu JSON de la requête
        $json = $request->getContent();

        // On désérialise les données JSON pour obtenir les objets Member et Support
        $member = $this->jsonDecodeService->deserializeMember($json, $request, $member);
        $support = $this->jsonDecodeService->deserializeSupport($json, $request, $support);

        // On appelle la méthode du sevice pour ajouter les données au member et au support
        // Dans les paramètres de la méthode on renvoi l'objet créé et en deuxième paramètre on envoi la partie member ou support du contenu de la requête décodé en fonction de l'objet
        $this->memberDataService->setDataMember($member, $this->jsonDecodeService->dataDecode($json)['member'], $request);
        $this->supportDataService->setDataSupport($support, $this->jsonDecodeService->dataDecode($json)['support'], $request);

        // On enregistre en base de données
        $em->flush();

           // On renvoie une réponse
           return $this->json([
            'member' => $member,
            'support' => $support
        ], Response::HTTP_OK, [], ["groups" => "member"]);

    }

    /**
     * @Route("/members-support/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, SupportRepository $supportRepository): JsonResponse
    {
        // On utilise l'entity manager pour récupérer le member
        $member = $em->find(Member::class, $id);


        // Si le member n'existe pas, on envoie un message d'erreur
        if ($member === null)
        {
            $errorMessage = [
                'message' => "Adhérent non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On supprime de la base de données
        $em->remove($member);
        $em->flush();

        // on renvoie une réponse
        return $this->json("adhérent supprimé", Response::HTTP_OK, [], ["groups" => 'member']);
    }

    /**
     * @Route("/members-support/archived/{id<\d+>}", name="archived", methods={"DELETE"})
     */
    public function archived($id, EntityManagerInterface $em, SupportRepository $supportRepository): JsonResponse
    {
        // on peut utiliser l'entity manager directement pour faire un find 
        $member = $em->find(Member::class, $id);

        //On récupére le support lié au membre
        $support = $member->getSupport();

        // Si la member n'existe pas, on envoie un message d'erreur
        if ($member === null)
        {
            $errorMessage = [
                'message' => "Adhérent non trouvé",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On crée les objets qu'on va peupler
        $archivedMember = new ArchivedMember();
        $archivedSupport = new ArchivedSupport();

        //On ajoute les données à garder dans la table archiveMember
        //Pour l'anonymisation des données (RGPD) on récuère uniquement les données qui ne permettent pas d'identifier l'adhérent (member)
        $archivedMember->setGender($member->getGender());
        $archivedMember->setNote($member->getNote());

        // On crée un tableau vide pour le remplir avec les vulnérabilités récupérées à partir du member sélectionné
        $vulnerabilities = [];
        foreach($member->getVulnerability() as $vulnerability){
            $vulnerabilities [] = $vulnerability->getName();
        }
        // Et pouvoir les ajouter à la table archiveMember
        $archivedMember->setVulnerabilities($vulnerabilities);

        // On crée un tableau vide pour le remplir avec les difficultés récupérées à partir du member sélectionné
        $difficulties = [];
        foreach($member->getDifficulty() as $difficulty){
            $difficulties [] = $difficulty->getName();
        }
        // Et pouvoir les ajouter à la table archivedMember
        $archivedMember->setDifficulties($difficulties);

        //On ajoute les données à garder dans la table archivedSupport
        $archivedSupport->setEntryDate($support->getEntryDate());
        $archivedSupport->setOngoingJob($support->getOngoingJob());
        $archivedSupport->setOngoingFormation($support->getOngoingFormation());
        $archivedSupport->setWorksitePosition($support->isWorksitePosition());
        $archivedSupport->setFormationPositioning($support->isFormationPositioning());
        $archivedSupport->setNote($support->getNote());
    
        // On récupére et ajoute le nom du lieu du support via la table Place
        $archivedSupport->setPlace($support->getPlace()->getName());

        // On récupère et on ajoute le nom et prenom de la personne en charge du support 
        if($support->getUser() !== null){
            $archivedSupport->setUser($support->getUser()->getFirstname().' '.$support->getUser()->getLastname());
        }
        // On récupére et on ajoute la raison de la fin du support grâce à la relation avec les tables endSupport et ReleaseReason
        $archivedSupport->setEndSupport($support->getEndSupport()->getReleaseReason()->getReason());
        // On récupére et on ajoute le type de la targetedAxis
        $archivedSupport->setTargetedAxis($support->getTargetedAxis()->getType());

        // On crée un tableau vide pour le remplir avec les outils externes récupérées à partir du member sélectionné
        $externalTools = [];
        foreach($support->getExternalTool() as $externalTool){
            $externalTools [] = $externalTool->getname();
        }
        // Et pouvoir les ajouter à la table archivedSupport
        $archivedSupport->setExternalTool($externalTools);

        // On fait la liaison de archivedMember vers archivedSupport
        $archivedMember->setArchivedSupport($archivedSupport);
    
        // On enregistre archivedMember et archivedSupport en base de données 
        $em->persist($archivedMember);
        $em->persist($archivedSupport);

        // On supprime le member de la base de données
        $em->remove($member);
        $em->flush();

        // On renvoie une réponse
        return $this->json("adhérent Archivé", Response::HTTP_OK, [], ["groups" => 'member_archived']);
    }

}
