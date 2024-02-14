<?php

namespace App\DTO;

class TripDTO
{
    public $campusName;
    public $tripName;
    public $dateTimeStart;
    public $dateTimeEnd;
    public $organizer;
    public $subscribe;
    public $unsubscribe;
    public $lastTrip;


    /**
     * @return mixed
     */
    public function getCampusName()
    {
        return $this->campusName;
    }

    /**
     * @param mixed $campusName
     */
    public function setCampusName($campusName): void
    {
        $this->campusName = $campusName;
    }

    /**
     * @return mixed
     */
    public function getTripName()
    {
        return $this->tripName;
    }

    /**
     * @param mixed $tripName
     */
    public function setTripName($tripName): void
    {
        $this->tripName = $tripName;
    }

    /**
     * @return mixed
     */
    public function getDateTimeStart()
    {
        return $this->dateTimeStart;
    }

    /**
     * @param mixed $dateTimeStart
     */
    public function setDateTimeStart($dateTimeStart): void
    {
        $this->dateTimeStart = $dateTimeStart;
    }

    /**
     * @return mixed
     */
    public function getDateTimeEnd()
    {
        return $this->dateTimeEnd;
    }

    /**
     * @param mixed $dateTimeEnd
     */
    public function setDateTimeEnd($dateTimeEnd): void
    {
        $this->dateTimeEnd = $dateTimeEnd;
    }

    /**
     * @return mixed
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @param mixed $organizer
     */
    public function setOrganizer($organizer): void
    {
        $this->organizer = $organizer;
    }

    /**
     * @return mixed
     */
    public function getSubscribe()
    {
        return $this->subscribe;
    }

    /**
     * @param mixed $subscribe
     */
    public function setSubscribe($subscribe): void
    {
        $this->subscribe = $subscribe;
    }

    /**
     * @return mixed
     */
    public function getUnsubscribe()
    {
        return $this->unsubscribe;
    }

    /**
     * @param mixed $unsubscribe
     */
    public function setUnsubscribe($unsubscribe): void
    {
        $this->unsubscribe = $unsubscribe;
    }

    /**
     * @return mixed
     */
    public function getLastTrip()
    {
        return $this->lastTrip;
    }

    /**
     * @param mixed $lastTrip
     */
    public function setLastTrip($lastTrip): void
    {
        $this->lastTrip = $lastTrip;
    }









}
