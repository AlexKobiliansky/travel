<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, array(
                'trim' => true,
                'required' => false,
            ))

            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password', 'required' => false),
                'second_options' => array('label' => 'Repeat Password'),
            ))

            ->add('avatarFile', FileType::class, array(
                'required' => false,
            ))

            ->add('name', TextType::class, array(
                'required' => false,
            ))

            ->add('surname', TextType::class, array(
                'required' => false,
            ))

            ->add('email', EmailType::class, array(
                'required' => false,
            ))

            ->add('phone', TextType::class, array(
                'required' => false,
            ))

            ->add('address', TextType::class, array(
                'required' => false,
            ))

            ->add('dateOfBirth', DateType::class, array(
                'widget' => 'choice',
                'years' => range(date("Y"), 1901),
                'format' => 'ddMMMMyyyy'
            ))

            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
