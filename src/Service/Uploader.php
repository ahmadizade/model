<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader extends Controller
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    function UploadFile(UploadedFile $file = null, $params = []){
        if(!$file)
            return null;

        if(!isset($params['type']))
            $params['type'] = 'general';

        $mime = $file->getMimeType();
        if(in_array($mime,['image/jpeg','image/png','image/jpg'])){
            list($width,$height) = getimagesize($file->getRealPath());
            if(!$width)
                return null;
            $name = $file->getClientOriginalName();
            $ex = substr($name,strrpos($name,'.'),strlen($name));

            $base_name = substr($name,0,strpos($name,'.'));
            $base_name = str_replace('.','',$base_name);
            $base_name = str_replace(' ','_',$base_name);

            $name = $base_name.$ex;

            $fs = new Filesystem();
            if($params['type'] == 'general'){
                $upload_path = 'media/general/img';
            }
            elseif ($params['type'] == 'post_thumbnail'){
                $upload_path = 'media/posts/img/thumbnails';
            }else
                return null;

            if(!$fs->exists($upload_path))
                $fs->mkdir($upload_path);

            $file->move($upload_path,$name);

            return ['uri' => $upload_path.'/'.$name, 'name' => $name, 'mime' => $mime];
        }

        return null;
    }

    function RemoveFile($uri){
        if(!$uri)
            return false;
        $fs = new Filesystem();
        try {
            if ($fs->exists($uri)){
                $fs->remove($uri);
                return true;
            }
        }catch (\Exception $exception) {
            return false;
        }

    }

}
