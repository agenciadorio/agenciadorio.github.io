<?php

/**
 * Class SupsysticSlider_Settings_Module
 * User settings module
 *
 * @package SupsysticSlider\Settings
 * @author Artur Kovalevsky
 */
class SupsysticSlider_Settings_Module extends Rsc_Mvc_Module
{

    /**
     * @var SupsysticSlider_Settings_Registry
     */
    private $registry;

    /**
     * Returns the Settings Registry
     *
     * @param SupsysticSlider_Settings_SettingsStorageInterface $storage
     * @return SupsysticSlider_Settings_Registry
     */
    public function getRegistry(SupsysticSlider_Settings_SettingsStorageInterface $storage = null)
    {
        if ($this->registry === null) {
            $this->registry = new SupsysticSlider_Settings_Registry(
                $this->getEnvironment()->getConfig()->get('hooks_prefix'),
                $storage
            );
        }

        return $this->registry;
    }

    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        $prefix = $this->getEnvironment()->getConfig()->get('hooks_prefix');

        add_action($prefix . 'after_ui_loaded', array(
            $this, 'loadAssets'
        ));

        $dispatcher = $this->getEnvironment()->getDispatcher();
        $dispatcher->on(
            'ui_menu_items',
            array($this, 'registerMenuItem'),
            3,
            0
        );
    }

    public function onInstall()
    {
        parent::onInstall();

        $registry = $this->getRegistry();
        $registry->set('send_stats', 1);
    }

    /**
     * Loads the assets required by the module
     */
	public function getTemplatesAliases()
	{
		return array(
			'settings.index' => '@settings/index.twig'
		);
	}
    public function loadAssets(SupsysticSlider_Ui_Module $ui)
    {
		$environment = $this->getEnvironment();

		$ui->add(new SupsysticSlider_Ui_BackendJavascript(
            'gg-settings-save-js',
            $this->getLocationUrl() . '/assets/js/gird-gallery.settings.clearCache.js'
        ));

		if ($environment->isModule('settings')) {
			$ui->add(
				new SupsysticSlider_Ui_BackendJavascript(
					'supsystic-slider-chosen-js',
					'//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js'
				)
			);

			$ui->add(
				new SupsysticSlider_Ui_BackendStylesheet(
					'supsystic-slider-chosen',
					'//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css'
				)
			);

			$ui->add(
				new SupsysticSlider_Ui_BackendJavascript(
					'settings.index',
					$this->getLocationUrl() . '/assets/js/settings.index.js'
				)
			);
		}
    }

    public function registerMenuItem()
    {
        $menu = $this->getEnvironment()->getMenu();

        $plugin_menu = $this->getConfig()->get('plugin_menu');
        $capability = $plugin_menu['capability'];

        $submenu = $menu->createSubmenuItem();
        $submenu->setCapability($capability)
            ->setMenuSlug('supsystic-slider&module=settings')
            ->setMenuTitle($this->translate('Settings'))
            ->setPageTitle($this->translate('Settings'))
            ->setModuleName('settings');
		// Avoid conflicts with old vendor version
		if(method_exists($submenu, 'setSortOrder')) {
			$submenu->setSortOrder(70);
		}

        $menu->addSubmenuItem('settings', $submenu);
    }
}