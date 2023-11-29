<?php

namespace App\Entity;

use App\Entity\Member;
use App\Entity\EndSupport;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DifficultyRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DifficultyRepository::class)
 */
class Difficulty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"member", "difficulty"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"member", "difficulty"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Member::class, mappedBy="difficulty")
     * @ORM\JoinColumn(nullable=true)
     */
    private $members;

    /**
     * @ORM\ManyToMany(targetEntity=EndSupport::class, mappedBy="difficulty", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $endSupports;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->endSupports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->addDifficulty($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            $member->removeDifficulty($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, EndSupport>
     */
    public function getEndSupports(): Collection
    {
        return $this->endSupports;
    }

    public function addEndSupport(EndSupport $endSupport): self
    {
        if (!$this->endSupports->contains($endSupport)) {
            $this->endSupports[] = $endSupport;
            $endSupport->addDifficulty($this);
        }

        return $this;
    }

    public function removeEndSupport(EndSupport $endSupport): self
    {
        if ($this->endSupports->removeElement($endSupport)) {
            $endSupport->removeDifficulty($this);
        }

        return $this;
    }
}
