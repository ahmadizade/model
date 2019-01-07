<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PagesRepository")
 */
class Pages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $page_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page_slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $page_title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $page_params;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $template;

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
     * @ORM\Column(type="string", columnDefinition="enum('active', 'inactive')")
     */
    private $status;

    public function getPageId(): ?int
    {
        return $this->page_id;
    }

    public function getPageSlug(): ?string
    {
        return $this->page_slug;
    }

    public function setPageSlug(string $page_slug): self
    {
        $this->page_slug = $page_slug;

        return $this;
    }

    public function getPageTitle(): ?string
    {
        return $this->page_title;
    }

    public function setPageTitle(?string $page_title): self
    {
        $this->page_title = $page_title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function setDeletedAt(\DateTimeInterface $deleted_at = null): self
    {
        if(!$deleted_at)
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

    public function getPageType(): ?string
    {
        return $this->page_type;
    }

    public function setPageType(string $page_type): self
    {
        $this->page_type = $page_type;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getPageParams(): ?array
    {
        $this->page_params = json_decode($this->page_params,true);

        return $this->page_params;
    }

    public function setPageParams($page_params): self
    {
        if(is_array($page_params))
            $page_params = json_encode($page_params,JSON_UNESCAPED_UNICODE);

        $this->page_params = $page_params;

        return $this;
    }

    public function getParamByKey($key): ?array
    {
        /*
         * if getPageParams called first
         */
        if(is_array($this->page_params)){
            if(isset($this->page_params[$key])){
                return $this->page_params[$key];
            }
            return [];
        }

        $this->page_params = json_decode($this->page_params,true);

        if(isset($this->page_params[$key])){
            return $this->page_params[$key];
        }
        return [];
    }

}
