<?php

namespace App\EventListener;

use App\Service\DataBase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthorisationListener
{

    private $container;
    private $dataBase;

    public function __construct(ContainerInterface $container, DataBase $dataBase)
    {
        $this->container = $container;
        $this->dataBase = $dataBase;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->get('_route');

        if (strpos($routeName, 'admin_') === false)
            return;

        $session = new Session();
        $roles = (array)$session->get('roles');
        if (!$roles) {
            throw new AccessDeniedHttpException();
        }

        if (in_array(1, $roles)) // is admin and can pass
            return;

        $method = $request->getMethod();

        $existRule = $this->dataBase->fetchAssoc("SELECT rule_id FROM rule WHERE route_name = :route AND (method = :method OR method = 'all') AND role_ref_id IN ( :roles )", ['route' => $routeName, 'method' => $method, 'roles' => implode(', ', $roles)]);
        if ($existRule)
            return; // pass if rule exists

        throw new AccessDeniedHttpException();

    }


}