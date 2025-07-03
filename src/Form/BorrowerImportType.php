<?php

namespace App\Form;

use App\Entity\BorrowerType as BorrowerTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class BorrowerImportType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'label.type',
                'class' => BorrowerTypeEntity::class
            ])
            ->add('delimiter', ChoiceType::class, [
                'label' => 'label.delimiter',
                'choices' => [
                    ';' => ';',
                    ',' => ','
                ],
                'data' => ';'
            ])
            ->add('file', FileType::class, [
                'label' => 'label.file',
            ])
            ->add('delete', CheckboxType::class, [
                'required' => false,
                'label' => 'borrowers.import.delete.label',
                'help' => 'borrowers.import.delete.help',
            ]);
    }
}