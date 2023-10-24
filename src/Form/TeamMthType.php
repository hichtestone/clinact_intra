<?php

namespace App\Form;

use App\Entity\TeamMTH;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;

class TeamMthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Regex(
                        array(
                            'pattern' =>'/^((?!<script)[\s\S])*$/',
                            'message' => 'cette valeur n\'est pas valide ',
                        )
                    ),
                    new Regex(
                        array(
                            'pattern' =>'/^((?!<img)[\s\S])*$/',
                            'message' => 'cette valeur n\'est pas valide ')
                    ),
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr'  => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Regex(
                        array(
                            'pattern' =>'/^((?!<script)[\s\S])*$/',
                            'message' => 'cette valeur n\'est pas valide ',
                        )
                    ),
                    new Regex(
                        array(
                            'pattern' =>'/^((?!<img)[\s\S])*$/',
                            'message' => 'cette valeur n\'est pas valide ')
                    ),
                ]
            ])
            ->add('imageFile',FileType::class,[
                'label'      =>'Video',
                'attr'       => [
                    'class' => 'form-control form-control-sm',
                    'accept' => 'video/*'
                ],
                'required'   => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // We want to let upload only txt, csv or Excel files
                            'video/mp4',
                        ],
                        'maxSize'=>'24M',
                        'mimeTypesMessage' => "Ce type de fichier n'est pas pris en charge !!",
                    ]) ],
            ])
            ->add('imageFile1',FileType::class,[
                'label'      =>'Journal Interne',
                'attr'       => [
                    'class' => 'form-control form-control-sm',  'accept' => 'application/pdf',

                ],
                'required'   => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'maxSize'=>'24M',
                        'mimeTypesMessage' => "Ce type de fichier n'est pas pris en charge !!",
                    ]) ],
            ])
            ->add('publishedAt',DateType::class,[
                'label' => 'Date de publication',
                'attr' => [
                    'class' => 'form-control form-control-solid datepick',
                ],
                'html5' => false,
                'widget' => 'single_text',
                'required' => true,
                'format' => 'dd/MM/yyyy'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamMTH::class,
        ]);
    }
}
