<?php

namespace App\Controller\Admin;

use App\Entity\Rule;
use App\Service\DataBase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/config", name="admin_config")
     */
    public function config(DataBase $dataBase)
    {
        $routes = $this->get('router')->getRouteCollection()->all();

        $adminControllers = [];

        foreach ($routes as $routeName => $route) {
            $controller = explode('::', $route->getDefault('_controller'));
            if (strpos($controller[0], 'App\Controller\Admin') !== false) {
                $adminControllers[$controller[0]] = $routeName;
            }
        }

        $config = [];

        foreach ($adminControllers as $adminController => $routeName) {
            $controller = new $adminController();
            if (method_exists($controller, 'registerRouts')) {
                $config = array_merge($config, $controller->registerRouts());
            }
        }

        if(isset($_GET['force'])){
            $query = "DELETE FROM rule WHERE role_ref_id = 1";
            $dataBase->Delete($query);
        }

        $em = $this->getDoctrine()->getManager();

        foreach ($config as $route){

            $query = "SELECT rule_id FROM rule WHERE `route_key` = :route_key";
            if(!$dataBase->fetchAssoc($query, ['route_key' => $route['key']])){
                // add new rule
                if(isset($route['methods']) && is_array($route['methods'])){
                    foreach ($route['methods'] as $method){
                        $rule = new Rule();
                        $rule->setRoleRefId(1); // sysadmin role
                        $rule->setRouteName($route['route_name']);
                        $rule->setRouteKey($route['key']);
                        $rule->setMethod($method);

                        $em->persist($rule);
                        $em->flush();
                    }
                }else{

                    $rule = new Rule();
                    $rule->setRoleRefId(1); // sysadmin role
                    $rule->setRouteName($route['route_name']);
                    $rule->setRouteKey($route['key']);
                    $rule->setMethod('all');

                    $em->persist($rule);
                    $em->flush();

                }

            }

        }

        return new Response('ok');
    }

}
