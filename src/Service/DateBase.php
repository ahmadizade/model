<?php

namespace App\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;

class DateBase extends Controller
{
    protected $em;
    public $query;
    public $variable;
    private $params;
    protected $logger;
    protected $log;
    protected $container;

    public function __construct( EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->container = $container;
        $this->logger = $logger;
    }

    public function fetchAll($query, $variable = '')
    {
        $this->query = $query;
        $this->variable = $variable;

        try {
            $em = $this->em->getConnection();
            $params = $this->GetParams();
            return $em->fetchAll($query, $params);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return false;
        }
    }

    public function fetchAssoc($query, $variable = '')
    {
        $this->query = $query;
        $this->variable = $variable;

        try {
            $em = $this->em->getConnection();
            $params = $this->GetParams();
            return $em->fetchAssoc($query, $params);
        }catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return false;
        }
    }

    public function Delete($query, $variable = '')
    {
        $this->query = $query;
        $this->variable = $variable;

        try {
            $params = $this->GetParams();
            $em = $this->em->getConnection();
            $statement = $em->prepare($query);
            return $statement->execute($params);

        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return false;
        }
    }

    public function Update($query, $variable = '')
    {
        $this->query = $query;
        $this->variable = $variable;

        try {
            $params = $this->GetParams();
            $em = $this->em->getConnection();
            $statement = $em->prepare($query);
            return $statement->execute($params);

        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return false;
        }
    }

    public function Insert($query, $variable = '')
    {
        $this->query = $query;
        $this->variable = $variable;

        try {
            $params = $this->GetParams();
            $em = $this->em->getConnection();
            $statement = $em->prepare($query);
            return $statement->execute($params);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return false;
        }
    }

    // if $variable is empty it get $_POST as $variable
    public function InsertGetID($query, $variable = '')
    {
        $this->query = $query;
        $this->variable = $variable;

        try {
            $params = $this->GetParams();
            $em = $this->em->getConnection();
            $statement = $em->prepare($query);
            $statement->execute($params);
            return $em->lastInsertId();
        } catch (UniqueConstraintViolationException $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return true;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['query' => $query]);
            return false;
        }
    }

    public function GetParams()
    {
        $new_params = $params = array();

        $query = $this->query;
        preg_match_all('/\:{1}[a-zA-Z_]+/', $query, $params);

        if (empty($this->variable))
            $this->variable = $_POST;

        foreach ($params['0'] as $key => $value) {
            $value = trim($value, ':');
            if (array_key_exists($value, $this->variable))
                $new_params[$value] = $this->variable[$value];
            elseif ($value == 'created_at')
                $new_params[$value] = date('Y-m-d H:i:s');
        }

        return $new_params;
    }

}
