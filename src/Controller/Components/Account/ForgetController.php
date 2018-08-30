<?php

namespace App\Controller\Components\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForgetController extends AbstractController
{
    /**
     * @Route("/components/account/forget", name="components_account_forget")
     */
    public function index()
    {
        return $this->render('components/account/forget/index.html.twig', [
            'controller_name' => 'ForgetController',
        ]);
    }
}
