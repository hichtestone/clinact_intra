<?php

namespace App\Form;

use App\Entity\PresedentWord;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PresedentWordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('object',TextType::class, [
                'label' => 'Objet de Mot de présedent',
                'attr'  => [
                    'class' => 'form-control'
                ],
            ])
            ->add('content',CKEditorType::class,[
                'label' => 'Contenu de Mot de présedent',
            ])
            ->add('imageFile', FileType::class, [ 'label' => 'Logo',
                'data_class'  => null,
                'label_attr' => [
                    'class' => 'form_control'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024M',
                        'mimeTypes' => [
                            'image/gif',
                            'image/png',
                            'image/jpeg',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Merci de choisir un type correspond a l\'image',
                    ])
                ],
                'empty_data'  => '',
                'attr'        => [
                    'class'       =>'form-control',
                    'accept'      => 'image/*'


                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PresedentWord::class,
        ]);
    }
}
