<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RuleRepository")
 */
class Rule
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $rule_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $route_key;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $route_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $method;

    /**
     * @ORM\Column(type="integer")
     */
    private $role_ref_id;

    public function getRuleId(): ?int
    {
        return $this->rule_id;
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

    public function getRouteName(): ?string
    {
        return $this->route_name;
    }

    public function setRouteName(string $route_name): self
    {
        $this->route_name = $route_name;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getRouteKey(): ?string
    {
        return $this->route_key;
    }

    public function setRouteKey(string $route_key): self
    {
        $this->route_key = $route_key;

        return $this;
    }
}
