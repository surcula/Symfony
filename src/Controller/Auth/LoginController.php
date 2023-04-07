<?php

namespace App\Controller\Auth;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    private $title ;


    /**
     * @ROUTE("/login", name="login", methods="POST")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function login(Request $request,EntityManagerInterface $manager):Response{
//        $req = $request->request;
//        if($req->count() > 0 && $req->get("username")!==null && $req->get("password")!==null){
//            $user = $manager->getRepository(Users::class)->findOneByLowerUsername($req->get("username"));
//
//            if($user !== null && password_verify($req->get('password'),$user->getPassword())){
//                $this->title = "Bienvenue ".$user->getUsername()." sur la première page";
//                $session = $this->get("session");
//                $session->set('filter',array(
//                    "idRole"=>$user->getIdRole()->getId(),
//                    "username"=>$user->getUsername(),
//                    "idUser"=>$user->getId()
//                ));
                return $this->render("View/index.html.twig",["title"=>$this->title]);
//            }
//        }
//
//        return $this->render("View/index.html.twig",["title"=>$this->title, "errorLogin"=>"Error Login/Password"]);
    }

    /**
     * @Route ("/logout", name="logout")
     * @return Response
     */
    public function logout(){
        $session = $this->get('session');
        $session->remove('filter');
        return $this->render('View/index.html.twig',['title'=>$this->title]);
    }
}
