<?php


class SupsysticSlider_Featuredplugins_Module extends Rsc_Mvc_Module
{

    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        $environment = $this->getEnvironment();
        $config = $environment->getConfig();

        $this->registerMenu();

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
		if($this->getEnvironment()->isModule('featuredplugins')) {
			$ui->add(new SupsysticSlider_Ui_BackendStylesheet(
				'ss-bootstrap-simple-css',
				$this->getLocationUrl() . '/assets/css/bootstrap-simple.css'
			));
			$ui->add(new SupsysticSlider_Ui_BackendStylesheet(
				'ss-featured-plugins-css',
				$this->getLocationUrl() . '/assets/css/admin.featured-plugins.css'
			));
		}
    }

    public function registerMenu()
    {
        $menu = $this->getMenu();
        
        $plugin_menu = $this->getConfig()->get('plugin_menu');
        $capability = $plugin_menu['capability'];
        
        $submenu = $menu->createSubmenuItem();
        $submenu->setCapability($capability)
            ->setMenuSlug('supsystic-slider&module=featuredplugins')
            ->setMenuTitle($this->translate('Featured Plugins'))
            ->setPageTitle($this->translate('Featured Plugins'))
            ->setModuleName('featuredplugins');
		// Avoid conflicts with old vendor version
		if(method_exists($submenu, 'setSortOrder')) {
			$submenu->setSortOrder(99);
		}

        $menu->addSubmenuItem('featuredplugins', $submenu);
    }
} 