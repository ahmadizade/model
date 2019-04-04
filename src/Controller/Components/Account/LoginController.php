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

class LoginController extends AbstractController
{

    /**
     * @param Validation $validation
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/account/login", name="login")
     */
    public function index(Request $request, Validation $validation, ApiHelper $apiHelper, Security $security)
    {
        if($request->isMethod('GET')){
            return $this->render('components/account/Login.html.twig');
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Users::class);

        $validate = $validation->Validate([
            ['type' => 'Reg', 'name' => 'username', 'pattern' => ['/^[0-9]+$/'], 'msg' => 'لطفا شماره همراه را به صورت صحیح وارد کنید.'],
            ['type' => 'All', 'name' => '*password'],
            ['type' => 'MaxLen', 'name' => '*password', 'len' => 25],
            ['type' => 'MinLen', 'name' => '*password', 'len' => 6],
        ]);

        if (!$validate)
            return $apiHelper->CustomResponse($validation->errorMessage);

        if ($user = $repository->authenticatePhone($_POST['username'],sha1($_POST['password'].'ASAR@$#e211fQF3r1~sa6'))){
            $session = new Session();
            $session->set('user_id',$user->getUserId());

            return $apiHelper->CustomResponse('You Are login Now',1);
        }

        return $apiHelper->CustomResponse('email/phone or password is incorrect',0);

    }
}
