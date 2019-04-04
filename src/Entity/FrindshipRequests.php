<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FrindshipRequestsRepository")
 */
class FrindshipRequests
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
    private $req_sender_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $req_recever_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReqSenderId(): ?int
    {
        return $this->req_sender_id;
    }

    public function setReqSenderId(int $req_sender_id): self
    {
        $this->req_sender_id = $req_sender_id;

        return $this;
    }

    public function getReqReceverId(): ?int
    {
        return $this->req_recever_id;
    }

    public function setReqReceverId(int $req_recever_id): self
    {
        $this->req_recever_id = $req_recever_id;

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
