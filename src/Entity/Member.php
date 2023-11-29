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
class Member
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"member"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"member"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"member"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"member"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"member"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"member"})
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"member"})
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"member"})
     */
    private $id_caf;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"member"})
     */
    private $id_pole_emploi;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"member"})
     */
    private $note;

    /**
     * @ORM\OneToOne(targetEntity=Support::class, mappedBy="member", cascade={"persist", "remove"})
     *  @Groups({"member"})
     */
    private $support;

    /**
     * @ORM\ManyToMany(targetEntity=Vulnerability::class, inversedBy="members", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"member"})
     */
    private $vulnerability;

    /**
     * @ORM\ManyToMany(targetEntity=Difficulty::class, inversedBy="members", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"member"})
     */
    private $difficulty;

    public function __construct()
    {
        $this->vulnerability = new ArrayCollection();
        $this->difficulty = new ArrayCollection();
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

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
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

    public function getSupport(): ?Support
    {
        return $this->support;
    }

    public function setSupport(Support $support): self
    {
        // set the owning side of the relation if necessary
        if ($support->getMember() !== $this) {
            $support->setMember($this);
        }

        $this->support = $support;

        return $this;
    }

    /**
     * @return Collection<int, Vulnerability>
     */
    public function getVulnerability(): Collection
    {
        return $this->vulnerability;
    }

    public function addVulnerability(Vulnerability $vulnerability): self
    {
        if (!$this->vulnerability->contains($vulnerability)) {
            $this->vulnerability[] = $vulnerability;
        }

        return $this;
    }

    public function removeVulnerability(Vulnerability $vulnerability): self
    {
        $this->vulnerability->removeElement($vulnerability);

        return $this;
    }

    /**
     * @return Collection<int, Difficulty>
     */
    public function getDifficulty(): Collection
    {
        return $this->difficulty;
    }

    public function addDifficulty(Difficulty $difficulty): self
    {
        if (!$this->difficulty->contains($difficulty)) {
            $this->difficulty[] = $difficulty;
        }

        return $this;
    }

    public function removeDifficulty(Difficulty $difficulty): self
    {
        $this->difficulty->removeElement($difficulty);

        return $this;
    }
}
