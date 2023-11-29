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
class Support
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="supports", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"member"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="supports", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"member"})
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity=TargetedAxis::class, inversedBy="supports", cascade={"persist"})
     * @Groups({"member"})
     */
    private $targeted_axis;

    /**
     * @ORM\ManyToMany(targetEntity=ExternalTool::class, inversedBy="supports", cascade={"persist"})
     * @Groups({"member"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $external_tool;

    /**
     * @ORM\OneToOne(targetEntity=EndSupport::class, inversedBy="support", cascade={"persist", "remove"})
     * @Groups({"member"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $end_support;

    /**
     * @ORM\OneToOne(targetEntity=Member::class, inversedBy="support", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $member;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getTargetedAxis(): ?TargetedAxis
    {
        return $this->targeted_axis;
    }

    public function setTargetedAxis(?TargetedAxis $targeted_axis): self
    {
        $this->targeted_axis = $targeted_axis;

        return $this;
    }

    /**
     * @return Collection<int, ExternalTool>
     */
    public function getExternalTool(): Collection
    {
        return $this->external_tool;
    }

    public function addExternalTool(ExternalTool $externalTool): self
    {
        if (!$this->external_tool->contains($externalTool)) {
            $this->external_tool[] = $externalTool;
        }

        return $this;
    }

    public function removeExternalTool(ExternalTool $externalTool): self
    {
        $this->external_tool->removeElement($externalTool);

        return $this;
    }

    public function getEndSupport(): ?EndSupport
    {
        return $this->end_support;
    }

    public function setEndSupport(?EndSupport $end_support): self
    {
        $this->end_support = $end_support;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        return $this;
    }
}
