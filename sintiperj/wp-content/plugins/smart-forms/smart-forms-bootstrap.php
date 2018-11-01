<?php
wp_enqueue_script('jquery');
wp_enqueue_script('isolated-slider',SMART_FORMS_DIR_URL.'js/rednao-isolated-jq.js',array('jquery'));
wp_enqueue_script('rednao-commons',SMART_FORMS_DIR_URL.'js/utilities/rnCommons.js',array('isolated-slider'));
wp_enqueue_style('smart-forms-bootstrap-theme',SMART_FORMS_DIR_URL.'css/bootstrap/bootstrap-theme.css');
wp_enqueue_style('smart-forms-bootstrap',SMART_FORMS_DIR_URL.'css/bootstrap/bootstrap-scopped.css');
wp_enqueue_style('smart-forms-ladda',SMART_FORMS_DIR_URL.'css/bootstrap/ladda-themeless.min.css');
wp_enqueue_style('smart-forms-fontawesome',SMART_FORMS_DIR_URL.'css/bootstrap/font-awesome.min.css');

wp_enqueue_script('smart-forms-bootstrap-theme',SMART_FORMS_DIR_URL.'js/bootstrap/bootstrapUtils.js',array('isolated-slider','smart-forms-bootstrap-js'));
wp_enqueue_script('smart-forms-bootstrap-js',SMART_FORMS_DIR_URL.'js/bootstrap/bootstrap.min.js',array('isolated-slider'));
wp_enqueue_script('smart-forms-spin-js',SMART_FORMS_DIR_URL.'js/bootstrap/spin.min.js');
wp_enqueue_script('smart-forms-ladda-js',SMART_FORMS_DIR_URL.'js/bootstrap/ladda.min.js',array('smart-forms-spin-js'));