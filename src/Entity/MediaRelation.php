<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRelationRepository")
 */
class MediaRelation
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
    private $media_ref_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reference_id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reference_type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $media_params;

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

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaRefId(): ?int
    {
        return $this->media_ref_id;
    }

    public function setMediaRefId(int $media_ref_id): self
    {
        $this->media_ref_id = $media_ref_id;

        return $this;
    }

    public function getReferenceId(): ?int
    {
        return $this->reference_id;
    }

    public function setReferenceId(int $reference_id): self
    {
        $this->reference_id = $reference_id;

        return $this;
    }

    public function getReferenceType(): ?string
    {
        return $this->reference_type;
    }

    public function setReferenceType(?string $reference_type): self
    {
        $this->reference_type = $reference_type;

        return $this;
    }

    public function getMediaParams(): ?string
    {
        return $this->media_params;
    }

    public function setMediaParams(?string $media_params): self
    {
        $this->media_params = $media_params;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
