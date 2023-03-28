<?php

namespace App\Controller\Auth;

use App\Entity\Users;
use App\Entity\Roles;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{


    /**
     * @Route ("/account/new",name="account")
     * @Route ("/account/edit/{id}",name="account-edit")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function accountForm(Request $request, EntityManagerInterface $entityManager):Response{

        $update = false;
        if($request->attributes->get('_route')==='account-edit'){
            $filter = $this->get('session')->get('filter');
            $idRoleUser = $filter['idRole'];
            $idUser = $filter['idUser'];
            if($idUser == $request->attributes->get('id') || $idRoleUser == 1){
                $user = $entityManager->getRepository(Users::class)->find($request->attributes->get('id'));
            }else{
                return $this->redirectToRoute('index');
            }
            $update = true;
        }else{
            $user = new Users();
        }
        $userForm = $this->createForm(UserType::class,$user);
//        $userForm = $this->createFormBuilder($user)
//            ->add('username',TextType::class)
//            ->add('password',PasswordType::class)
//            ->add('mail',EmailType::class)
//            ->add('idRole',EntityType::class,[
//                'class'=>Roles::class,
//                'choice_label'=>'label',
//            ])
//            ->getform();
        if($update){
            $userForm->remove('password');
            $userForm->remove('confirm_password');
        }
        $userForm->handleRequest($request);
        if($userForm->isSubmitted() && $userForm->isValid()){
            $user = $userForm->getData();
            if (!$update){
                $passHashed = password_hash($user->getPassword(),PASSWORD_BCRYPT);
                $user->setPassword($passHashed);
            }else{
                if($user->getIdRole()==null){
                    $user->setIdRole($entityManager->getRepository(Roles::class)->find($idRoleUser));
                }
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('View/auth/account.html.twig',['formAccount'=>$userForm->createView(), 'editMode'=> $update]);
    }
}
