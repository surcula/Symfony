<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class)
            ->add('password',PasswordType::class)
            ->add('confirm_password',PasswordType::class)
            ->add('mail',EmailType::class)
            ->add('idRole', EntityType::class,[
                'class'=>Roles::class,
                'choice_label'=> 'label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            "Validation_groups"=>["Default","registration"],
        ]);
    }
}
