<?php

namespace App\Controller\Admin\Users;

use App\Controller\Admin\AdminController;
use App\Service\DataBase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AdminController
{

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/users", name="admin_all_users")
     */
    public function all(DataBase $dataBase)
    {
        $users = $dataBase->fetchAll('SELECT * FROM users WHERE deleted_at is null');
        return $this->render('admin/users/All.html.twig', ['users' => $users]);
    }

    /**
     * @return array
     */
    public function registerRouts(){
        return [
            [
                'key' => 'allUsers',
                'label' => 'مشاهده لیست کاربران',
                'route_name' => 'admin_all_users',
            ]
        ];
    }
}
