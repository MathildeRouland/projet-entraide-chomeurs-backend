<?php 

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\PlaceRepository;
use App\Repository\ExternalToolRepository;
use App\Repository\TargetedAxisRepository;


class SupportDataService
{
    private $placeRepository;
    private $userRepository;
    private $targetedAxisRepository;
    private $externalToolRepository;

    public function __construct(PlaceRepository $placeRepository, TargetedAxisRepository $targetedAxisRepository, ExternalToolRepository $externalToolRepository, UserRepository $userRepository)
    {
        $this->placeRepository = $placeRepository;
        $this->userRepository = $userRepository;
        $this->targetedAxisRepository = $targetedAxisRepository;
        $this->externalToolRepository = $externalToolRepository;
    }

    public function getPlace(int $id)
    {
        return $this->placeRepository->find($id);
    }

    public function getUser(int $id)
    {
        return $this->userRepository->find($id);
    }

    public function getTargetedAxis(int $id)
    {
            return $this->targetedAxisRepository->find($id);
    }

    public function getExternalTool(int $id)
    {
        return $this->externalToolRepository->find($id);
    }
    
    public function  setDataSupport($support, $supportData, $request){
        if($request->ismethod('POST')) {
            //On ajoute les données au support
            $support->setUser($this->getUser($supportData['user']));
            $support->setPlace($this->getPlace($supportData['place']));
            $support->setTargetedAxis($this->getTargetedAxis($supportData['targeted_axis']));

            // Si les external_tool sur supportData existent
            if (isset($supportData['external_tool'])) {
                // On vide le tableau external_tool de l'objet sélectionné (support)
                $support->getExternalTool()->clear();
                // Pour chaque external_tool on récupère son id
                foreach($supportData['external_tool'] as $externalTool=>$externalToolId) {
                    // qui nous sert a l'ajouter au support
                    $support->addExternalTool($this->getExternalTool($externalToolId));
                }
            }
        }

        if($request->ismethod('PATCH')) {
            //On ajoute les données au support
            $support->setUser($this->getUser($supportData['user']['id']));
            $support->setPlace($this->getPlace($supportData['place']['id']));
            $support->setTargetedAxis($this->getTargetedAxis($supportData['targeted_axis']['id']));

            // Si les external_tool sur supportData existent
            if (isset($supportData['external_tool'])) {
                // On vide le tableau external_tool de l'objet sélectionné (support)
                $support->getExternalTool()->clear();
                // Pour chaque external_tool on récupère son id
                foreach($supportData['external_tool'] as $externalTool) {
                    $externalToolId = $externalTool['id'];
                    // qui nous sert a l'ajouter au support
                    $support->addExternalTool($this->getExternalTool($externalToolId));
                }
            }
        }
    }
}