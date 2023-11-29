<?php

namespace App\Entity;

use App\Entity\Support;
use App\Entity\Difficulty;
use App\Entity\Vulnerability;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MemberRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 */
class ArchivedMember
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"member", "member_browse"})
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"member"})
     */
    private $note;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $vulnerabilities = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $difficulties = [];

    /**
     * @ORM\OneToOne(targetEntity=ArchivedSupport::class, inversedBy="archivedMember", cascade={"persist", "remove"})
     */
    private $archived_support;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeImmutable $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?int $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdCaf(): ?int
    {
        return $this->id_caf;
    }

    public function setIdCaf(?int $id_caf): self
    {
        $this->id_caf = $id_caf;

        return $this;
    }

    public function getIdPoleEmploi(): ?int
    {
        return $this->id_pole_emploi;
    }

    public function setIdPoleEmploi(?int $id_pole_emploi): self
    {
        $this->id_pole_emploi = $id_pole_emploi;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getVulnerabilities(): ?array
    {
        return $this->vulnerabilities;
    }

    public function setVulnerabilities(?array $vulnerabilities): self
    {
        $this->vulnerabilities = $vulnerabilities;

        return $this;
    }

    public function getDifficulties(): ?array
    {
        return $this->difficulties;
    }

    public function setDifficulties(?array $difficulties): self
    {
        $this->difficulties = $difficulties;

        return $this;
    }

    public function getArchivedSupport(): ?ArchivedSupport
    {
        return $this->archived_support;
    }

    public function setArchivedSupport(?ArchivedSupport $archived_support): self
    {
        $this->archived_support = $archived_support;

        return $this;
    }
}
