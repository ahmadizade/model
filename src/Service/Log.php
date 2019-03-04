<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Log extends Controller
{

    private $em;
    protected $container;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function Log($e='', $params = '')
    {

        global $user_id;
        if(!$user_id)
            $user_id = 0;

        $request = $this->get('request_stack')->getCurrentRequest();

        $ip = $route = $post_request = $get_request = $header = $message = $line_number = $controller = NULL;

        try {

            if ($request instanceOf Request) {
                $controller = $request->attributes->get('_controller');
                $ip = $request->getClientIp();
                $route = $request->get('_route');
                $post_request = $request->request->all();
                $get_request = (array)$request->attributes->get('_route_params') + $request->query->all();
                $header = $request->headers->all();
                ///
                $post_request = json_encode($post_request, JSON_UNESCAPED_UNICODE);
                $get_request = json_encode($get_request, JSON_UNESCAPED_UNICODE);
                $header = json_encode($header, JSON_UNESCAPED_UNICODE);
            }

            if ($e instanceOf \Exception) {
                $message = $e->getMessage();
                $message = str_replace("'",'"',$message);
                $line_number = $e->getLine();
            }

            if (is_object($params)) {
                $params = (array)$params;
                $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            } else if (is_array($params)) {
                $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            }
            $params = strval($params);

            $query = "INSERT INTO logs (`type`, `post_request`, `get_request`, `header`, `route`, `message`, `params`, `today`, `ip`, `line_number`, `controller`, `user_ref_id`)
                      VALUES           (:type,  :post_request,  :get_request,  :header,  :route,  :message,  :params,  :today,  :ip,  :line_number,  :controller,  :user_ref_id )";

            $em = $this->em->getConnection();
            $statement = $em->prepare($query);
            return $statement->execute([
                'type' => 'error',
                'post_request' => $post_request,
                'get_request' => $get_request,
                'header' => $header,
                'route' => $route,
                'message' => $message,
                'params' => $params,
                'today' => Date('Y-m-d H:i:s'),
                'ip' => $ip,
                'line_number' => $line_number,
                'controller' => $controller,
                'user_ref_id' => $user_id,
            ]);

        } catch (\Exception $e) {

        }

    }

    public function LogScore($clientId, $scoreType){

    }

}
