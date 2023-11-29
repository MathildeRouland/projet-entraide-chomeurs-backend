<?php

namespace App\Entity;

use App\Entity\Support;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TargetedAxisRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TargetedAxisRepository::class)
 */
class TargetedAxis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"member", "member_archived", "targeted_axis"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"member", "member_archived", "targeted_axis"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Support::class, mappedBy="targeted_axis", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $supports;

    public function __construct()
    {
        $this->supports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Support>
     */
    public function getSupports(): Collection
    {
        return $this->supports;
    }

    public function addSupport(Support $support): self
    {
        if (!$this->supports->contains($support)) {
            $this->supports[] = $support;
            $support->setTargetedAxis($this);
        }

        return $this;
    }

    public function removeSupport(Support $support): self
    {
        if ($this->supports->removeElement($support)) {
            // set the owning side to null (unless already changed)
            if ($support->getTargetedAxis() === $this) {
                $support->setTargetedAxis(null);
            }
        }

        return $this;
    }
}
