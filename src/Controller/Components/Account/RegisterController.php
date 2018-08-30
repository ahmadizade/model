<?php

namespace App\Controller\Components\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/components/account/register", name="components_account_register")
     */
    public function index()
    {
        return $this->render('components/account/register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
