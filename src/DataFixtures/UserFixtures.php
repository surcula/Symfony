<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $role = new Roles();
        $role->setLabel('Admin');
        $manager->persist($role);
        $role2 = new Roles();
        $role2->setLabel('User');
        $manager->persist($role2);

        $usernames = ["David","Flo","test"];
        foreach ($usernames as $key => $value){
            $user = new Users();
            $user->setUsername($value);
            $user->setPassword(password_hash('test',PASSWORD_BCRYPT));
            if($key == 0){
                $user->setIdRole($role);
            }else{
                $user->setIdRole($role2);
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
