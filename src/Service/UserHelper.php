<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserHelper extends Controller
{

    protected $security;
    protected $dataBase;

    public function __construct(Security $security, DataBase $dataBase)
    {
        $this->security = $security;
        $this->dataBase = $dataBase;
    }

    public function isMyFriend($userId){
        $myUserId = $this->security->getUserId();

        $query = "SELECT * FROM friendships WHERE (user_ref_id_1 = :user_id1 or user_ref_id_2 = :user_id2) AND (user_ref_id_1 = :user_id3 or user_ref_id_2 = :user_id4)";
        if($this->dataBase->fetchAssoc($query,['user_id1' => $userId, 'user_id2' => $userId, 'user_id3' => $myUserId, 'user_id4' => $myUserId]))
            return true;
        return false;
    }

    public function getUserMeta($userId, $metaKey = null, $status = null)
    {
        $return_meta = [];
        if (is_null($metaKey)) { // get all meta

            $query = "SELECT meta_key FROM users_metas where user_ref_id = :user_id";
            $metas = $this->dataBase->fetchAll($query, ['user_id' => $userId]);

        } elseif (is_array($metaKey)) {

            $metaKey = implode(',', $metaKey);
            $query = "SELECT meta_key FROM users_metas where user_ref_id = :user_id AND meta_key IN ($metaKey)"; // TODO CHECK FOR INJECTION
            $metas = $this->dataBase->fetchAll($query, ['user_id' => $userId]);

        } else {

            $query = "SELECT meta_key FROM users_metas where user_ref_id = :user_id AND meta_key = :meta_key";
            $metas = $this->dataBase->fetchAll($query, ['user_id' => $userId, 'meta_key' => $metaKey]);

        }

        foreach ($metas as $meta) {
            //TODO DEVELOP FOR MULTI META
            $return_meta[$meta['meta_key']] = ['value' => $meta['meta_value'], 'status' => $meta['status']];
        }

        return $return_meta;
    }

    public function setUserMeta($userId, $metaKey, $metaValue, $status = null, $single = true)
    {
        try{
            /*    if(is_array($metaKey) && is_array($metaValue) && is_array($status) && count($metaKey) == count($metaValue) && count($metaKey) == count($status)){
                    foreach ($metaValue as $key => $value){
                        $query = "INSERT INTO users_metas (user_ref_id, meta_key, meta_value, status) VALUES (:user_id,:meta_key,:meta_value,:status)";
                        $result = $this->dataBase->Insert($query, ['user_id' => $userId, 'meta_key' => $metaKey, 'meta_value' => $metaValue, 'status' => $status]);
                    }
                }*/

            if($single){
                $query = "Delete from users_metas WHERE user_ref_id = :user_id AND meta_key = :meta_key";
                $this->dataBase->Delete($query, ['user_id' => $userId, 'meta_key' => $metaKey]);
            }

            $query = "INSERT INTO users_metas (user_ref_id, meta_key, meta_value, status) VALUES (:user_id, :meta_key, :meta_value, :status)";
            $result = $this->dataBase->Insert($query, ['user_id' => $userId, 'meta_key' => $metaKey, 'meta_value' => $metaValue, 'status' => $status]);

        }catch (\Exception $exception){
            return false;
        }

        return $result;
    }
}
