<?php

namespace App\Controller\Components\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/components/account/login", name="components_account_login")
     */
    public function index()
    {
        return $this->render('components/account/login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
