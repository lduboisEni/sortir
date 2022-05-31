<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $startTime;

    #[ORM\Column(type: 'integer')]
    private $lenght;

    #[ORM\Column(type: 'date')]
    private $registrationTimeLimit;

    #[ORM\Column(type: 'integer')]
    private $maxRegistration;

    #[ORM\Column(type: 'text', nullable: true)]
    private $tripInfos;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'trips')]
    private $users;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'organisedTrips')]
    #[ORM\JoinColumn(nullable: false)]
    private $organiser;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'campusTrips')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\ManyToOne(targetEntity: Place::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $place;

    public function __construct()
    {
        $this->users = new ArrayCollection();

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

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getLenght(): ?int
    {
        return $this->lenght;
    }

    public function setLenght(int $lenght): self
    {
        $this->lenght = $lenght;

        return $this;
    }

    public function getRegistrationTimeLimit(): ?\DateTimeInterface
    {
        return $this->registrationTimeLimit;
    }

    public function setRegistrationTimeLimit(\DateTimeInterface $registrationTimeLimit): self
    {
        $this->registrationTimeLimit = $registrationTimeLimit;

        return $this;
    }

    public function getMaxRegistration(): ?int
    {
        return $this->maxRegistration;
    }

    public function setMaxRegistration(int $maxRegistration): self
    {
        $this->maxRegistration = $maxRegistration;

        return $this;
    }

    public function getTripInfos(): ?string
    {
        return $this->tripInfos;
    }

    public function setTripInfos(?string $tripInfos): self
    {
        $this->tripInfos = $tripInfos;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getOrganiser(): ?User
    {
        return $this->organiser;
    }

    public function setOrganiser(?User $organiser): self
    {
        $this->organiser = $organiser;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

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

}
