<?php

namespace App\Form;

use App\Entity\Contact;
use Nicbond\RecaptchaBundle\Type\RecaptchaSubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom'
            ],TextType::class)
            ->add('lastname', null,[
                'label' => 'Nom'
            ], TextType::class)
            ->add('phone', null,[
                'label' => 'Téléphone'
            ], TextType::class)
            ->add('email', null,[
                'label' => 'Email'
            ], EmailType::class)
            ->add('message', TextareaType::class)
            ->add('envoyer', SubmitType::class)

           
            
            // ->add('captcha', RecaptchaSubmitType::class, [
            //     'label' => 'Envoyer',
            //     'attr' => [
            //         'class' => 'btn btn-primary'
            //         ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
