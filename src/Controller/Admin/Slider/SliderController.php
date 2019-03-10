<?php

namespace App\Controller\Admin\Slider;

use App\Entity\Media;
use App\Entity\MediaRelation;
use App\Entity\Sliders;
use App\Service\ApiHelper;
use App\Service\DataBase;
use App\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SliderController extends AbstractController
{
    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/sliders", name="admin_all_sliders")
     */
    public function All(DataBase $dataBase){

        $sliders = $dataBase->fetchAll('SELECT * FROM sliders WHERE deleted_at is null');
        return $this->render('admin/sliders/All.html.twig',['sliders' => $sliders]);

    }

    /**
     * @param Request $request
     * @param ApiHelper $apiHelper
     * @param Helper $helper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/slider/add", name="admin_add_slider")
     */
    public function add(Request $request, ApiHelper $apiHelper, Helper $helper)
    {
        if ($request->isMethod('GET')) {
/*            $router = $this->get('router');
            $routes = $router->getRouteCollection();
            $routes = $routes->all();
            foreach ($routes as $key => $route){
                $routes[$key] = $key;
                if(strpos($key ,'admin') !== false)
                    unset($routes[$key]);
                elseif (substr($key,0,1) == '_')
                    unset($routes[$key]);

            }*/
            return $this->render('admin/sliders/Add.html.twig',['positions'=>['main'],'routes'=>$helper->routeAlias()]);
        }

        if (empty($_POST['slug'])) {
            $_POST['slug'] = str_replace(' ', '-', $this->cleanSlug($_POST['title']));
        } else
            $_POST['slug'] = $this->cleanSlug($_POST['slug']);

        if ($this->isDuplicateSlug($_POST['slug']))
            return $apiHelper->CustomResponse('Duplicate slug', 0);


        $em = $this->getDoctrine()->getManager();

        $slider = new Sliders();
        $slider->setSliderTitle($_POST['title']);
        $slider->setSliderSlug($_POST['slug']);
        $slider->setPosition($_POST['position']);
        $slider->setRoute($_POST['route']);
        $slider->setStatus($_POST['status']);
        $slider->setCreatedAt();

        $em->persist($slider);
        $em->flush();


        return $apiHelper->CustomResponse('Slider added successfully', 1, ['slider_id' => $slider->getSliderId()]);

    }

    /**
     * @param $slider_id
     * @param Request $request
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/slider/editstatus/{slider_id}", name="admin_edit_slider_status", defaults={"slider_id"=0})
     */
    public function editStatus($slider_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Sliders::class);
        $slider = $repository->find($slider_id);
        if (!$slider)
            return $apiHelper->CustomResponse('Slider Not Find!', 0, [], 'admin_all_sliders');

        $slider->setStatus($_POST['status']);
        $slider->setUpdatedAt();

        $em = $this->getDoctrine()->getManager();
        $em->persist($slider);
        $em->flush();

        return $apiHelper->CustomResponse('Slider status updated successfully', 1);

    }


    /**
     * @param integer $slider_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/slider/archive/{slider_id}", name="admin_archive_slider", defaults={"slider_id"=0})
     */
    public function archive($slider_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Sliders::class);
        $slider = $repository->find($slider_id);

        if ($slider) {

            $em = $this->getDoctrine()->getManager();

            $slider->setDeletedAt();      // todo remove relation

            $em->persist($slider);
            $em->flush();

            return $apiHelper->CustomResponse('Slider archive successfully');
        }

        return $apiHelper->CustomResponse('No Slider Archived');
    }

    /**
     * @param integer $slider_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/slider/delete/{slider_id}", name="admin_delete_slider", defaults={"slider_id"=0})
     */
    public function delete($slider_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Sliders::class);
        $slider = $repository->find($slider_id);

        if ($slider) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush();

            $repository = $this->getDoctrine()->getRepository(MediaRelation::class);
            $slides = $repository->findBy(['reference_id' => $slider_id, 'reference_type' => 'slider']);
            foreach ($slides as $slide){
                $em->remove($slide);
                $em->flush();
            }

            if(isset($_POST['remove_media'])){
                $repository = $this->getDoctrine()->getRepository(Media::class);
                $slides = $repository->findBy(['reference_id' => $slider_id, 'reference_type' => 'slider']);
                foreach ($slides as $slide) {
                    $em->remove($slide);
                    $em->flush();
                }
            }

            return $apiHelper->CustomResponse('Slider deleted successfully');
        }

        return $apiHelper->CustomResponse('No Slider Deleted');
    }

    public function isDuplicateSlug($slug, $id = ''){
        $repository = $this->getDoctrine()->getRepository(Sliders::class);
        $qb = $repository->createQueryBuilder('u');

        if($id){
            $qb->where('u.slider_slug = :slug AND u.slider_id <> :id')->setParameters(['slug' => $slug, 'id' => $id]);
        }
        else{
            $qb->where('u.slider_slug = :slug')->setParameters(['slug' => $slug]);
        }

        if($qb->getQuery()->getResult())
            return true;

        return false;
    }

    public function cleanSlug($slug)
    {
        $slug = strtolower($slug);
        $slug = str_replace(' ', '_', $slug);
        return urlencode($slug);
    }

}
