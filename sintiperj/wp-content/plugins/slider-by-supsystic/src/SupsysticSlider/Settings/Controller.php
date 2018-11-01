<?php

/**
 * Class SupsysticSlider_Settings_Controller
 * Settings Controller
 *
 * @package SupsysticSlider\Settings
 * @author Artur Kovalevsky
 */
class SupsysticSlider_Settings_Controller extends SupsysticSlider_Core_BaseController
{

    /**
     * {@inheritdoc}
     */
    protected function getModelAliases()
    {
        return array(
            'settings' => 'SupsysticSlider_Settings_Model_Settings',
        );
    }

    /**
     * Index Action
     * Shows the settings page
     *
     * @param Rsc_Http_Request $request
     * @return Rsc_Http_Response
     */
    public function indexAction(Rsc_Http_Request $request)
    {
        /** @var SupsysticSlider_Settings_Model_Settings $settings */
        $settings = $this->getModel('settings');

        /** @var SupsysticSlider_Settings_Module $module */
        $module   = $this->getModule('settings');
        $registry = $module->getRegistry();
		$templates = $module->getTemplatesAliases();

        $lang = $this->getEnvironment()->getLang();

        $defaultResponseData = array(
            'registry' => $registry,
            'form'     => array(
                'accept_charset' => get_bloginfo('charset'),
                'action'         => $this->getEnvironment()->generateUrl('settings', 'index'),
                'method'         => 'POST',
            ),
            'ajax_url' => admin_url('admin-ajax.php'),
        );

        $validator = new Rsc_Form_Validator($request, $defaultResponseData['form']['method'], array(
            'cache_ttl' => Rsc_Form_Rule_Regex::create($lang->translate('Lifetime for cached data'), '/[0-9]+/')
                ->setMessage($lang->translate('The field "%s" available only for numeric values'))
        ));
        
        if ($validator->isValid() || !$request->post->isEmpty()) {
            $settings->setRegistry($registry);

            $params = [];

            foreach ($request->post as $field => $value) {
                $params[$field] = $value;
            }

            if($request->post && empty($request->post['settings']['access_roles'])) {
                $params['settings']['access_roles'] = [];
            }

            $settings->save($params);

            return $this->response($templates['settings.index'], array_merge($defaultResponseData, array(
//                'message' => $lang->translate('Settings successfully updated!'),
            )));

        } else {

            return $this->response($templates['settings.index'], array_merge($defaultResponseData, array(
//                'message'   => $lang->translate('Please correct following errors:'),
                'validator' => $validator,
            )));

        }
    }

    /**
     * Clear Cache Action
     * Clears the application cache
     *
     * @return Rsc_Http_Response
     */
    public function clearCacheAction()
    {
        $this->getEnvironment()->getCache()->clear();
        return $this->response('ajax');
    }
}