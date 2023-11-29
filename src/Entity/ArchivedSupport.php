<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Place;
use App\Entity\Member;
use App\Entity\ExternalTool;
use App\Entity\TargetedAxis;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SupportRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\EndSupport;

/**
 * @ORM\Entity(repositoryClass=SupportRepository::class)
 */
class ArchivedSupport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_group", "member"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"member"})
     */
    private $entry_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"member"})
     */
    private $ongoing_job;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"member"})
     */
    private $ongoing_formation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"member"})
     */
    private $worksite_position;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"member"})
     */
    private $formation_positioning;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"member"})
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $targeted_axis;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $external_tool = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $end_support;

    /**
     * @ORM\OneToOne(targetEntity=ArchivedMember::class, mappedBy="archived_support", cascade={"persist", "remove"})
     */
    private $archivedMember;

    public function __construct()
    {
        $this->external_tool = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntryDate(): ?\DateTimeImmutable
    {
        return $this->entry_date;
    }

    public function setEntryDate(\DateTimeImmutable $entry_date): self
    {
        $this->entry_date = $entry_date;

        return $this;
    }

    public function getOngoingJob(): ?string
    {
        return $this->ongoing_job;
    }

    public function setOngoingJob(?string $ongoing_job): self
    {
        $this->ongoing_job = $ongoing_job;

        return $this;
    }

    public function getOngoingFormation(): ?string
    {
        return $this->ongoing_formation;
    }

    public function setOngoingFormation(?string $ongoing_formation): self
    {
        $this->ongoing_formation = $ongoing_formation;

        return $this;
    }

    public function isWorksitePosition(): ?bool
    {
        return $this->worksite_position;
    }

    public function setWorksitePosition(?bool $worksite_position): self
    {
        $this->worksite_position = $worksite_position;

        return $this;
    }

    public function isFormationPositioning(): ?bool
    {
        return $this->formation_positioning;
    }

    public function setFormationPositioning(?bool $formation_positioning): self
    {
        $this->formation_positioning = $formation_positioning;

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

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getTargetedAxis(): ?string
    {
        return $this->targeted_axis;
    }

    public function setTargetedAxis(string $targeted_axis): self
    {
        $this->targeted_axis = $targeted_axis;

        return $this;
    }

    public function getExternalTool(): ?array
    {
        return $this->external_tool;
    }

    public function setExternalTool(?array $external_tool): self
    {
        $this->external_tool = $external_tool;

        return $this;
    }

    public function getEndSupport(): ?string
    {
        return $this->end_support;
    }

    public function setEndSupport(string $end_support): self
    {
        $this->end_support = $end_support;

        return $this;
    }

    public function getArchivedMember(): ?ArchivedMember
    {
        return $this->archivedMember;
    }

    public function setArchivedMember(?ArchivedMember $archivedMember): self
    {
        // unset the owning side of the relation if necessary
        if ($archivedMember === null && $this->archivedMember !== null) {
            $this->archivedMember->setArchivedSupport(null);
        }

        // set the owning side of the relation if necessary
        if ($archivedMember !== null && $archivedMember->getArchivedSupport() !== $this) {
            $archivedMember->setArchivedSupport($this);
        }

        $this->archivedMember = $archivedMember;

        return $this;
    }
}
