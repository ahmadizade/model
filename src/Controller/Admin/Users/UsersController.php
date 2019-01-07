<?php

namespace App\Controller\Admin;

use App\Service\DataBase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller
{

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/users", name="admin_all_users")
     */
    public function all(DataBase $dataBase){
        $users = $dataBase->fetchAll('SELECT * FROM users WHERE deleted_at is null');
        return $this->render('admin/users/All.html.twig',['users' => $users]);
    }


}
