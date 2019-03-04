<?php

namespace App\Controller\Admin\Security;

use App\Service\DataBase;
use App\Service\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RulesController extends AbstractController
{
    public $restrictedRoles;

    public function __construct()
    {
        $this->restrictedRoles = ['sysadmin' => '1' , 'typical user' => '2' ];
    }

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/rules", name="admin_all_rules")
     */
    public function AllAction(DataBase $dataBase)
    {
        $query = "SELECT * FROM rule INNER JOIN roles on role_ref_id = role_id";
        $rules = $dataBase->fetchAll($query);

        return $this->render('admin/Security/Rules/All.html.twig', compact('rules'));
    }

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/rule/edit/{roleId}", name="admin_edit_rule_of_role")
     */
    public function EditAction($roleId,Request $request,DataBase $dataBase){

        if( in_array($roleId, $this->restrictedRoles) ){
         //   throw new AccessDeniedHttpException();
        }

        $allRoutes = $this->getAllRoutesList();

        $query = "SELECT * FROM rule WHERE role_ref_id = :role_id";
        $allRules = $dataBase->fetchAll($query, ['role_id' => $roleId]);


        if($request->isMethod('GET')){

            $rulesKey = [];
            foreach ($allRules as $rule){
                $rulesKey[$rule['route_key']] = $rule;
            }

            return $this->render('admin/Security/Rules/Edit.html.twig', ['rulesKey' => $rulesKey,'allRoutes' => $allRoutes]);
        }

        $currentUserRoles = $this->getCurrentUserRoles();
        if(empty($currentUserRoles ))
            exit;

        if(is_array($currentUserRoles))
            $currentUserRoles  = implode(',',$currentUserRoles );
        else
            exit;


        // get current user rules
        $query = "SELECT * FROM rule WHERE role_ref_id IN ($currentUserRoles)" ;
        $currentUserRules = $dataBase->fetchAll($query);

        $currentUserRulesKey = [];
        foreach ($currentUserRules as $rule){
            $currentUserRulesKey[$rule['route_key']] = $rule;
        }


        $editingRules = array_intersect_key($_POST['rules'], $currentUserRulesKey);
dump($allRules);exit;

        $query = "DELETE FROM rule WHERE role_ref_id = :role_id";
        $dataBase->Delete($query, ['role_id' => $roleId]);

        foreach ($allRules as $rule) {
            foreach ($editingRules as $key => $status) {
                if($status );
            }
        }

        foreach ($editingRules as $key => $status){
            if($status === 0){ // delete
                $query = "DELETE FROM rule WHERE role_ref_id = :role_id AND route_key = :route_key";
                $dataBase->Delete($query, ['role_id' => $roleId, 'route_key' => $key]);
            }else{ // insert
                foreach ($allRules as $rule){
                    if($rule['route_key'] == $key){
                        $query = "INSERT INTO rule (route_key, route_name, method, role_ref_id) VALUES (:route_key, :route_name, :method,:role_id)";
                        $dataBase->Insert($query, ['role_id' => $roleId, 'route_key' => $key, 'route_name' => $rulesKey[$key]['route_name'],'method' => $rulesKey[$key]['route_name']]);
                    }
                }

            }
        }



        foreach ($editingRules as $key => $status){
            if($status === 0){ // delete
                $query = "DELETE FROM rule WHERE role_ref_id = :role_id AND route_key = :route_key";
                $dataBase->Delete($query, ['role_id' => $roleId, 'route_key' => $key]);
            }else{ // insert
                foreach ($allRules as $rule){
                    if($rule['route_key'] == $key){
                        $query = "INSERT INTO rule (route_key, route_name, method, role_ref_id) VALUES (:route_key, :route_name, :method,:role_id)";
                        $dataBase->Insert($query, ['role_id' => $roleId, 'route_key' => $key, 'route_name' => $rulesKey[$key]['route_name'],'method' => $rulesKey[$key]['route_name']]);
                    }
                }

            }
        }

        dump($currentUserRules);
        dump($_POST['rules']);
        exit;

        dump($_POST);exit;

    }



    public function getAllRoutesList()
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
        return $config;
    }

    public function getCurrentUserRoles(){
        $session = new Session();
        return $session->get('roles');
    }




}
