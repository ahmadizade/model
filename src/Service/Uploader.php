<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

class Uploader extends AbstractController
{

    protected $container;
    private $imageMime;
    private $videoMime;
    private $docMime;
    public  $message;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->imageMime = [
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif'
        ];

        $this->videoMime = [
            'mp4' => 'video/mp4',
            'avi' => 'video/avi'
        ];

        $this->docMime = [
            'pdf' => 'application/pdf'
        ];
    }

    public function BulkUpload(FileBag $files = null, $params = []){
        $result = [];
        foreach ($files as $file){
            $result[] = $this->UploadFile($file, $params);
        }
        return $result;
    }

    public function UploadImage(UploadedFile $file = null, $params = []){
        if($this->getFileType($file) != 'image'){
            $this->message = 'invalid image';
            return null;
        }
        return $this->UploadFile($file, $params);
    }

    public function UploadVideo(UploadedFile $file = null, $params = []){
        if($this->getFileType($file) != 'video'){
            $this->message = 'invalid video';
            return null;
        }
        return $this->UploadFile($file, $params);
    }

    public function UploadFile(UploadedFile $file = null, $params = []){
        if(!$file){
            $this->message = 'no file selected';
            return null;
        }
        $renamed = false;

        // just resize images for know
        if((isset($params['resize']) || isset($params['height']) || isset($params['width']) || isset($params['quality'])) && $this->getFileType($file) == 'image')
            return $this->ResizeImage($file, $params);

        $params = $params + ['type' => 'general'] ;

        if(!$this->isValid($file)){
            $this->message = 'file format is not valid';
            return null;
        }

        $fileInfo = $this->getFileInfo($file);


        if(isset($params['rand_name'])){
            $baseName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $this->random_string(20));
            $baseName = mb_ereg_replace("([\.]{2,})", '', $baseName);
            $renamed = true;
        } elseif(isset($params['name'])){
            $baseName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $params['name']);
            $baseName = mb_ereg_replace("([\.]{2,})", '', $baseName);
            $renamed = true;
        }else
            $baseName = $fileInfo['base_name'];

        $name = $baseName.'.'.$fileInfo['ext'];

        if(isset($params['upload_path'])){
            $upload_path = $params['upload_path'];
        }else{
            if ($params['type'] == 'post_thumbnail'){
                $upload_path = 'media/posts/img/thumbnails';
            }else
                $upload_path = 'media/general/img';
        }

        $fs = new Filesystem();
        if(!$fs->exists($upload_path))
            $fs->mkdir($upload_path);

        $desPathName = $upload_path.DIRECTORY_SEPARATOR.$name ;
        while ($fs->exists($desPathName)){
            $rand = substr(uniqid(rand(), true), 0, 5);
            $baseName = $baseName . $rand;
            $name = $baseName.'.'.$fileInfo['ext'];
            $desPathName = $upload_path.DIRECTORY_SEPARATOR.$name ;
        }

        $file->move($upload_path,$name);

        if($renamed){
            return  ['hash' => sha1_file($desPathName), 'uri' => $desPathName,'name' => $name,'base_name'=>$baseName,'old_name' => $fileInfo['name'], 'old_base_name' => $fileInfo['base_name']] + $fileInfo;
        }

        return  [ 'hash' => sha1_file($desPathName) , 'uri' => $desPathName] + $fileInfo;

    }

    public function ResizeImage(UploadedFile $file = null, $params = []){
        if(!$file)
            return null;

        if(!$this->IsValidImage($file))
            return null;

        $params = $params + ['width' => null, 'height' => null, 'quality' => 100, 'transparent' => false, 'type' => 'general'];

        if ($file->getMimeType() == 'image/jpeg') {
            $image = imagecreatefromjpeg($file->getRealPath());
        } else{ #image/png
            $image = imagecreatefrompng($file->getRealPath());
            $params['transparent'] = true;
        }

        list($baseWidth,$baseHeight) = getimagesize($file->getRealPath());

        $newWidth = $params['width'];
        $newHeight = $params['height'];

        if ($params['width'] === null && $params['height'] === null) {
            $newWidth = (int)$baseWidth;
            $newHeight = (int)$baseHeight;
        } elseif ($params['height'] === null) {
            $newHeight = (int)floor($baseHeight * ($params['width'] / $baseWidth));
        } elseif ($params['width'] === null) {
            $newWidth = (int)floor($baseWidth * ($params['height'] / $baseHeight));
        }

        $imageInfo = $this->getFileInfo($file,$params);

        $virtual_image = imagecreatetruecolor($newWidth, $newHeight);

        if ($params['transparent']) {
            imagealphablending($virtual_image, false);
            imagesavealpha($virtual_image, true);
        }

        imagecopyresampled($virtual_image, $image, 0, 0, 0, 0, $newWidth, $newHeight, $baseWidth, $baseHeight);


        $fs = new Filesystem();
        if(isset($params['upload_path']))
            $upload_path = $params['upload_path'];
        else {
            if ($params['type'] == 'post_thumbnail') {
                $upload_path = 'media/posts/img/thumbnails';
            } else
                $upload_path = 'media/general/img';
        }
        if(!$fs->exists($upload_path))
            $fs->mkdir($upload_path);

        $desPathName = $upload_path.DIRECTORY_SEPARATOR.$imageInfo['name'] ;
        while ($fs->exists($desPathName)){  // check for duplicate name and replace by rand name
            $rand = substr(uniqid(rand(), true), 0, 5);
            $imageInfo['base_name'] = $imageInfo['base_name'] . $rand;
            $imageInfo['name'] = $imageInfo['base_name'].'.'.$imageInfo['ext'];
            $desPathName = $upload_path.DIRECTORY_SEPARATOR.$imageInfo['name'] ;
        }

        if ($imageInfo['ext'] == 'jpg') {
            imagejpeg($virtual_image, $desPathName, $params['quality']);
        }
        else { // png
            $params['quality'] = (100.00 - $params['quality']) * (9.00 / 100.00);
            imagepng($virtual_image, $desPathName, $params['quality']);
        }

        return  ['hash' => sha1_file($desPathName),'uri' => $desPathName, 'name' => $imageInfo['name'],'width' => $newWidth,'height' => $newHeight, 'old_width' => $imageInfo['width'], 'old_height' => $imageInfo['height'],'size' => filesize($desPathName) ,'old_size' => $imageInfo['size']] + $imageInfo;

    }

    public function IsValidImage(UploadedFile $file){
        $mime = $file->getMimeType();

        if(!in_array($mime,['image/jpeg','image/png','image/jpg']))
            return false;

        list($width,$height) = getimagesize($file->getRealPath());
        if(!$width || !$height)
            return false;

        $ext= $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();
        $ex = substr($name,strrpos($name,'.'),strlen($name));
        $ex = str_replace('.','',$ex);
        /*
                if($ext !== $ex)
                    return false;*/

        return true;
    }

    public function getFileInfo(UploadedFile $file,$params = []){

        $fileType = $this->getFileType($file);

        $name = $file->getClientOriginalName();
        list($width,$height) = getimagesize($file->getRealPath());
        $ex = substr($name,strrpos($name,'.'),strlen($name));

        $base_name = substr($name,0,strpos($name,'.'));
        $base_name = str_replace('.','',$base_name);
        $base_name = str_replace(' ','_',$base_name);
        $ex = str_replace('.','',$ex);

        if(isset($params['name'])){
            $base_name = substr($params['name'],0,strpos($params['name'],'.'));
            $base_name = str_replace(' ','',$base_name );
        }
        $name = $base_name.'.'.$ex;

        if($fileType == 'image'){
            return ['ext' => $ex, 'name' => $name, 'base_name' => $base_name, 'mime' => $file->getMimeType(), 'width'=>$width, 'height'=>$height, 'size' => $file->getSize() ? $file->getSize() : 0,'type' => $fileType];
        }

        return ['ext' => $ex, 'name' => $name, 'base_name' => $base_name, 'mime' => $file->getMimeType(), 'size' => $file->getSize() ? $file->getSize() : 0,'type' => $fileType];

    }

    public function isValid(UploadedFile $file){

        if(empty($this->getFileType($file))){
            $this->message = 'invalid file type';
            return false;
        }

        $ext= $file->getClientOriginalExtension();
        $ext = str_replace('.','',$ext);

        $name = $file->getClientOriginalName();
        $ex = substr($name,strrpos($name,'.'),strlen($name));
        $ex = str_replace('.','',$ex);
        if($ext !== $ex) {
            $this->message = 'invalid file type';
            return false;
        }

        return true;
    }

    public function getFileType(UploadedFile $file){
        $mime = $file->getMimeType();

        if(in_array($mime,$this->imageMime)){
            return 'image';
        }elseif (in_array($mime,$this->videoMime)){
            return 'video';
        }elseif (in_array($mime,$this->docMime)){
            return 'doc';
        }

        return '';
    }

    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

}
