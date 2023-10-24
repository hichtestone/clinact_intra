<?php

namespace App\Form;

use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadTrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile',FileType::class,[
                'label'      =>'Fiche de connaissance',
                'label_attr' => [
                    'class' => 'form_control'
                ],
                'attr'       => [
                    'class' => 'form-control form-control-sm',
                    'accept' => 'application/pdf',

                ],
                'required'   => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'maxSize'=>'24M',
                        'mimeTypesMessage' => "Ce type de fichier n'est pas pris en charge !!",
                    ]) ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
