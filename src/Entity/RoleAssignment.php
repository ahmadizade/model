<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleAssignmentRepository")
 */
class RoleAssignment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $role_assignment_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_ref_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $role_ref_id;

    public function getRoleAssignmentId(): ?int
    {
        return $this->role_assignment_id;
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

    public function getRoleRefId(): ?int
    {
        return $this->role_ref_id;
    }

    public function setRoleRefId(int $role_ref_id): self
    {
        $this->role_ref_id = $role_ref_id;

        return $this;
    }
}
