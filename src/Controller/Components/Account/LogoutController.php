<?php

namespace App\Controller\Components\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route("/components/account/logout", name="components_account_logout")
     */
    public function index()
    {
        return $this->render('components/account/logout/index.html.twig', [
            'controller_name' => 'LogoutController',
        ]);
    }
}
