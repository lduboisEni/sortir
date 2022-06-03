<?php

namespace App\Form\model;

use App\Entity\Campus;
use App\Repository\TripRepository;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\String_;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Search{

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'campusTrips')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\Column(type: 'string', length: 255)]
    private $nameContain;

    #[ORM\Column(type: 'date')]
    #[Assert\GreaterThanOrEqual("today", message: "La date de début doit être postérieure à aujourd'hui.")]
    private $begindate;

    #[ORM\Column(type: 'date')]
    #[Assert\GreaterThanOrEqual("today", message: "La date de fin doit être postérieure à aujourd'hui.")]
    private $enddate;

    #[ORM\Column(type: 'boolean')]
    private $isOrganiser;

    private bool $isRegistered;
    private bool $isNotRegistered;
    private bool $isPassed;

    /**
     * @return Campus
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     * @return Search
     */
    public function setCampus(?Campus $campus): Search
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return String
     */
    public function getNameContain(): ?string
    {
        return $this->nameContain;
    }

    /**
     * @param String $nameContain
     * @return Search
     */
    public function setNameContain(?string $nameContain): Search
    {
        $this->nameContain = $nameContain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBegindate()
    {
        return $this->begindate;
    }

    /**
     * @param mixed $begindate
     * @return Search
     */
    public function setBegindate($begindate)
    {
        $this->begindate = $begindate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * @param mixed $enddate
     * @return Search
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrganiser(): bool
    {
        return $this->isOrganiser;
    }

    /**
     * @param bool $isOrganiser
     * @return Search
     */
    public function setIsOrganiser(bool $isOrganiser): Search
    {
        $this->isOrganiser = $isOrganiser;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRegistered(): bool
    {
        return $this->isRegistered;
    }

    /**
     * @param bool $isRegistered
     * @return Search
     */
    public function setIsRegistered(bool $isRegistered): Search
    {
        $this->isRegistered = $isRegistered;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNotRegistered(): bool
    {
        return $this->isNotRegistered;
    }

    /**
     * @param bool $isNotRegistered
     * @return Search
     */
    public function setIsNotRegistered(bool $isNotRegistered): Search
    {
        $this->isNotRegistered = $isNotRegistered;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->isPassed;
    }

    /**
     * @param bool $isPassed
     * @return Search
     */
    public function setIsPassed(bool $isPassed): Search
    {
        $this->isPassed = $isPassed;
        return $this;
    }



}