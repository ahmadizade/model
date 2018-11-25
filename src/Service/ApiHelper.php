<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiHelper extends Controller
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function CustomResponse($message = 'عملیات با خطا روبرو شد.', $status = '0', $params = [], $route = '', $route_params = [])
    {

        if ($this->IsMobile() || $this->IsJson()) {
            return new JsonResponse($params + [
                    'status' => strval($status),
                    'message' => $message,
                ], 200, ['Content-Type' => 'text/json', 'Accept' => ' application/json;charset=UTF-8']);
        }

        if ($status == 0)
            $this->container->get('session')->getFlashBag()->add('error', $message);
        elseif ($status == 1)
            $this->container->get('session')->getFlashBag()->add('success', $message);

        if (empty($route))
            return $this->redirect($this->container->get('router')->generate('site_homepage'));

        return $this->redirect($this->container->get('router')->generate($route, $route_params));

    }

    public function JsonResponse($message = 'عملیات با خطا روبرو شد.', $status = '0', $params = [])
    {

        return new JsonResponse($params + [
                'status' => strval($status),
                'message' => $message,
            ], 200, ['Content-Type' => 'text/json', 'Accept' => ' application/json;charset=UTF-8']);

    }

    public function GetUser()
    {
        global $user_id;

        $token = null;
        if (isset($_POST['token']) && !empty($_POST['token']))
            $token = $_POST['token'];
        elseif (isset($_GET['token']) && !empty($_GET['token']))
            $token = $_GET['token'];

        if ($token) {
            $query = "SELECT * FROM users WHERE token = :token";
            $user = $this->container->get('db')->fetchAssoc($query, ['token' => $token]);
            if ($user)
                return $user;
        }

        if ($user_id) {
            $query = "SELECT * FROM users WHERE user_id = :user_id";
            $user = $this->container->get('db')->fetchAssoc($query, ['user_id' => $user_id]);
            if ($user)
                return $user;
        }

        return null;
    }

    public function GetUserId()
    {
        if (isset($_POST['token']) && !empty($_POST['token']))
            $token = $_POST['token'];
        elseif (isset($_GET['token']) && !empty($_GET['token']))
            $token = $_GET['token'];
        else
            return null;

        $query = "SELECT user_id FROM users WHERE token = :token";
        $user = $this->container->get('db')->fetchAssoc($query, ['token' => $token]);
        if (!$user)
            return null;
        return $user['user_id'];
    }

    public function IsJson()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if ((isset($_GET['type']) && $_GET['type'] == 'json') || $request->isXmlHttpRequest())
            return true;

        return false;

    }

    public function IsMobile()
    {

        if ((isset($_GET['type']) && $_GET['type'] == 'json') || (isset($_GET['platform']) && $_GET['platform'] == 'mobile'))
            return true;

        return false;

    }

}
