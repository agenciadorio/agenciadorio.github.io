<?php

/**
 * Class SupsysticSlider_Core_Module
 * Core module
 *
 * @package SupsysticSlider\Core
 * @author Artur Kovalevsky
 */
class SupsysticSlider_Core_Module extends Rsc_Mvc_Module
{

    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        parent::onInit();
        $path = dirname(dirname(dirname(dirname(__FILE__))));
        $url = plugins_url(basename($path));
        $config = $this->getEnvironment()->getConfig();

        $config->add('plugin_url', $url);
        $config->add('plugin_path', $path);

		$this->registerTwigFunctions();
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
        add_action('admin_enqueue_scripts', array($this, 'dequeueAssets'));
        add_filter('gg_hooks_prefix', array($this, 'addHooksPrefix'), 10, 1);
    }

    public function enqueueAssets()
    {
        if ($this->isPluginPage()) {
            wp_enqueue_script('notice', 
                $this->getLocationUrl() . '/assets/js/notice.js', 
                array('jquery'),
                '1.0',
                true
            );
        }
    }

    public function dequeueAssets() {
        /* 
            Fix for conflict with Revolution Slider 
        */
        wp_dequeue_style('wp-jquery-ui-dialog');
        wp_dequeue_style('revslider-global-styles');
    }

    /**
     * Adds the plugin's hooks prefix to the hook name
     *
     * @param string $hook The name of the hook
     * @return string
     */
    public function addHooksPrefix($hook)
    {
        $config = $this->getEnvironment()->getConfig();

        return $config->get('hooks_prefix') . $hook;
    }

    public function afterUiLoaded(Callable $callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('$callback must be a callable');
        }

        add_action($this->addHooksPrefix('after_ui_loaded'), $callback);
    }

	public function getProUrl($params = null) {
		$config = $this->getConfig();
		return $config->get('page_url') . (strpos($params, '?') === 0 ? '' : '?') . $params;
	}

	public function buildProUrl(array $parameters = array())
	{
		$config = $this->getEnvironment()->getConfig();
		$homepage = 'https://supsystic.com/plugins/slider/';
		$campaign = 'slider';

		if (!array_key_exists('utm_source', $parameters)) {
			$parameters['utm_source'] = 'plugin';
		}

		if (!array_key_exists('utm_campaign', $parameters)) {
			$parameters['utm_campaign'] = $campaign;
		}

		return $homepage . '?' . http_build_query($parameters);
	}

	private function registerTwigFunctions()
	{

		$twig = $this->getTwig();

		$twig->addFunction(
			new Twig_SimpleFunction(
				'build_pro_url', array($this, 'buildProUrl')
			)
		);

		$twig->addFunction(
			new Twig_SimpleFunction(
				'translate', array($this, 'translate')
			)
		);

		$twig->addFunction(
			new Twig_SimpleFunction(
				'getProUrl', array($this, 'getProUrl')
			)
		);

	}
}
