<?php

namespace App\Form;

use App\Entity\Video;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;


class VideoType extends AbstractType
{
    private $requestsatck;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestsatck = $requestStack;
        $this->entityManager = $entityManager;
        // $this->repository=$repository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request = $this->requestsatck->getCurrentRequest();
        $route   = $request->attributes->get('_route');
        $required = $route === 'admin.video.new';
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => [
                    'class' => 'form-control'
                ],
            ])

            ->add('tags', CollectionType::class, [
                'entry_type'    => TagType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add'     => true,
                'label'         => false,
                'prototype' => true,


            ])

            ->add('imageFile',FileType::class,[
                'label'      =>'Video',
                'label_attr' => [
                    'class' => 'form_control'
                    ],
                'attr'       => [
                    'class' => '',  'accept' => 'video/*'
                ],
                'required'   => $required,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // We want to let upload only txt, csv or Excel files
                            'video/mp4',
                        ],
                        'mimeTypesMessage' => "Ce type de fichier n'est pas pris en charge !!",
                    ]) ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
