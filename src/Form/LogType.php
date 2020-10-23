<?php

namespace App\Form;

use App\Entity\Log;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitparms = ['label' => 'Salva', 'attr' => ['class' => 'btn-outline-primary bisubmit']];
        $builder
                ->add('submit', SubmitType::class, $submitparms)
                ->add('data', DateTimeType::class, [
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'attr' => ['class' => 'bidatetimepicker'],
                ])
                ->add('volt')
                ->add('temp')
                ->add('device')
                ->add('detectorperc')
                ->add('longitude')
                ->add('latitude')
                ->add('weather')
                ->add('externaltemp')
                ->add('cloudiness')
                ->add('location')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Log::class,
            'parametriform' => [],
        ]);
    }
}
