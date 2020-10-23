<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitparms = ['label' => 'Salva', 'attr' => ['class' => 'btn-outline-primary bisubmit', 'aria-label' => 'Salva']];
        $builder
            ->add('submit', SubmitType::class, $submitparms)
            ->add('key')
            ->add('value')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
            'parametriform' => [],
        ]);
    }
}
