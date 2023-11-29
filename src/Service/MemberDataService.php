<?php 

namespace App\Service;

use App\Repository\DifficultyRepository;
use App\Repository\VulnerabilityRepository;


class MemberDataService
{
    private $vulnerabilityRepository;
    private $difficultyRepository;

    public function __construct(VulnerabilityRepository $vulnerabilityRepository, DifficultyRepository $difficultyRepository)
    {
        $this->vulnerabilityRepository = $vulnerabilityRepository;
        $this->difficultyRepository = $difficultyRepository;
    }

    public function getVulnerability(int $id)
    {
        return $this->vulnerabilityRepository->find($id);
    }

    public function getDifficulty(int $id)
    {
        return $this->difficultyRepository->find($id);
    }

    public function  setDataMember($member, $memberData, $request){
        
        if($request->ismethod('POST')) {
            // Si les vulnérabilités sur memberData existent
            if (isset($memberData['vulnerability'])) {
                // On vide le tableau vulnerability de l'objet sélectionné (member)
                $member->getVulnerability()->clear();
                // Pour chaque vulnerability on récupère son id
                foreach($memberData['vulnerability'] as $vulnerability=>$vulnerabilityId ) {
                    // qui nous sert a l'ajouter à member
                    $member->addVulnerability($this->getVulnerability($vulnerabilityId));
                }
            }

            // Si les difficulty sur memberData existent
            if (isset($memberData['difficulty'])) {
                // On vide le tableau difficulty de l'objet sélectionné (member)
                $member->getDifficulty()->clear();
                // Pour chaque difficulty on récupère son id
                foreach($memberData['difficulty'] as $difficulty=>$difficultyId) {
                    // qui nous sert a l'ajouter à member
                    $member->addDifficulty($this->getDifficulty($difficultyId));
                }
            }
        }

        if($request->ismethod('PATCH')) {
            // Si les vulnérabilités sur memberData existent
            if (isset($memberData['vulnerability'])) {
                // On vide le tableau vulnerability de l'objet sélectionné (member)
                $member->getVulnerability()->clear();
                // Pour chaque vulnerability on récupère son id
                foreach($memberData['vulnerability'] as $vulnerability) {
                    $vulnerabilityId = $vulnerability['id'];
                    // qui nous sert a l'ajouter à member
                    $member->addVulnerability($this->getVulnerability($vulnerabilityId));
                }
            }

            // Si les difficulty sur memberData existent
            if (isset($memberData['difficulty'])) {
                // On vide le tableau difficulty de l'objet sélectionné (member)
                $member->getDifficulty()->clear();
                // Pour chaque difficulty on récupère son id
                foreach($memberData['difficulty'] as $difficulty) {
                    $difficultyId = $difficulty['id'];
                    // qui nous sert a l'ajouter à member
                    $member->addDifficulty($this->getDifficulty($difficultyId));
                }
            }
        }
    }
}