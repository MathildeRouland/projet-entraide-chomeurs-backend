<?php

namespace App\Entity;

use App\Entity\Support;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExternalToolRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ExternalToolRepository::class)
 */
class ExternalTool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"member", "external_tool"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"member", "external_tool"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Support::class, mappedBy="external_tool")
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
            $support->addExternalTool($this);
        }

        return $this;
    }

    public function removeSupport(Support $support): self
    {
        if ($this->supports->removeElement($support)) {
            $support->removeExternalTool($this);
        }

        return $this;
    }
}
