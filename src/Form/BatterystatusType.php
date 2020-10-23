<?php

namespace App\Form;

use App\Entity\Batterystatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatterystatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitparms = ['label' => 'Salva', 'attr' => ['class' => 'btn-outline-primary bisubmit', 'aria-label' => 'Salva']];
        $builder
            ->add('submit', SubmitType::class, $submitparms)
            ->add('fromvolt')
            ->add('tovolt')
            ->add('perc')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Batterystatus::class,
            'parametriform' => [],
        ]);
    }
}
