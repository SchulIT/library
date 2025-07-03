<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\IntType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;

#[Settings]
class LabelSettings {
    use SettingsTrait;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.rows.label', description: 'settings.labels.rows.help')]
    public int $rows = 8;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.columns.label', description: 'settings.labels.columns.help')]
    public int $columns = 3;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.margin.top.label', description: 'settings.labels.margin.top.help')]
    public int $topMarginMM = 4;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.margin.bottom.label', description: 'settings.labels.margin.bottom.help')]
    public int $bottomMarginMM = 4;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.margin.left.label', description: 'settings.labels.margin.left.help')]
    public int $leftMarginMM = 1;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.margin.right.label', description: 'settings.labels.margin.right.help')]
    public int $rightMarginMM = 1;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.cell.width.label', description: 'settings.labels.cell.width.help')]
    public int $cellWidthMM = 70;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.cell.height.label', description: 'settings.labels.cell.height.help')]
    public int $cellHeightMM = 36;

    #[SettingsParameter(type: IntType::class, label: 'settings.labels.cell.padding.label', description: 'settings.labels.cell.padding.help')]
    public int $cellPaddingMM = 5;
}