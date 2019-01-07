<?php

namespace App\Service;

use App\Entity\Projects;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class Security extends Controller
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUser()
    {
        $session = new Session();
        $user_id = $session->get('user_id');

        if($user_id){
            $repository = $this->em->getRepository(Users::class);

            $user = $repository->find($user_id);
            if($user)
                return $user ;
        }

        return null;
    }

    public function getUserId(){
        $session = new Session();
        return $session->get('user_id');
    }

    public function getCompanyId(){
        $session = new Session();
        return $session->get('company_id');
    }

    public function isOwner(){
        $session = new Session();
        if($session->get('is_owner'))
            return true;
        return false;
    }

    public function isGranted($attributes, $subject = null): bool
    {

    }
}
