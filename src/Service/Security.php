<?php

namespace App\Service;

use App\Entity\LogRequests;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class Security extends AbstractController
{

    private $em;
    private $requestStack;
    private $log;
    private $dataBase;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $requestStack, Log $log, DataBase $dataBase)
    {
        $this->em = $entityManager;
        $this->dataBase = $dataBase;
        $this->requestStack = $requestStack;
        $this->log = $log;
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
        if(!$session->get('user_id'))
            return null;
        return $session->get('user_id');
    }


    public function LogRequest($status = 1, $route = null, $params = [])
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$route) {
            $route = $request->get('_route');
        }

        try{

            $logRequest = new LogRequests();
            $logRequest->setRoute($route);
            if($params)
                $logRequest->setParams(json_encode($params,JSON_UNESCAPED_UNICODE));
            $logRequest->setHeader(json_encode($request->headers->all(),JSON_UNESCAPED_UNICODE));
            $logRequest->setIp($request->getClientIp());
            $logRequest->setStatus($status);
            $logRequest->setCreatedAt();
            $this->em->persist($logRequest);
            $this->em->flush();
            return true;

        }catch (\Exception $exception){
            $this->log->Log($exception);
            return false;
        }

    }

    public function IsCaptchaNeeded($period = 3600, $repeat_count = 3, $status = 2, $route = '')
    {
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request->getClientIp();

        $last_time = time() - $period;
        $last_time = Date('Y-m-d H:i:s', $last_time);

        if (empty($route))
            $route = $request->get('_route');

        if ($status == 0)
            $status = "AND status = '0'";
        elseif ($status == 1)
            $status = "AND status = '1'";
        else
            $status = '';

        $query = "SELECT count(*) as `count` FROM log_requests WHERE route = '$route' AND ip = '$ip' AND created_at > '$last_time' {$status} ";
        $total = $this->dataBase->fetchAssoc($query);
        $total = $total['count'];

        if ($total >= $repeat_count)
            return true;

        return false;
    }

    public function IsCaptchaValid($captcha = '')
    {
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request->getClientIp();

        if (empty($captcha)) {
            if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response']))
                return false;
            $captcha = $_POST['g-recaptcha-response'];
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['secret' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'response' => $captcha, 'remoteip' => $ip]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, true);
        if (isset($result['success'])) {
            if ($result['success'] == 'true')
                return true;
        }
        return false;

    }
}
