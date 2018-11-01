<?php

/**
 * Class SupsysticSlider_Settings_Model_Settings
 *
 * @package SupsysticSlider\Settings\Model
 * @author Artur Kovalevsky
 */
class SupsysticSlider_Settings_Model_Settings extends SupsysticSlider_Core_BaseModel
{

    /**
     * @var SupsysticSlider_Settings_Registry
     */
    private $registry;

    /**
     * Sets the Settings Registry object
     *
     * @param \SupsysticSlider_Settings_Registry $registry
     * @return SupsysticSlider_Settings_Model_Settings
     */
    public function setRegistry($registry)
    {
        $this->registry = $registry;
        return $this;
    }

    public function save($params)
    {
        foreach ($params as $field => $value) {
            $this->registry->set($field, $value);
        }
    }
} 