<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function h5ab_print_settings() {

    $printActive = ( isset ( $_POST['h5ab-print-active'] ) ) ? trim(strip_tags($_POST['h5ab-print-active'])) : null;
    $printLabel = ( isset ( $_POST['h5ab-print-label'] ) ) ? trim(strip_tags($_POST['h5ab-print-label'])) : null;
    $printIconColor = ( isset ( $_POST['h5ab-print-icon-color'] ) ) ? trim(strip_tags($_POST['h5ab-print-icon-color'])) : null;
    $printIconSize = ( isset ( $_POST['h5ab-print-icon-size'] ) ) ? trim(strip_tags($_POST['h5ab-print-icon-size'])) : null;
    $printPlacement = ( isset ( $_POST['h5ab-print-placement'] ) ) ? trim(strip_tags($_POST['h5ab-print-placement'])) : null;
    $printAlignment = ( isset ( $_POST['h5ab-print-alignment'] ) ) ? trim(strip_tags($_POST['h5ab-print-alignment'])) : null;
    $printCustomCSS = ( isset ( $_POST['h5ab-print-css'] ) ) ? strip_tags($_POST['h5ab-print-css']) : null;

    $printActive = sanitize_text_field($printActive);
    $printLabel = sanitize_text_field($printLabel);
    $printIconColor = sanitize_text_field($printIconColor);
    $printIconSize = sanitize_text_field($printIconSize);
    $printPlacement = sanitize_text_field($printPlacement);
    $printAlignment = sanitize_text_field($printAlignment);
    $printCustomCSS = wp_kses_post($printCustomCSS);

    $h5abPrintArray = array (
        "h5abPrintActive" => $printActive,
        "h5abPrintLabel" => $printLabel,
        "h5abPrintIconColor" => $printIconColor,
        "h5abPrintIconSize" => $printIconSize,
        "h5abPrintPlacement" => $printPlacement,
        "h5abPrintAlignment" => $printAlignment
    );

	$updated = update_option( 'h5abPrintData', $h5abPrintArray) || update_option('h5abPrintCSS', $printCustomCSS);
	$message = ($updated) ? 'Settings successfully saved' : 'Settings could not be saved';
	$response = array('success' => esc_attr($updated), 'message' => esc_attr($message));

    return $response;

}

?>
