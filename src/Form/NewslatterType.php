<?php

namespace App\Form;

use App\Entity\Newslatter;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewslatterType extends AbstractType
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
            ->add('description',CKEditorType::class)

            ->add('pdfFile',FileType::class,[
                'label'      =>'Journal Interne',
                'label_attr' => [
                    'class' => 'form_control'
                ],
                'attr'       => [
                    'class' => '',  'accept' => 'application/pdf',

                ],
                'required'   => $required,
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
            'data_class' => Newslatter::class,
        ]);
    }
}
