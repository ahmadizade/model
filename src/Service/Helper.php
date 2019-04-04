<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Helper extends Controller
{

    private $dataBase;
    protected $container;

    public function __construct(DataBase $dataBase, ContainerInterface $container)
    {
        $this->container = $container;
        $this->dataBase = $dataBase;
    }

    public function routeAlias($routes = [])
    {
        $routeAlias = [
            'site_homepage' => 'homepage'
        ];
        return $routeAlias;

        /*        $return = [];

                foreach ($routeAlias as $key => $alias){
                    if(isset($routes[$key]))
                        $return[$key] = $alias;
                }

                foreach ($routes as $key => $route){

                }*/
    }



}
