<?php

namespace App\Form;

use App\Entity\Url;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longURL', \Symfony\Component\Form\Extension\Core\Type\UrlType::class, [
                'required' => true,
                'label' => "Long Url",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://startpage.com',
                    'value' => 'https://',
                ]

            ])
            ->add('shortURL', TextType::class, [
                'required' => false,
                'label' => "Short Url",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'start',

                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success form-control mt-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Url::class,
            'attr' => ['id' => 'url_form'],
        ]);
    }


}
