<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileVisitsRepository")
 */
class ProfileVisits
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_ref_id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $sessions;

    /**
     * @ORM\Column(type="integer")
     */
    private $visits;

    /**
     * @ORM\Column(type="integer")
     */
    private $mobile_device;

    /**
     * @ORM\Column(type="integer")
     */
    private $desktop_device;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRefId(): ?int
    {
        return $this->user_ref_id;
    }

    public function setUserRefId(int $user_ref_id): self
    {
        $this->user_ref_id = $user_ref_id;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSessions(): ?int
    {
        return $this->sessions;
    }

    public function setSessions(int $sessions): self
    {
        $this->sessions = $sessions;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function setVisits(int $visits): self
    {
        $this->visits = $visits;

        return $this;
    }

    public function getMobileDevice(): ?int
    {
        return $this->mobile_device;
    }

    public function setMobileDevice(int $mobile_device): self
    {
        $this->mobile_device = $mobile_device;

        return $this;
    }

    public function getDesktopDevice(): ?int
    {
        return $this->desktop_device;
    }

    public function setDesktopDevice(int $desktop_device): self
    {
        $this->desktop_device = $desktop_device;

        return $this;
    }
}
