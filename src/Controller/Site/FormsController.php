<?php

namespace App\Controller\Site;

use App\Entity\Forms;
use App\Service\ApiHelper;
use App\Service\Email;
use App\Service\Security;
use App\Service\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormsController extends AbstractController
{
    private $validTypes = [];

    public function __construct()
    {
        $this->validTypes = ['contact_us'];
    }


    /**
     * @param Request $request
     * @param Security $security
     * @param ApiHelper $apiHelper
     * @param Email $email
     * @param Validation $validation
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/form/submit", name="submit_form")
     */
    public function Submit(Request $request, Security $security, ApiHelper $apiHelper, Email $email, Validation $validation)
    {

        $user_id = $security->getUserId();
        $msg = '';

        if ($security->IsCaptchaNeeded())
            return $apiHelper->CustomResponse('تعداد دفعات درخواست بیشتر از حد مجاز است.', 99);

        try {

            if (!isset($_POST['type']) || !in_array($_POST['type'], $this->validTypes)) {
                throw new \Exception('Invalid Type');
            }


            if ($_POST['type'] == 'contact_us') {

                $valid = $validation->Validate([
                    ['type' => 'email', 'name' => '*email']
                ]);
                if (!$valid['status'])
                    return $apiHelper->CustomResponse($valid['message']);

                //$email->ContactUs($_POST);
                $msg = 'Thanks for Your Contact';
            }


            // Save Form
            $em = $this->getDoctrine()->getManager();

            $form = new Forms();
            $form->setContent(json_encode($_POST, JSON_UNESCAPED_UNICODE));
            $form->setIp($request->getClientIp());
            $form->setType($_POST['type']);
            $form->setUserRefId($user_id);
            $form->setCreatedAt();

            $em->persist($form);
            $em->flush();
            $security->LogRequest(1);

            return $apiHelper->CustomResponse($msg, 1);

        } catch (\Exception $exception) {
            $security->LogRequest(0);
            return $apiHelper->CustomResponse();
        }

    }

}
