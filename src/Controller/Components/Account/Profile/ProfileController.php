<?php

namespace App\Controller\Components\Account\Profile;

use App\Entity\Users;
use App\Service\ApiHelper;
use App\Service\DataBase;
use App\Service\Security;
use App\Service\UserHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
class ProfileController extends AbstractController
{

    public $dataBase;
    public $security;

    public function __construct(DataBase $dataBase, Security $security)
    {
        $this->dataBase = $dataBase;
        $this->security = $security;
    }

    /**
     * @param Validation $validation
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/user/{userId}", name="show_profile")
     */
    public function show($userId,DataBase $dataBase, Request $request, Validation $validation, ApiHelper $apiHelper, Security $security,UserHelper $userHelper)
    {

        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $user = $dataBase->fetchAssoc($query, ['user_id' => $userId]);
        if(!$user)exit;

        if(($user['privacy'] == 'public') || ($user['privacy'] == 'private' && $userHelper->isMyFriend($user['user_id']))){
            $userHelper->getUserMeta($user['user_id']);
        }

    }

    /**
     * @param Validation $validation
     * @param ApiHelper $apiHelper
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/account/profile", name="edit_profile")
     */
    public function editProfile(Request $request,UserHelper $userHelper){
        if($request->isMethod('GET')){
            return $this->render('site/profile/Edit.html.twig');
        }

    /*    if($_POST['modelling_type'] == 'fashion'){
            if($_POST['gender'] == 'man'){
                // height , bust , weight, waist, hips, shoe, eys_color, hair_color, skin_color
                // height , weight, waist, shoe, eys_color, hair_color
            }
        }elseif($_POST['modelling_type'] == 'commercial'){

            // voise, height , bust , wight, waist, hips, shoe, eys_color, hair_color, skin_color
            // height , wight, waist, shoe, eys_color, hair_color

        }elseif($_POST['modelling_type'] == 'fitness'){

            // height , bust , wight, waist, hips, shoe, eys_color, hair_color, skin_color
            // height , wight, waist, shoe, eys_color, hair_color


        }elseif($_POST['modelling_type'] == 'part'){

            //woman :Neck, wrist, skin_color, shoe,
            //man :Neck, wrist, skin_color, shoe,

        }elseif($_POST['modelling_type'] == 'hair'){

            // skin_color,hair_color,eye_color,hair_type =>

        }elseif($_POST['modelling_type'] == 'plussize'){

            // height , bust , wight, waist, hips, shoe, eys_color, hair_color, skin_color
            // height , wight, waist, shoe, eys_color, hair_color

        }*/

    //dump($request->files->get('ss'));exit;



    }

    public function fashionValidation(){

        $validator = Validation::createValidator();

        $validator->validate($_POST['height'],new Assert\Range([
            'min' => 150,
            'max' => 230,
            'invalidMessage' => 'مقدار قد نا معتبر است.',
            'minMessage' => 'حداقل قد 150 و حداکثر 230 سانتی متر می باشد',
            'maxMessage' => 'حداقل قد 150 و حداکثر 230 سانتی متر می باشد'
        ]));

        $validator->validate($_POST['weight'],new Assert\Range([
            'min' => 40,
            'max' => 150,
            'invalidMessage' => 'مقدار وزن نا معتبر است.',
            'minMessage' => 'حداقل قد 40 و حداکثر 150 کیلوگرم می باشد',
            'maxMessage' => 'حداقل قد 40 و حداکثر 150 کیلوگرم می باشد',
        ]));

        $validator->validate($_POST['bust'],new Assert\Range([
            'min' => 40,
            'max' => 150,
            'invalidMessage' => 'مقدار اندازه دور سینه نا معتبر است.',
            'minMessage' => 'حداقل اندازه دور سینه 40 و حداکثر 150 سانتی متر می باشد.',
            'maxMessage' => 'حداقل اندازه دور سینه 40 و حداکثر 150 سانتی متر می باشد.',
        ]));

        $validator->validate($_POST['hips'],new Assert\Range([
            'min' => 40,
            'max' => 150,
            'invalidMessage' => 'مقدار اندازه دور ران نا معتبر است.',
            'minMessage' => 'حداقل اندازه دور ران 40 و حداکثر 150 سانتی متر می باشد.',
            'maxMessage' => 'حداقل اندازه دور ران 40 و حداکثر 150 سانتی متر می باشد.',
        ]));

        $validator->validate($_POST['waist'],new Assert\Range([
            'min' => 40,
            'max' => 150,
            'invalidMessage' => 'مقدار اندازه دور کمر نا معتبر است.',
            'minMessage' => 'حداقل اندازه دور کمر 40 و حداکثر 150 سانتی متر می باشد.',
            'maxMessage' => 'حداقل اندازه دور کمر 40 و حداکثر 150 سانتی متر می باشد.',
        ]));

        $validator->validate($_POST['shoe'],new Assert\Range([
            'min' => 40,
            'max' => 150,
            'invalidMessage' => 'مقدار سایز کفش نا معتبر است.',
            'minMessage' => 'حداقل سایز کفش 40 و حداکثر 150 می باشد.',
            'maxMessage' => 'حداقل سایز کفش 40 و حداکثر 150 می باشد.',
        ]));

        $validator->validate($_POST['neck'],new Assert\Range([
            'min' => 40,
            'max' => 150,
            'invalidMessage' => 'مقدار دور گردن نا معتبر است.',
            'minMessage' => 'حداقل اندازه دور گردن 40 و حداکثر 150 سانتی متر می باشد.',
            'maxMessage' => 'حداقل اندازه دور گردن 40 و حداکثر 150 سانتی متر می باشد.',
        ]));

        $validator->validate($_POST['eys_color'],new Assert\Choice([
            'choices' => [''],
            'message' => 'لطفا رنگ چشم را از لیست انتخاب کنید.',
        ]));

        $validator->validate($_POST['hair_color'],new Assert\Choice([
            'choices' => [''],
            'message' => 'لطفا رنگ مو را از لیست انتخاب کنید.',
        ]));

        $validator->validate($_POST['skin_color'],new Assert\Choice([
            'choices' => [''],
            'message' => 'لطفا رنگ پوست را از لیست انتخاب کنید.',
        ]));


        $validator->validate('voice', new Assert\File([
            'maxSize' => '3m',
            'mimeTypes' => [
                'audio/mp3',
                'audio/vnd.wav',
                'audio/ogg',
            ],
            'mimeTypesMessage' => 'پسوند های مجاز برای فایل صوتی mp3 , wav , ogg می باشد.',
            'maxSizeMessage' => 'حداکثر حجم فایل صوتی {{ limit }} مگابایت می باشد.'
        ]));


        $validator->validate('voice', new Assert\Image([
            'maxSize' => '3m',
            'mimeTypes' => [
                'audio/mp3',
                'audio/vnd.wav',
                'audio/ogg',
            ],
            'mimeTypesMessage' => 'پسوند های مجاز برای فایل صوتی mp3 , wav , ogg می باشد.',
            'maxSizeMessage' => 'حداکثر حجم فایل صوتی {{ limit }} مگابایت می باشد.'
        ]));



    }


}
