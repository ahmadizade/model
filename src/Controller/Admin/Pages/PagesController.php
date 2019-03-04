<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Pages;
use App\Service\ApiHelper;
use App\Service\DataBase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @param DataBase $dataBase
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/pages", name="admin_all_pages")
     */
    public function all(DataBase $dataBase)
    {
        $pages = $dataBase->fetchAll('SELECT * FROM pages WHERE deleted_at is null');
        return $this->render('admin/pages/All.html.twig', ['pages' => $pages]);
    }

    /**
     * @param Request $request
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/page/add", name="admin_add_page")
     */
    public function add(Request $request, ApiHelper $apiHelper)
    {
        if ($request->isMethod('GET')) {
            return $this->render('admin/pages/Add.html.twig');
        }

        if (empty($_POST['slug'])) {
            $_POST['slug'] = str_replace(' ', '-', $this->cleanSlug($_POST['title']));
        } else
            $_POST['slug'] = $this->cleanSlug($_POST['slug']);

        if ($this->isDuplicateSlug($_POST['slug']))
            return $apiHelper->CustomResponse('Duplicate slug', 0);


        $em = $this->getDoctrine()->getManager();

        $page = new Pages();
        $page->setPageTitle($_POST['title']);
        $page->setPageSlug($_POST['slug']);
        $page->setContent($_POST['content']);
        $page->setStatus($_POST['status']);
        $page->setPageType('general');
        $page->setCreatedAt();

        $em->persist($page);
        $em->flush();

        return $apiHelper->CustomResponse('Page Added Successfully', 1, ['editUrl' => $this->generateUrl('admin_edit_page',['pageId' => $page->getPageId()])]);

    }

    /**
     * @param $pageId
     * @param Request $request
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/page/edit/{pageId}", name="admin_edit_page", defaults={"pageId"=0})
     */
    public function edit($pageId, ApiHelper $apiHelper, Request $request, DataBase $dataBase)
    {

        if ($request->isMethod('GET')) {
            $page = $dataBase->fetchAssoc("SELECT * FROM pages where page_id = :page_id",['page_id' => $pageId]);

            if ($page['page_type'] == 'landing') {
                try {
                    return $this->render('admin/pages/templates/' . ucwords($page['themplate']) . '.html.twig', [
                        'content' => $page->getParamByKey('content')
                    ]);
                } catch (\Exception $exception) {
                    throw $this->createNotFoundException('template not find');
                }
            }
            return $this->render('admin/pages/Add.html.twig', [ 'page' => $page ]);
        }

        $repository = $this->getDoctrine()->getRepository(Pages::class);

        $page = $repository->find($pageId);
        if (!$page)
            return $apiHelper->CustomResponse('Page Not Find!', 0, [], 'admin_all_pages');

        if(isset($_GET['status'])){

            if($_GET['status'] == 'delete')
                return $this->delete($pageId, $apiHelper);

            if($_GET['status'] == 'archive')
                return $this->archive($pageId, $apiHelper);

            $page->setStatus($_GET['status']);
            $page->setUpdatedAt();

        }else{
            if (empty($_POST['slug'])) {
                $_POST['slug'] = str_replace(' ', '-', $this->cleanSlug($_POST['title']));
            } else
                $_POST['slug'] = $this->cleanSlug($_POST['slug']);

            if ($this->isDuplicateSlug($_POST['slug'], $pageId))
                return $apiHelper->CustomResponse('Duplicate slug', 0);

            $page->setPageTitle($_POST['title']);
            $page->setPageSlug($_POST['slug']);
            $page->setContent($_POST['content']);
            $page->setPageType('general');
            $page->setStatus($_POST['status']);
            $page->setUpdatedAt();
        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();

        return $apiHelper->CustomResponse('Page edited successfully', 1);

    }

    /**
     * @param integer $page_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/page/archive/{page_id}", name="admin_archive_page")
     */
    public function archive($page_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Pages::class);
        $page = $repository->find($page_id);

        if ($page) {

            $em = $this->getDoctrine()->getManager();

            $page->setDeletedAt();      // todo remove relation

            $em->persist($page);
            $em->flush();

            return $apiHelper->CustomResponse('Page archive successfully');
        }

        return $apiHelper->CustomResponse('No Page Archived');
    }

    /**
     * @param integer $page_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/page/delete/{page_id}", name="admin_delete_page")
     */
    public function delete($page_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Pages::class);
        $page = $repository->find($page_id);

        if ($page) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();

            return $apiHelper->CustomResponse('Page deleted successfully');
        }

        return $apiHelper->CustomResponse('No Page Deleted');
    }

    /**
     * @param $page_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/page/{page_id}/param", name="page_param_edit")
     */
    public function updateParam($page_id, ApiHelper $apiHelper)
    {
        return;
        $repository = $this->getDoctrine()->getRepository(Pages::class);
        $page = $repository->find($page_id);
        if (!$page)
            exit;

        $params = $page->getPageParams();
        $params['content'] = array_merge($params['content'], $_POST);

        $page->setPageParams($params);

        $em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();

        return $apiHelper->CustomResponse('Page params updated successfully', 1);

    }

    public function isDuplicateSlug($slug, $id = '')
    {
        $repository = $this->getDoctrine()->getRepository(Pages::class);
        $qb = $repository->createQueryBuilder('u');

        if ($id) {
            $qb->where('u.page_slug = :slug AND u.page_id <> :id')->setParameters(['slug' => $slug, 'id' => $id]);
        } else {
            $qb->where('u.page_slug = :slug')->setParameters(['slug' => $slug]);
        }

        if ($qb->getQuery()->getResult())
            return true;

        return false;
    }

    public function cleanSlug($slug)
    {
        $slug = strtolower($slug);
        $slug = str_replace(' ', '_', $slug);
        return urlencode($slug);
    }

    /**
     * @return array
     */
    public function registerRouts(){
        return [
            [
                'key' => 'allPages',
                'label' => 'مشاهده لیست تمام صفحات',
                /*'methods' => ['get'],*/
                'route_name' => 'admin_all_pages',
            ],
            [
                'key' => 'addPage',
                'label' => 'افزودن صفحه جدید',
                'route_name' => 'admin_add_page',
            ],
            [
                'key' => 'showPage',
                'label' => 'مشاهده اطلاعات صفحه',
                'methods' => ['get'],
                'route_name' => 'admin_edit_page',
            ],
            [
                'key' => 'editPage',
                'label' => 'ویرایش اطلاعات صفحه',
                'methods' => ['post'],
                'route_name' => 'admin_edit_page',
            ],
            [
                'key' => 'archivePage',
                'label' => 'آرشیو صفحه',
                'route_name' => 'admin_archive_page',
            ],
            [
                'key' => 'deletePage',
                'label' => 'حذف صفحه',
                'route_name' => 'admin_delete_page',
            ],
        ];
    }
}
