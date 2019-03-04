<?php

namespace App\Controller\Admin\Media;

use App\Entity\Media;
use App\Entity\MediaRelation;
use App\Service\ApiHelper;
use App\Service\DataBase;
use App\Service\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/medias", name="admin_all_medias")
     */
    public function All(DataBase $dataBase){

        $medias = $dataBase->fetchAll('SELECT * FROM media WHERE deleted_at is null');
        return $this->render('admin/media/All.html.twig',['medias' => $medias]);

    }

    /**
     * @param Request $request
     * @param Uploader $uploader
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/media/add", name="admin_add_media")
     */
    public function Add(Request $request,Uploader $uploader, ApiHelper $apiHelper ){

        if ($request->isMethod('GET'))
            return $this->render('admin/media/Add.html.twig');

        $file = $request->files->get('file');
        if($file){
            $uploaded = $uploader->UploadFile($file,['type'=>'general']);
            if(is_null($uploaded))exit;

            $em = $this->getDoctrine()->getManager();

            $media = new Media();
            $media->setFileName($uploaded['name']);
            $media->setUri($uploaded['uri']);
            $media->setMime($uploaded['mime']);
            $media->setCreatedAt();

            $em->persist($media);
            $em->flush();
        }

	    return $apiHelper->CustomResponse('successfully added',1);
    }

    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/medias/{reference_type}/{reference_id}", name="admin_all_to_medias")
     */
    public function AllTo($reference_type, $reference_id,DataBase $dataBase){

        $medias = $dataBase->fetchAll('SELECT * FROM media INNER JOIN media_relation on media_id = media_ref_id and reference_type = :reference_type and reference_id = :reference_id WHERE media.deleted_at is null',
                                                    ['reference_type' => $reference_type, 'reference_id' => $reference_id]);
        return $this->render('admin/media/All.html.twig',['medias' => $medias, 'type'=>'allto','reference_type' => $reference_type,'reference_id' => $reference_id]);

    }

    /**
     * @param string $reference_type
     * @param integer $reference_id
     * @param Request $request
     * @param Uploader $uploader
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/media/add/{reference_type}/{reference_id}", name="admin_add_to_media")
     */
    public function AddTo($reference_type, $reference_id, Request $request,Uploader $uploader, ApiHelper $apiHelper){

        if ($request->isMethod('GET')){
            if($reference_type == 'tour')
                return $this->render('admin/media/Add.html.twig',['types' => ['tour-thumbnail','tour-wallpaper','tour-gallery']]);
            if($reference_type == 'category')
                return $this->render('admin/media/Add.html.twig',['types' => ['category-thumbnail','category-wallpaper']]);
            if($reference_type == 'slider')
                return $this->render('admin/media/Add.html.twig',['types' => ['slider']]);
	        if($reference_type == 'page')
		        return $this->render('admin/media/Add.html.twig',['types' => ['page-thumbnail']]);
        }

        $file = $request->files->get('file');
        if($file){
            $uploaded = $uploader->UploadFile($file,['type'=>'general']);
            if(is_null($uploaded))exit;

            $em = $this->getDoctrine()->getManager();

            $media = new Media();
            $media->setFileName($uploaded['name']);
            $media->setUri($uploaded['uri']);
            $media->setMime($uploaded['mime']);
            $media->setCreatedAt();

            $em->persist($media);
            $em->flush();


            $media_rel = new MediaRelation();
            $media_rel->setCreatedAt();
            $media_rel->setMediaRefId($media->getMediaId());
            $media_rel->setReferenceId($reference_id);
            $media_rel->setReferenceType($reference_type);
            $media_rel->setType($_POST['type']);

            $em->persist($media_rel);
            $em->flush();


        }

	    return $apiHelper->CustomResponse('successfully added',1);
    }


    /**
     * @param string $reference_type
     * @param integer $reference_id
     * @param Request $request
     * @param Uploader $uploader
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/media/removeto/{media_relation_id}", name="admin_remove_to_media")
     */
    public function RemoveTo($media_relation_id, Request $request,Uploader $uploader, ApiHelper $apiHelper){

        $repository = $this->getDoctrine()->getRepository(MediaRelation::class);
        $media_rel = $repository->find($media_relation_id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($media_rel);
        $em->flush();

        return $apiHelper->CustomResponse('successfully removed',1);
    }


    /**
     * @param integer $media_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/media/add", name="admin_add_media")
     */
/*    public function archive($media_id, ApiHelper $apiHelper){

        $repository = $this->getDoctrine()->getRepository(Media::class);
        $media = $repository->find($media_id);
        if($media){
            $media->setDeletedAt();
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->f1lush();

            return $apiHelper->CustomResponse('media archived successfully',1);
        }

        return $apiHelper->CustomResponse('media not found!',1);
    }*/

    /**
     * @param integer $media_id
     * @param ApiHelper $apiHelper
     * @param Uploader $uploader
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/media/add", name="admin_add_media")
     */
    /*public function delete($media_id, ApiHelper $apiHelper,Uploader $uploader){

        $repository = $this->getDoctrine()->getRepository(Media::class);
        $media = $repository->find($media_id);
        if($media){
            $media->setDeletedAt();
            $em = $this->getDoctrine()->getManager();
            $em->remove($media);
            $em->flush();

            $uploader->RemoveFile($media->getUri());

            return $apiHelper->CustomResponse('media deleted successfully',1);
        }

        return $apiHelper->CustomResponse('media not found!',1);
    }*/

    /**
     * @return array
     */
    public function registerRouts(){
        return [
            [
                'key' => 'allMedia',
                'label' => 'مشاهده لیست تمام رسانه ها',
                /*'methods' => ['get'],*/
                'route_name' => 'admin_all_medias',
            ],
            [
                'key' => 'addMedia',
                'label' => 'افزودن رسانه جدید',
                'route_name' => 'admin_add_media',
            ],
            [
                'key' => 'allToMedia',
                'label' => 'مشاهده لیست رسانه های یک موجودیت',
                'route_name' => 'admin_all_to_medias',
            ],
            [
                'key' => 'addToMedia',
                'label' => 'افزودن رسانه به موجودیت',
                'route_name' => 'admin_add_to_medias',
            ],
            [
                'key' => 'removeToMedia',
                'label' => 'حذف رابطه رسانه با موجودیت',
                'route_name' => 'admin_remove_to_media',
            ],
        ];
    }
}
