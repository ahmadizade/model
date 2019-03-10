<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $category_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category_slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category_type;

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
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $category_excerpt;


    public function __construct()
    {
        $this->toursToCategories = new ArrayCollection();
    }


    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function getCategoryTitle(): ?string
    {
        return $this->category_title;
    }

    public function setCategoryTitle(string $category_title): self
    {
        $this->category_title = $category_title;

        return $this;
    }

    public function getCategorySlug(): ?string
    {
        return $this->category_slug;
    }

    public function setCategorySlug(string $category_slug): self
    {
        $this->category_slug = $category_slug;

        return $this;
    }

    public function getCategoryType(): ?string
    {
        return $this->category_type;
    }

    public function setCategoryType(?string $category_type): self
    {
        $this->category_type = $category_type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at = null): self
    {
        if (!$created_at)
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
        if (!$updated_at)
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
        if (!$deleted_at)
            $deleted_at = new \DateTime();

        $this->deleted_at = $deleted_at;

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

    public function getCategoryExcerpt(): ?string
    {
        return $this->category_excerpt;
    }

    public function setCategoryExcerpt(?string $category_excerpt): self
    {
        $this->category_excerpt = $category_excerpt;

        return $this;
    }

}
