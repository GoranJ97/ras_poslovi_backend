<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class MultipleImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', CollectionType::class, [
            'label'      => 'label.file',
            'required'   => false,
            'data_class' => FileType::class,
            'mapped'     => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
