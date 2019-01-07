<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Media extends Controller
{

    private $dataBase;
    protected $container;

    public function __construct(DataBase $dataBase, ContainerInterface $container)
    {
        $this->container = $container;
        $this->dataBase = $dataBase;
    }

    public function getAll($reference_type, $reference_id = 0,$type = []){
        if(!is_array($type))
            $type = [$type];

        $where = ' 1 ';

        if($type){
            $type = implode("','",$type);
            $where .= " AND type IN ('$type') ";
        }
        if($reference_id){
            if(is_array($reference_id)){
                $reference_id = implode("','",$reference_id);
                $where .= " AND reference_id IN ('$reference_id') ";
            }
            else
                $where .= " AND reference_id = '$reference_id' ";
        }

        $query = "SELECT type,uri,mime,reference_id FROM media INNER JOIN media_relation on media_id = media_ref_id AND reference_type = :reference_type where $where";
        $results = $this->dataBase->fetchAll($query,['reference_type' => $reference_type]);

        if($results){
            foreach ($results as $key => $result){
                if($result['type'] == $reference_type.'-gallery')
                    $results[$key]['type'] = 'gallery';
                if($result['type'] == $reference_type.'-wallpaper')
                    $results[$key]['type'] = 'wallpaper';
                if($result['type'] == $reference_type.'-thumbnail')
                    $results[$key]['type'] = 'thumbnail';
            }

            return $results;
        }

        return [];
    }

    public function getThumbnail($reference_type, $reference_id = 0){

        return $this->getAll($reference_type,$reference_id,$reference_type.'-thumbnail');

    }

    public function getWallpaper($reference_type, $reference_id = 0){

        return $this->getAll($reference_type,$reference_id,$reference_type.'-wallpaper');

    }

    public function getGallery($reference_type, $reference_id = 0){

        return $this->getAll($reference_type,$reference_id,$reference_type.'-gallery');

    }

    public function setAll($reference_type, &$values,$type = []){
        $reference_ids = [];
        foreach ($values as $id){
            array_push($reference_ids,$id[$reference_type.'_id']);
        }
        $thumbnailes =  $this->getAll($reference_type,$reference_ids,$type);
        foreach ($values as $key => $value){
            $values[$key]['medias'] = [];
            foreach ($thumbnailes as $key2 => $value2){
                if($value[$reference_type.'_id'] == $value2['reference_id']){
                    array_push($values[$key]['medias'],$value2);
                    unset($value2);
                    break;
                }
            }
        }
    }

    public function setOne($reference_type, &$value,$type = []){

        $value['medias'] =  $this->getAll($reference_type,$value[$reference_type.'_id'],$type);

    }

    public function setThumbnails($reference_type, &$values){

        $this->setAll($reference_type,$values, $reference_type.'-thumbnail');

    }


    public function setThumbnail($reference_type, &$value){

        $this->setOne($reference_type,$value, $reference_type.'-thumbnail');

    }

    public function setGalleries($reference_type, &$values){

        $this->setAll($reference_type,$values, $reference_type.'-gallery');

    }

    public function setGallery($reference_type, &$value){

        $this->setOne($reference_type,$value, $reference_type.'-gallery');

    }

    public function setWallpapers($reference_type, &$values){

        $this->setAll($reference_type,$values, $reference_type.'-wallpaper');

    }

    public function setWallpaper($reference_type, &$value){

        $this->setOne($reference_type,$value, $reference_type.'-wallpaper');

    }

}
