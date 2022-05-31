<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 255)]
    private $phoneNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private $eMail;

    #[ORM\Column(type: 'string', length: 255)]
    private $passWord;

    #[ORM\Column(type: 'boolean')]
    private $admin;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\ManyToMany(targetEntity: Trip::class, mappedBy: 'users')]
    private $trips;

    #[ORM\OneToMany(mappedBy: 'organiser', targetEntity: Trip::class, orphanRemoval: true)]
    private $organisedTrips;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'campusUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
        $this->organisedTrips = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEMail(): ?string
    {
        return $this->eMail;
    }

    public function setEMail(string $eMail): self
    {
        $this->eMail = $eMail;

        return $this;
    }

    public function getPassWord(): ?string
    {
        return $this->passWord;
    }

    public function setPassWord(string $passWord): self
    {
        $this->passWord = $passWord;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->addUser($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            $trip->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getOrganisedTrips(): Collection
    {
        return $this->organisedTrips;
    }

    public function addOrganisedTrip(Trip $organisedTrip): self
    {
        if (!$this->organisedTrips->contains($organisedTrip)) {
            $this->organisedTrips[] = $organisedTrip;
            $organisedTrip->setOrganiser($this);
        }

        return $this;
    }

    public function removeOrganisedTrip(Trip $organisedTrip): self
    {
        if ($this->organisedTrips->removeElement($organisedTrip)) {
            // set the owning side to null (unless already changed)
            if ($organisedTrip->getOrganiser() === $this) {
                $organisedTrip->setOrganiser(null);
            }
        }

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
}
