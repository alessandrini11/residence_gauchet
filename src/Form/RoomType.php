<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('capacity')
            ->add('surface')
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Room::TYPES),
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('price')
            ->add('description')
            ->add('facilities')
            ->add('files', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'data_class' => null, 
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
