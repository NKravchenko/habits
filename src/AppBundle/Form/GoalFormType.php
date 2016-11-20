<?php

namespace AppBundle\Form;

use AppBundle\Entity\GoalType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, [
                'label'    => 'Краткое название',
                'required' => true,
                'attr'        => array(
                    'placeholder' => 'Обзательное поле',
                ),
            ])
        ->add('isActive', ChoiceType::class, [
            'label'   => 'Еще работаю над целью?',
            'choices' => [
                'В процессе' => true,
                'Завершил'   => false,
            ]
        ])
        ->add('result', ChoiceType::class, [
            'label'   => 'По завершении работы над целью я достиг поставленной цели?',
            'choices' => [
                'Достиг' => true,
                'Не достиг'   => false,
            ]
        ])
        ->add('description', TextareaType::class, [
            'label'       => 'Описание',
            'empty_data'  => null,
            'attr'        => array(
                'style'       => 'resize: none',
                'placeholder' => 'Необязательное описание, чего Вы хотите добиться',
            ),
        ])
        ->add('inWeekend', ChoiceType::class, [
            'label'   => 'Учитывать выходные дни?',
            'choices' => [
                'Учитывать'    => true,
                'Не учитывать' => false,
            ]
        ])
        ->add('dateStop', DateType::class, [
            'label'  => 'Планируемая дата окончания',
            'widget' => 'single_text',
            'attr'   => ['class' => 'js-datepicker'],
            'html5'  => false,
        ])

        ->add('goalType', EntityType::class, [
            'label'  => 'Тип вводимых данных',
            'class'  => GoalType::class,
            'choices_as_values' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Goal',
        ]);
    }

}
