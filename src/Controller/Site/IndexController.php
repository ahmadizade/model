<?php

namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/site/index", name="site_index")
     */
    public function index()
    {
        return $this->render('site/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
