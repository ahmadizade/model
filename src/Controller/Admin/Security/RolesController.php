<?php

namespace App\Controller\Admin\Security;

use App\Entity\RoleAssignment;
use App\Entity\Roles;
use App\Service\ApiHelper;
use App\Service\DataBase;
use App\Service\Security;
use App\Service\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RolesController extends AbstractController
{
    /**
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/roles", name="admin_all_roles")
     */
    public function AllAction(Security $security)
    {
        $roles = $security->getRolesList();
        return $this->render('admin/Security/Roles/All.html.twig', compact('roles'));
    }


    /**
     * @param Request $request
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/role/add", name="admin_add_role")
     */
    public function AddAction(Request $request, Validation $validation, ApiHelper $apiHelper)
    {

        if ($request->isMethod('GET')) {
            return $this->render('admin/Security/Roles/Add.html.twig');
        }
        /*$validation->Validate(
            ['']
        );*/ //TODO CHECK FOR exist name

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Roles::class);

        if ($repository->isDuplicate($_POST['role_name']))
            return $apiHelper->CustomResponse('عنوان نقش تکراری می باشد.', 0, [], 'admin_add_role');

        try {
            $role = new Roles();
            $role->setRoleName($_POST['role_name']);
            $role->setRoleType('core');

            $em->persist($role);
            $em->flush();
        } catch (\Exception $exception) {
            return $apiHelper->CustomResponse('عملیات با خطا مواجه شد.', 0, [], 'admin_add_role');
        }

        return $apiHelper->CustomResponse('عملیات با موفقیت انجام شد.', 1, ['editUrl' => $this->generateUrl('admin_edit_role',['roleId' => $role->getRoleId()])]);

    }

    /**
     * @param Int $roleId
     * @param Security $security
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/role/edit/{roleId}", name="admin_edit_role")
     */
    public function EditAction($roleId, Security $security, ApiHelper $apiHelper, Request $request, DataBase $dataBase)
    {

        if (isset($security->restrictedRoles[$roleId]))
            return $apiHelper->CustomResponse('این نقش قابل ویرایش نیست.', 0, [], 'admin_all_roles');

        if ($request->isMethod('GET')) {
            $role = $dataBase->fetchAssoc("SELECT * FROM roles WHERE role_id = :role_id", ['role_id' => $roleId]);
            return $this->render('admin/Security/Roles/Add.html.twig', ['role' => $role]);
        }

        /*$validation->Validate(
            ['']
        );*/ //TODO CHECK FOR exist name

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Roles::class);

        try {
            $role = $repository->find($roleId);
            $role->setRoleName($_POST['role_name']);

            $em->persist($role);
            $em->flush();
        } catch (\Exception $exception) {
            return $apiHelper->CustomResponse('عملیات با خطا مواجه شد.', 0, [], 'admin_edit_role', ['roleId' => $roleId]);
        }

        return $apiHelper->CustomResponse('عملیات با موفقیت انجام شد.', 1, [], 'admin_all_roles');


    }

    /**
     * get list of users in role
     * @param Int $roleId
     * @param Request $request
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/role/{roleId}/users", name="admin_users_of_role")
     */
    public function usersOfRole($roleId, Request $request, DataBase $dataBase, Security $security)
    {
        $query = "SELECT * FROM role_assignment INNER join users on user_id = user_ref_id WHERE role_ref_id = :role_id";
        $users = $dataBase->fetchAll($query,['role_id' => $roleId]);
        return $this->render('admin/Security/Roles/usersOfRole.html.twig',['users' => $users]);

    }

    /**
     * @param Security $security
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/security/role/assignment/{user_id}", name="admin_assign_role")
     */
    public function roleAssignment($userId, Request $request, DataBase $dataBase, Security $security)
    {
        $roles = $security->getRolesList();

        if ($request->isMethod('GET')) {
            $user = $dataBase->fetchAssoc("SELECT * FROM `users` LEFT JOIN `role_assignment` ON `users`.`user_id` = `role_assignment`.`user_ref_id` WHERE `users`.`user_id` = :user_id", ['user_id' => $userId]);
            return $this->render('admin/Security/Roles/Assignment.html.twig', ['user' => $user, 'roles' => $roles]);
        }

        /*$validation->Validate(
            ['']
        );*/ //TODO CHECK FOR exist name

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(RoleAssignment::class);

        try {
            $role = $repository->find($roleId);
            $role->setRoleName($_POST['role_name']);

            $em->persist($role);
            $em->flush();
        } catch (\Exception $exception) {
            return $apiHelper->CustomResponse('عملیات با خطا مواجه شد.', 0, [], 'admin_edit_role', ['roleId' => $roleId]);
        }

        return $apiHelper->CustomResponse('عملیات با موفقیت انجام شد.', 1, [], 'admin_all_roles');

    }

    public function registerRouts(){
        return [
            [
                'key' => 'allRoles',
                'label' => 'مشاهده لیست نقش های کاربری',
                'route_name' => 'admin_all_roles',
                'methods' => ['all']
            ],
            [
                'key' => 'addRole',
                'label' => 'افزودن نقش کاربری',
                'route_name' => 'admin_add_role',
                'methods' => ['all']
            ],
            [
                'key' => 'editRole',
                'label' => 'ویرایش نقش کاربری',
                'route_name' => 'admin_edit_role',
                'methods' => ['all']
            ],
            [
                'key' => 'usersOfRole',
                'label' => 'مشاهده کاربران یک نقش',
                'route_name' => 'admin_users_of_role',
                'methods' => ['all']
            ],
        ];
    }
}
