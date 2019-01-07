<?php

namespace App\Controller\Components\Account;

use App\Service\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Param ApiHelper $apiHelper
     * @Route("/account/logout", name="logout")
     */
    public function index(ApiHelper $apiHelper)
    {

        try {
            $session = new Session();
            $session->invalidate();
            return $apiHelper->CustomResponse('',1,[],'site_homepage');

        } catch (\Exception $e) {

        }

        return $apiHelper->CustomResponse('',1,[],'site_homepage');

    }
}
