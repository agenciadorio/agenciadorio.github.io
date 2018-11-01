<?php


class SupsysticSlider_Overview_Module extends Rsc_Mvc_Module
{

    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        $environment = $this->getEnvironment();
        $config = $environment->getConfig();

        $this->registerMenu();

        // Client ID
        $config->add('post_id', 637);
        $config->add('post_url', 'http://supsystic.com/news/main.html');
        $config->add('mail', 'support@supsystic.zendesk.com');

        $prefix = $config->get('hooks_prefix');

        add_action($prefix . 'after_ui_loaded', array(
            $this, 'loadAssets'
        ));
    }

    /**
     * Loads the assets required by the module
     */
    public function loadAssets(SupsysticSlider_Ui_Module $ui)
    {
        $ui->add(new SupsysticSlider_Ui_BackendJavascript(
            'gg-overview-settings-js',
            $this->getLocationUrl() . '/assets/js/overview-settings.js'
        ));

        $ui->add(new SupsysticSlider_Ui_BackendStylesheet(
            'gg-overview-css',
            $this->getLocationUrl() . '/assets/css/overview-styles.css'
        ));
    }

    protected function registerMenu() {
        $menu = $this->getEnvironment()->getMenu();

        $plugin_menu = $this->getConfig()->get('plugin_menu');
        $capability = $plugin_menu['capability'];

        $submenu = $menu->createSubmenuItem();
        $submenu->setCapability($capability)
            ->setMenuSlug('supsystic-slider&module=overview')
            ->setMenuTitle($this->translate('Overview'))
            ->setPageTitle($this->translate('Overview'))
            ->setModuleName('overview');
		// Avoid conflicts with old vendor version
		if(method_exists($submenu, 'setSortOrder')) {
			$submenu->setSortOrder(10);
		}

        $menu->addSubmenuItem('overview', $submenu);
    }
} 