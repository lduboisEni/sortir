<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: Trip::class, orphanRemoval: true)]
    private $campusTrips;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: User::class, orphanRemoval: true)]
    private $campusUsers;

    public function __construct()
    {
        $this->campusTrips = new ArrayCollection();
        $this->campusUsers = new ArrayCollection();
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
     * @return Collection<int, Trip>
     */
    public function getCampusTrips(): Collection
    {
        return $this->campusTrips;
    }

    public function addCampusTrip(Trip $campusTrip): self
    {
        if (!$this->campusTrips->contains($campusTrip)) {
            $this->campusTrips[] = $campusTrip;
            $campusTrip->setCampus($this);
        }

        return $this;
    }

    public function removeCampusTrip(Trip $campusTrip): self
    {
        if ($this->campusTrips->removeElement($campusTrip)) {
            // set the owning side to null (unless already changed)
            if ($campusTrip->getCampus() === $this) {
                $campusTrip->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCampusUsers(): Collection
    {
        return $this->campusUsers;
    }

    public function addCampusUser(User $campusUser): self
    {
        if (!$this->campusUsers->contains($campusUser)) {
            $this->campusUsers[] = $campusUser;
            $campusUser->setCampus($this);
        }

        return $this;
    }

    public function removeCampusUser(User $campusUser): self
    {
        if ($this->campusUsers->removeElement($campusUser)) {
            // set the owning side to null (unless already changed)
            if ($campusUser->getCampus() === $this) {
                $campusUser->setCampus(null);
            }
        }

        return $this;
    }
}
