<?php

namespace App\Controller\Components\Account;

use App\Entity\Company;
use App\Entity\Users;
use App\Service\ApiHelper;
use App\Service\DataBase;
use App\Service\Security;
use App\Service\Validation as Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FriendshipController extends AbstractController
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
     * @Route("/account/login", name="login")
     */
    public function allMyFriends(Request $request, Validation $validation, ApiHelper $apiHelper, Security $security)
    {
        $userId = $this->security->getUserId();

        $query = "SELECT * FROM friendships INNER JOIN users as u1 on user_ref_id_1 = u1.user_id WHERE user_ref_id_2 = :user_id2
                    UNION
                  SELECT * FROM friendships INNER JOIN users as u2 on user_ref_id_2 = u2.user_id WHERE user_ref_id_1 = :user_id1";
        $friends = $this->dataBase->fetchAll($query, ['user_id2' => $userId, 'user_id1' => $userId]);

    }


}
