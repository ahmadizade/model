<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetaRepository")
 */
class Meta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $meta_id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $meta_key;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $user_ref_id = 0;

    /**
     * @ORM\Column(type="smallint", options={"default" : 1})
     */
    private $status = 1;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $parent = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    public function getMetaId(): ?int
    {
        return $this->meta_id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getUserRefId(): ?int
    {
        return $this->user_ref_id;
    }

    public function setUserRefId(?int $user_ref_id): self
    {
        $this->user_ref_id = $user_ref_id;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at = null): self
    {
        if(!$created_at)
            $created_at = new \DateTime();

        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at = null): self
    {
        if(!$updated_at)
            $updated_at = new \DateTime();

        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $deleted_at = null): self
    {
        if(!$deleted_at)
            $deleted_at = new \DateTime();

        $this->deleted_at = $deleted_at;

        return $this;
    }
}
