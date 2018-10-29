<?php

/**
 * Class para Widget
 */
class AreaRestritaWidget extends WP_Widget {
    
    
    /**
     * Class para Widget
     */
    function AreaRestritaWidget() {
        $widget_ops = array(
            'classname'     => 'area-restrita_widget',
            'description'   => 'Mostra o formul&aacute;rio de login da &aacute;rea restrita'
        );
        $this -> WP_Widget( 'area-restrita_widget', '&Aacute;rea Restrita' , $widget_ops );
    }    
    
    //display the widget
    function widget($args, $instance) {
        echo mostrarAreaRestrita();
    }
}