<?php

namespace App\Form;

use App\Entity\Log;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitparms = array('label' => 'Salva','attr' => array("class" => "btn-outline-primary bisubmit"));
        $builder
            ->add('submit', SubmitType::class, $submitparms)
            ->add('device_id')
            ->add('data')
            ->add('volt')
            ->add('temp')
            ->add('device')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Log::class,
            'parametriform' => array()
        ]);
    }
}