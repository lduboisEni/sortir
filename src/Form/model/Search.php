<?php

namespace App\Form\model;

use App\Entity\Campus;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\String_;

class Search
{
    private Campus $campus;
    private String $nameContain;
    private $begindate;
    private $enddate;
    private Boolean $isOrganiser;
    private Boolean $isRegistered;
    private Boolean $isNotRegistered;
    private Boolean $isPassed;

    /**
     * @return Campus
     */
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     * @return Search
     */
    public function setCampus(Campus $campus): Search
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return String
     */
    public function getNameContain(): string
    {
        return $this->nameContain;
    }

    /**
     * @param String $nameContain
     * @return Search
     */
    public function setNameContain(string $nameContain): Search
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