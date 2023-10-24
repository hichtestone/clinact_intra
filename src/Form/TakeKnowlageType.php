<?php

namespace App\Form;

use App\Entity\Takeknowledge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TakeKnowlageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isSigneed', ChoiceType::class, [
                'label' => 'choix',
                'choices' => [
                    'Oui' => 1,
                    'Non'=> 2
                ],
                'required' => false,
                'expanded' => true,
                'placeholder' => false,
                'attr'    => [
                    'class' => 'select2input  form-control-sm mb-3',
                    'style' => 'margin-right:10px',
                ]

            ])
          /*  ->add('signedAt')
            ->add('user')
            ->add('documentTransverse')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Takeknowledge::class,
        ]);
    }
}
