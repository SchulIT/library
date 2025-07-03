<?php

namespace App\Controller\Settings;

use App\Settings\LabelSettings;
use Jbtronics\SettingsBundle\Form\SettingsFormFactoryInterface;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LabelSettingsAction extends AbstractController {
    public function __construct(private readonly SettingsManagerInterface     $settingsManager,
                                private readonly SettingsFormFactoryInterface $settingsFormFactory) {

    }

    #[Route('/settings/labels', name: 'label_settings')]
    public function __invoke(Request $request): RedirectResponse|Response {
        $clone = $this->settingsManager->createTemporaryCopy(LabelSettings::class);
        $builder = $this->settingsFormFactory->createSettingsFormBuilder($clone);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->settingsManager->mergeTemporaryCopy($clone);
            $this->settingsManager->save();

            $this->addFlash('success', 'settings.success');

            return $this->redirectToRoute('label_settings');
        }

        return $this->render('settings/labels.html.twig', [
            'form' => $form->createView()
        ]);
    }
}