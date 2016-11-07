<?php

namespace TodoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use TodoBundle\Form\Type\BooleanType;

/**
 * Class TodoType
 * @package TodoBundle\Form
 */
class TodoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
                ->add('description', TextareaType::class)
                ->add('deadline', DateTimeType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm'))
                ->add('completed', BooleanType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'TodoBundle\Entity\Todo',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName ()
    {
        return 'todobundle_todo';
    }
}