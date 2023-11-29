<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\Support;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class JsonDecodeService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    

// Décodage de la requete au format Json
    public function dataDecode($json){
        return json_decode($json, true);
    }


// On sélectionne les données de member de la requete decodée
    public function memberData($json){
        $data = $this->dataDecode($json);

        return json_encode($data['member']);
    }

    // On désérialise le contenu json de la requête 
    public function deserializeMember($json, $request, $member = ''){
        // Si la methode est en POST on deserialize pour créer un nouvel objet
        if($request->ismethod('POST')){
           return $this->serializer->deserialize($this->memberData($json), Member::class, 'json');
        }
        
        // Si la requête est en PATCH, on désérialise pour peupler un objet déjà existant
        if($request->ismethod('PATCH')){
           return $this->serializer->deserialize($this->memberData($json), Member::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $member]);
        }
    }


// On sélectionne les données de support de la requete decodée
    public function supportData($json){
        $data = $this->dataDecode($json);

        return json_encode($data['support']);
    }
    // On désérialise le contenu json de la requête 
    public function deserializeSupport($json, $request, $support = ''){
        // Si la methode est en POST on deserialize pour créer un nouvel objet
        if($request->ismethod('POST')){
           return $this->serializer->deserialize($this->supportData($json), Support::class, 'json');
        }

        // Si la requête est en PATCH, on désérialise pour peupler un objet déjà existant
        if($request->ismethod('PATCH')){
           return $this->serializer->deserialize($this->supportData($json), Support::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $support]);
        }
    }
}