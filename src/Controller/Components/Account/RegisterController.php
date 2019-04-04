<?php

namespace App\Controller\Components\Account;

use App\Entity\Users;
use App\Service\ApiHelper;
use App\Service\Security;
use App\Service\Validation as Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/account/register", name="register")
     */
    public function index(Request $request, Validation $validation, ApiHelper $apiHelper, Security $security)
    {
        $user_id = $security->getUserId();

        if($request->isMethod('GET')){
            if($user_id)
                return $this->redirectToRoute('panel');

            return $this->render('components/account/Register.html.twig');
        }
        if($user_id){
            return $apiHelper->CustomResponse('Register completed successfully',1,['url' => $this->generateUrl('panel')]);
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Users::class);

        $validate = $validation->Validate([
            ['type' => 'Int', 'name' => '*phone', 'msg' => 'لطفا شماره تلفن همراه خود را به صورت صحیح وارد کنید.'],
/*            ['type' => 'All', 'name' => '*name', 'msg' => 'Please fill your name correctly!'],*/
            ['type' => 'All', 'name' => '*password', 'msg' => 'Please fill your password correctly!'],
            ['type' => 'All', 'name' => '*password-conf'],
            ['type' => 'MaxLen', 'name' => '*password', 'len' => 25],
            ['type' => 'MinLen', 'name' => '*password', 'len' => 8],
        ]);
        $_POST['name'] = 's';
        if (!$validate)
            return $apiHelper->CustomResponse($validation->errorMessage);

        if($repository->isDuplicate($_POST['phone']))
            return $apiHelper->CustomResponse('This Phone number registered before !', 0);
        try{
            $user = new Users();
            $user->setName($_POST['name']);
            $user->setPassword(sha1($_POST['password'].'ASAR@$#e211fQF3r1~sa6'));
            $user->setPhone($_POST['phone']);
            $user->setStatus(1);
            $user->setCreatedAt();
            $user->setUserParams(['register_form' => $_POST]);

            $em->persist($user);
            $em->flush();
        }catch (\Exception $exception){
            return $apiHelper->CustomResponse();
        }

        $session = new Session();
        $session->set('user_id', $user->getUserId());
        $session->set('company_id', 0);
        $session->set('is_owner', 0);

        return $apiHelper->CustomResponse('Register completed successfully',1,['url' => $this->generateUrl('panel')]);
    }

}
