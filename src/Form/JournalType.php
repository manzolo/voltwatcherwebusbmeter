<?php

namespace App\Form;

use App\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitparms = ['label' => 'Salva', 'attr' => ['class' => 'btn-outline-primary bisubmit', 'aria-label' => 'Salva']];
        $builder
            ->add('submit', SubmitType::class, $submitparms)
            ->add('device_id')
            ->add('dal')
            ->add('al')
            ->add('volt')
            ->add('avgvolt')
            ->add('temp')
            ->add('detectorperc')
            ->add('device')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Journal::class,
            'parametriform' => [],
        ]);
    }
}
