<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendshipsRepository")
 */
class Friendships
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
    private $user_ref_id_1;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_ref_id_2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRefId1(): ?int
    {
        return $this->user_ref_id_1;
    }

    public function setUserRefId1(int $user_ref_id_1): self
    {
        $this->user_ref_id_1 = $user_ref_id_1;

        return $this;
    }

    public function getUserRefId2(): ?int
    {
        return $this->user_ref_id_2;
    }

    public function setUserRefId2(int $user_ref_id_2): self
    {
        $this->user_ref_id_2 = $user_ref_id_2;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
