<?php

namespace App\Form;

use App\Entity\Device;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitparms = ['label' => 'Salva', 'attr' => ['class' => 'btn-outline-primary bisubmit']];
        $builder
            ->add('submit', SubmitType::class, $submitparms)
            ->add('name')
            ->add('address')
            ->add('threshold')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
            'parametriform' => [],
        ]);
    }
}
