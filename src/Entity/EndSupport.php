<?php

namespace App\Entity;

use App\Entity\Support;
use App\Entity\Difficulty;
use App\Entity\ReleaseReason;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EndSupportRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EndSupportRepository::class)
 */
class EndSupport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("end_support")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("end_support")
     */
    private $release_date;

    /**
     * @ORM\OneToOne(targetEntity=Support::class, mappedBy="end_support", cascade={"persist", "remove"})
     */
    private $support;

    /**
     * @ORM\ManyToMany(targetEntity=Difficulty::class, inversedBy="endSupports")
     */
    private $difficulty;

    /**
     * @ORM\ManyToOne(targetEntity=ReleaseReason::class, inversedBy="endSupports")
     * @Groups("end_support")
     */
    private $release_reason;


    public function __construct()
    {
        $this->difficulty = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeImmutable $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getSupport(): ?Support
    {
        return $this->support;
    }

    public function setSupport(?Support $support): self
    {
        // unset the owning side of the relation if necessary
        if ($support === null && $this->support !== null) {
            $this->support->setEndSupport(null);
        }

        // set the owning side of the relation if necessary
        if ($support !== null && $support->getEndSupport() !== $this) {
            $support->setEndSupport($this);
        }

        $this->support = $support;

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

    public function getReleaseReason(): ?ReleaseReason
    {
        return $this->release_reason;
    }

    public function setReleaseReason(?ReleaseReason $release_reason): self
    {
        $this->release_reason = $release_reason;

        return $this;
    }
}
