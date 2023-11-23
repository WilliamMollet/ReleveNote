<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateNoteMatiereType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', NumberType::class, [
                'label' => $this->translator->trans('note'),
                'attr' => [
                    'placeholder' => $this->translator->trans('note'),
                    'class' => 'form-control'
                ]
            ])
            ->add('takenAt',DateTimeType::class, [
                'label' => $this->translator->trans('passage'),
                'input' => 'datetime_immutable',
                'attr' => [
                    'placeholder' => $this->translator->trans('passage'),
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('valider'),
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
