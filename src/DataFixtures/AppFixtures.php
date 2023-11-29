<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Member;
use DateTimeImmutable;
use App\Entity\Support;
use App\Entity\Difficulty;
use App\Entity\EndSupport;
use App\Entity\ExternalTool;
use App\Entity\TargetedAxis;
use App\Entity\ReleaseReason;
use App\Entity\Vulnerability;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    
    public function __construct(UserPasswordHasherInterface $passwordHasherInterface)
    {
        $this->passwordHasher = $passwordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // Fixtures des Users

        $user = new User();
        $user->setFirstname($faker->firstname());
        $user->setLastname($faker->lastname());
        $user->setEmail('user@aec.com');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user');
        $user->setPassword($hashedPassword);
        $user->setPhone('06'.(string)$faker->randomNumber(8, true));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);


        $admin = new User();
        $admin->setFirstname($faker->firstname());
        $admin->setLastname($faker->lastname());
        $admin->setEMail('admin@aec.com');
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);
        $admin->setPhone('06'.(string)$faker->randomNumber(8, true));
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        //Fixtures des difficulties

        $difficulties = [];
        $difficulties[] = 'Accès aux droits';
        $difficulties[] = 'Addiction';
        $difficulties[] = 'BDE';
        $difficulties[] = 'Budget, endettement';
        $difficulties[] = 'Confiance en soi';
        $difficulties[] = 'FLE';
        $difficulties[] = 'Isolement';
        $difficulties[] = 'Justice';
        $difficulties[] = 'Lecture,écriture, apprentissage';
        $difficulties[] = 'Logement';
        $difficulties[] = 'Mobilité';
        $difficulties[] = 'Personnage à charge';
        $difficulties[] = 'Qualification';
        $difficulties[] = 'Santé';

        foreach ($difficulties as $difficultyName)
        {
            $difficulty = new Difficulty();
            $difficulty->setName($difficultyName);

            $manager->persist($difficulty);
            $difficultyList [] = $difficulty;
        }

        // Fixtures des vulnerabilities

        $vulnerabilities = [];
        $vulnerabilities[] = 'Famille monoparentale';
        $vulnerabilities[] = 'RQTH';
        $vulnerabilities[] = 'Tutelle /Curatelle';

        foreach ($vulnerabilities as $vulnerabilityName)
        {
            $vulnerability = new Vulnerability();
            $vulnerability->setName($vulnerabilityName);

            $manager->persist($vulnerability);
            $vulnerabilityList [] = $vulnerability;
        }

        //Fixtures des targeted axis

        $targetedAxis = [];
        $targetedAxis[] = 'Emploi';
        $targetedAxis[] = 'Formation';
        $targetedAxis[] = 'Projet professionel';
        $targetedAxis[] = 'Social';

        foreach ($targetedAxis as $targetedAxisName)
        {
            $axis = new TargetedAxis();
            $axis->setType($targetedAxisName);

            $manager->persist($axis);
            $axisList [] = $axis;
        }

        // Fixtures de Targeted axis 

        $externalTool = [];
        $externalTool[] = 'Atelier Partenaire';
        $externalTool[] = 'Diagnostic handi54 - Maison des addictions';
        $externalTool[] = 'Espoir 54 - Suivi CMP';
        $externalTool[] = 'Langue et illétrisme';

        foreach ($externalTool as $externalToolName)
        {
            $tool = new ExternalTool();
            $tool->setName($externalToolName);

            $manager->persist($tool);
            $toolList [] = $tool;
        }

        // Fixtures de release reason 

        $releaseReasons = [];
        $releaseReasons[] = 'CDD < 6 mois';
        $releaseReasons[] = 'CDD > 6 mois';
        $releaseReasons[] = 'CDDI';
        $releaseReasons[] = 'CDI';
        $releaseReasons[] = 'Contrat de qualification';
        $releaseReasons[] = 'Création activité';
        $releaseReasons[] = 'Déménagement';

        foreach ($releaseReasons as $releaseReasonName)
        {
            $reason = new ReleaseReason();
            $reason->setReason($releaseReasonName);

            $manager->persist($reason);
            $reasonList [] = $reason;
        }

        // Fixtures des Places
        
        $places = [];
        $places[] = 'Baccarat';
        $places[] = 'Blâmont';
        $places[] = 'Cirey surs Vezouze';
        $places[] = 'Damelevièsres';
        $places[] = 'Dombasle s(CCSEV)';
        $places[] = 'Dombasle s(MDS)';
        $places[] = 'Einville';
        $places[] = 'Gerbévillser';
        $places[] = 'Lunéville';
        $places[] = 'Saint Nicolas (Espsace défi)';
        $places[] = 'Saint Nicolas (MDS)';
        $places[] = 'Varangéville';
        $places[] = 'Virecourt';

        foreach ($places as $placeName)
        {
            $place = new Place();
            $place->setName($placeName);

            $manager->persist($place);
            $placeList [] = $place;
        }

        // Fixtures des members
        // On crée 20 members avec leur support
        for ($i = 1; $i <= 20; $i++) {

            $dateString = mt_rand(2012, 2023).'-'.mt_rand(1, 12).'-'.mt_rand(1, 30);
            $date = new DateTimeImmutable($dateString);
            $gender = ['F', 'M', 'N'];

            $member = new Member();
            $member->setGender($gender[array_rand($gender)]);
            $member->setFirstname($faker->firstname());
            $member->setLastname($faker->lastname());
            $member->setBirthdate($date);
            $member->setPhoneNumber('06'.(string)$faker->randomNumber(8, true));
            $member->setEmail($faker->email());
            $member->setIdCaf($faker->randomNumber(7, true));
            $member->setIdPoleEmploi($faker->randomNumber(7, true));
            $member->setNote($faker->paragraphs(2, true));
            $member->addVulnerability($vulnerabilityList[mt_rand(0, 2)]);
            $member->addDifficulty($difficultyList[mt_rand(0, 13)]);

        // Fixtures des supports
        // On créé un support pour chaque member
            $support = new Support();
            $support->setEntryDate($date);
            $support->setOngoingJob($faker->sentence(3));
            $support->setOngoingFormation($faker->sentence(3));
            $support->setWorksitePosition($faker->boolean());
            $support->setFormationPositioning($faker->boolean());
            $support->setUser($user);
            $support->setTargetedAxis($axisList[mt_rand(0,3)]);
            $support->setPlace($placeList[mt_rand(0,12)]);
            $support->addExternalTool($toolList[mt_rand(0,3)]);
            $support->setNote($faker->sentence(8));

        // Fixtures des End supports
            $endSupport = new EndSupport();
            $endSupport->setReleaseDate($date);
            $endSupport->addDifficulty($difficultyList[mt_rand(0, 13)]);
            $endSupport->setReleaseReason($reasonList[mt_rand(0, 6)]);
            

            $support->setMember($member);
            $support->setEndSupport($endSupport);
            $member->setSupport($support);
            $endSupport->setSupport($support);

            $manager->persist($member);
            $manager->persist($support);
            $manager->persist($endSupport);
            $manager->flush();
        }
    }
}
