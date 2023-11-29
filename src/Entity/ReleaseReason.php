<?php

namespace App\Entity;

use App\Entity\EndSupport;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReleaseReasonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReleaseReasonRepository::class)
 */
class ReleaseReason
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("release_reason")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"release_reason", "end_support"})
     */
    private $reason;

    /**
     * @ORM\OneToMany(targetEntity=EndSupport::class, mappedBy="release_reason", cascade={"persist", "remove"})
     */
    private $endSupports;

    public function __construct()
    {
        $this->endSupports = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

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
            $endSupport->setReleaseReason($this);
        }

        return $this;
    }

    public function removeEndSupport(EndSupport $endSupport): self
    {
        if ($this->endSupports->removeElement($endSupport)) {
            // set the owning side to null (unless already changed)
            if ($endSupport->getReleaseReason() === $this) {
                $endSupport->setReleaseReason(null);
            }
        }

        return $this;
    }
}
