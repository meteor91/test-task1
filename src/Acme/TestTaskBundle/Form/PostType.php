<?php

namespace Acme\TestTaskBundle\Form;

/**
 * Description of PostType
 *
 * @author bilal
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('text', 'textarea')
                ->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\TestTaskBundle\Entity\Post',
        ));
    }

    public function getName() {
        return 'post';
    }

}
