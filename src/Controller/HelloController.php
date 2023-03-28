<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    protected $logger;
    private $title = "Hello ";
//    /**
//     * @Route("/hello", name="app_hello")
//     */
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }

    /**
     * @Route("/test/{name?world}",name="test",methods={"GET","POST"},host="localhost",schemes={"http","https"})
     */
    public function test($name){
        $this->title.=$name;
        return $this->render('View/item/hello.html.twig',["title"=>$this->title]);
    }
}
