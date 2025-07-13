<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LabelTemplateType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'label.name'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'label.description',
                'required' => false
            ])
            ->add('rows', IntegerType::class, [
                'label' => 'settings.labels.rows.label',
                'help' => 'settings.labels.rows.help'
            ])
            ->add('columns',  IntegerType::class, [
                'label' => 'settings.labels.columns.label',
                'help' => 'settings.labels.columns.help'
            ])
            ->add('topMarginMM',  NumberType::class, [
                'label' => 'settings.labels.margin.top.label',
                'help' => 'settings.labels.margin.top.help'
            ])
            ->add('bottomMarginMM',  NumberType::class, [
                'label' => 'settings.labels.margin.bottom.label',
                'help' => 'settings.labels.margin.bottom.help'
            ])
            ->add('leftMarginMM',  NumberType::class, [
                'label' => 'settings.labels.margin.left.label',
                'help' => 'settings.labels.margin.left.help'
            ])
            ->add('rightMarginMM',  NumberType::class, [
                'label' => 'settings.labels.margin.right.label',
                'help' => 'settings.labels.margin.right.help'
            ])
            ->add('cellWidthMM', NumberType::class, [
                'label' => 'settings.labels.cell.width.label',
                'help' => 'settings.labels.cell.width.help'
            ])
            ->add('cellHeightMM',   NumberType::class, [
                'label' => 'settings.labels.cell.height.label',
                'help' => 'settings.labels.cell.height.help'
            ])
            ->add('cellPaddingMM', NumberType::class, [
                'label' => 'settings.labels.cell.padding.label',
                'help' => 'settings.labels.cell.padding.help'
            ]);
    }
}