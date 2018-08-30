<?php

namespace App\Controller\Panel;

use App\Service\Cleaning;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PanelController extends Controller
{
    public function index()
    {
        return $this->render('panel/panel/index.html.twig', [
            'controller_name' => 'PanelController',
        ]);
    }
}
