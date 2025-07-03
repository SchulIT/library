<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class BookCopyType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('canCheckout', CheckboxType::class, [
                'required' => false,
                'label' => 'label.can_checkout'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'label.comment',
                'required' => false
            ]);
    }
}