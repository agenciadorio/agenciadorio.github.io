<?php
/*
Plugin Name: &Aacute;rea Restrita
Description: Plugin de &Aacute;rea Restrita para os sites Personalizados desenvolvidos pela BaseSoftware. <strong>Requerido o plugin Dados da Imobili&aacute;ria</strong>.
Version: 0.1
Author: BaseSoftware
Author URI: http://www.basesoftware.com.br
*/

require_once plugin_dir_path( __FILE__ ).'formatacao.php';
require_once plugin_dir_path( __FILE__ ).'widget.php';

/**
* Ativação
*
* Função executada ao ativar o plugin
*
*/
function ar_ativar(){

    add_option( 'arearestrita_status', true );
    add_option( 'arearestrita_css', ar_css() );
    add_option( 'arearestrita_label', 's' );
    add_option( 'arearestrita_labelcss', ar_labelcss() );
    add_option( 'arearestrita_textoenviar', 'Enviar' );

    update_option('arearestrita_status', true);
    
    // HOOK de desinstalação
    register_uninstall_hook( __FILE__, 'ar_desinstalar');

}

/**
* Desativação
*
* Função executada ao desativar o plugin
*
*/
function ar_desativar(){
    update_option('arearestrita_status', false);
}

/**
* Desinstalação
*
* Função executada ao desinstalar (deletar pelo painel) o plugin
*
*/
function ar_desinstalar(){
    delete_option( 'arearestrita_status');
    delete_option( 'arearestrita_css');
    delete_option( 'arearestrita_label');
    delete_option( 'arearestrita_labelcss');
    delete_option( 'arearestrita_textoenviar');

}

/**
* Verifica plugin Dados Imobiliaria
*
* Função auxiliar que verifica se o plugin Dados da Imobiliaria está instalado e configurado
*
* @return array
*/
function ar_Verifica(){
    $verifica['config'] = false;
    $verifica['status'] = false;

    if(get_option('dadosimob_status')){
        $verifica['status'] = true;

        if(get_option('dadosimob_apelido') != '' || get_option('dadosimob_codigo') != ''){
            $verifica['config'] = true;
        }
    }
    
    return $verifica;
}

/**
* Arquivos externos Javascript
*
* Adiciona arquivos externos de javascript. Ex: jQuery
*
*/
function ar_bibliotecas() {
    wp_enqueue_script( 'jquery');
}

/**
* Mensagens no painel
*
* Função que verifica e mostra mensagens de Sucesso, Alerta e Erro no painel de administração
*
* @return string echo na mensagem formatada
*/
function ar_msgAviso(){
    $alerta = '<div id="imob-alerta" class="updated fade"><p>';
    $erro = '<div id="imob-erro" class="error fade"><p>';
    $fim = '</p></div>';
    $msg = '';
    $verifica = ar_Verifica();

    if(!$verifica['status']){
        $msg .= $erro;
        $msg .= '<strong style="color:red;">Aten&ccedil;&atilde;o:</strong> Para o Plugin <strong>&Aacute;rea Restrita</strong> funcionar, é requerida a instalação e configuração do plugin <strong>Dados da Imobiliária</strong>.';
        $msg .= $fim;
    } 
/*    else if(!$verifica['config']){
        $msg .= $alerta;
        $msg .= '<strong style="color:red;">Aten&ccedil;&atilde;o:</strong> Voc&ecirc; ainda não configurou os <strong><a href="'.$url.'">Dados da Imobili&aacute;ria</a></strong>.';
        $msg .= $fim;
    } 
*/
    
    echo $msg;
}

/**
* Menu
*
* Adiciona novo menu no painel de admin
*
*/
function ar_menu(){
    if (function_exists('dadosimob_menu')){
        dadosimob_menu();
        add_submenu_page('dados-imobiliaria', '&Aacute;rea Restrita', '&Aacute;rea Restrita', 10, ''.substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).'/admin.php');
    }
}

/**
* Mostra o formulario de area restrita
*
* Função que mostra no tema o formulario de area restrita.
*
*/
function mostrarAreaRestrita(){
    $css = get_option('arearestrita_css');
    $javascript = '';
    $html = ar_html();
    
    if(get_option('arearestrita_label') == 's'){
        $css .= get_option('arearestrita_labelcss');
        $javascript = ar_js();
    }
    
    $arearestrita = $css.$javascript.$html;

    
    return $arearestrita;
    
}

/**
* Registra Widget no Wordpress
*
* Função adiciona aos widgets o criado na classe
*
*/
function ar_widget() {
    register_widget( 'AreaRestritaWidget' );
}


// HOOK de ativação
register_activation_hook( __FILE__, 'ar_ativar');
 
// HOOK de desativação
register_deactivation_hook( __FILE__, 'ar_desativar');

// ACTION de avisos no painel de administração
add_action('admin_notices', 'ar_msgAviso');

// ACTION de menu
add_action('admin_menu', 'ar_menu');

// ACTION de que é executada depois do wordpress terminar de carregar
add_action( 'init', 'ar_bibliotecas' );

// ACTION executada para registrar o widget
add_action( 'widgets_init', 'ar_widget' );