<?php

namespace App\Controller\Admin\Categories;

use App\Entity\Categories;
use App\Entity\Tours;
use App\Service\ApiHelper;
use App\Service\DataBase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/categories", name="admin_all_categories")
     */
    public function all(Request $request, DataBase $dataBase)
    {
        $categories = $dataBase->fetchAll('SELECT * FROM categories WHERE deleted_at is null');
        if ($request->isMethod('GET'))
            return $this->render('admin/categories/All.html.twig', ['categories' => $categories]);

    }

    /**
     * @param ApiHelper $apiHelper
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/category/add", name="admin_add_category")
     */
    public function add(ApiHelper $apiHelper, Request $request)
    {
        if ($request->isMethod('GET'))
            return $this->render('admin/categories/Add.html.twig');

        if (empty($_POST['slug'])) {
            $_POST['slug'] = str_replace(' ', '-', $this->cleanSlug($_POST['title']));
        } else
            $_POST['slug'] = $this->cleanSlug($_POST['slug']);

        if ($this->isDuplicateSlug($_POST['slug']))
            return $apiHelper->CustomResponse('Duplicate slug', 0);


        $em = $this->getDoctrine()->getManager();

        $category = new Categories();
        $category->setCategorySlug($_POST['slug']);
        $category->setCategoryExcerpt($_POST['excerpt']);
        $category->setCategoryTitle($_POST['title']);
        $category->setCategoryType($_POST['type']);
        $category->setStatus($_POST['status']);
        $category->setCreatedAt();

        $em->persist($category);
        $em->flush();

        return $apiHelper->CustomResponse('Category added successfully', 1, ['category_id' => $category->getCategoryId()]);

    }

    /**
     * @param integer $category_id
     * @param ApiHelper $apiHelper
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/category/edit/{category_id}", name="admin_edit_category", defaults={"category_id"=0})
     */
    public function edit($category_id, ApiHelper $apiHelper, Request $request, DataBase $dataBase)
    {

        if ($request->isMethod('GET')){
            $category = $dataBase->fetchAssoc("SELECT * FROM categories WHERE category_id = :id",['id'=>$category_id]);
            return $this->render('admin/categories/Add.html.twig', ['category' => $category]);
        }
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $category = $repository->find($category_id);

        $category->setCategorySlug($_POST['slug']); //TODO check duplicate slug
        $category->setCategoryTitle($_POST['title']);
        $category->setCategoryType($_POST['type']);
        $category->setCategoryExcerpt($_POST['excerpt']);
        $category->setStatus($_POST['status']);
        $category->setUpdatedAt();

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        return $apiHelper->CustomResponse('Category edited successfully', 1, ['category_id' => $category->getCategoryId()]);

    }

    /**
     * @param integer $category_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/category/editstatus/{category_id}", name="admin_edit_category_status", defaults={"category_id"=0})
     */
    public function editStatus($category_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $category = $repository->find($category_id);
        if (!$category)
            return $apiHelper->CustomResponse('Category not found!', 0, [], 'admin_all_categories');

        $category->setStatus($_POST['status']);
        $category->setUpdatedAt();

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $apiHelper->CustomResponse('Category status updated successfully', 1);

    }

    /**
     * @param integer $category_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/category/archive/{category_id}", name="admin_archive_category", defaults={"category_id"=0})
     */
    public function archive($category_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $category = $repository->find($category_id);

        if ($category) {

            $em = $this->getDoctrine()->getManager();

            $category->setDeletedAt();      // todo remove relation

            $em->persist($category);
            $em->flush();

            return $apiHelper->CustomResponse('Category archive successfully');
        }

        return $apiHelper->CustomResponse('No Archive Archived');
    }

    /**
     * @param integer $category_id
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/category/delete/{category_id}", name="admin_delete_category", defaults={"category_id"=0})
     */
    public function delete($category_id, ApiHelper $apiHelper)
    {

        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $category = $repository->find($category_id);

        if ($category) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            return $apiHelper->CustomResponse('Category deleted successfully');
        }

        return $apiHelper->CustomResponse('No Category Deleted');
    }

    public function isDuplicateSlug($slug)
    {
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        if ($repository->findBy(['category_slug' => $slug]))
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
