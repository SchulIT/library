<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\Route;

class SettingsMenuBuilder extends AbstractMenuBuilder {
    public function settingsMenu(array $options = [ ]): ItemInterface {
        $root = $this->factory->createItem('root');

        $root->addChild('settings.app.label', [
            'route' => 'app_settings'
        ])
            ->setExtra('icon', 'fa-solid fa-cog');

        $root->addChild('settings.labels.label', [
            'route' => 'label_settings'
        ])
            ->setExtra('icon', 'fa-solid fa-barcode');

        return $root;
    }
}