<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\BorrowerType as BorrowerTypeEntity;

class BorrowerType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'label.type',
                'class' => BorrowerTypeEntity::class
            ])
            ->add('barcodeId', TextType::class, [
                'label' => 'label.barcode_id.label',
                'help' => 'label.barcode_id.help',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'label.lastname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email'
            ])
            ->add('grade', TextType::class, [
                'label' => 'label.grade',
                'required' => false
            ]);
    }
}