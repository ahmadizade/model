<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolesRepository")
 */
class Roles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $role_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role_type = 'core';

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function getRoleName(): ?string
    {
        return $this->role_name;
    }

    public function setRoleName(string $role_name): self
    {
        $this->role_name = $role_name;

        return $this;
    }

    public function getRoleType(): ?string
    {
        return $this->role_type;
    }

    public function setRoleType(string $role_type): self
    {
        $this->role_type = $role_type;

        return $this;
    }
}
