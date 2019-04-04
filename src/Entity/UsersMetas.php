<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersMetasRepository")
 */
class UsersMetas
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
     * @ORM\Column(type="string", length=255)
     */
    private $meta_key;

    /**
     * @ORM\Column(type="text")
     */
    private $meta_value;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

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

    public function getMetaKey(): ?string
    {
        return $this->meta_key;
    }

    public function setMetaKey(string $meta_key): self
    {
        $this->meta_key = $meta_key;

        return $this;
    }

    public function getMetaValue(): ?string
    {
        return $this->meta_value;
    }

    public function setMetaValue(string $meta_value): self
    {
        $this->meta_value = $meta_value;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
