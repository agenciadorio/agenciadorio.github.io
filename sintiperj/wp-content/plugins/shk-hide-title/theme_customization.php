<?php
function shk_hide_title_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'shk_hide_title_class' , array(
	    'title'      => __( 'Shk Hide Title', 'shk-hide-title' ),
	    'priority'   => 30,
	) );   
	
	$wp_customize->add_setting( 'shk_hide_title_class_name', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
            'default' => '', //Default setting/value to save
            'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
            'transport' => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
         ) 
      );      
            
      $wp_customize->add_control('shk_hide_title_class_name', //Set a unique ID for the control
         array(
            'label' => __( 'Shk Class', 'mytheme' ), //Admin-visible name of the control
            'section' => 'shk_hide_title_class', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
            'settings' => 'shk_hide_title_class_name', //Which setting to load and manipulate (serialized is okay)
            'priority' => 1, //Determines the order this control appears in for the specified section
         ) 
       );
}
add_action( 'customize_register', 'shk_hide_title_customize_register' );

?>