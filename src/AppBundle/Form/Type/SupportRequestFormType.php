<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Member;
use AppBundle\Entity\SupportRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupportRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Your email address',
                'attr'  => [
                    'readonly' => 'readonly'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => 10,
                ]
            ])
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'username',
                'attr'  => [
                    'readonly' => 'readonly',
                    'class' => 'hidden',
                ],
                'label_attr' => [
                    'class' => 'hidden'
                ]
            ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'data_class' => SupportRequest::class
        ]);
    }
}