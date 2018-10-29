<?php
/*
Plugin Name: Dados da Imobili&aacute;ria
Description: Configura apelido e c&oacute;digo da imobili&aacute;ria.
Version: 0.1
Author: BaseSoftware
Author URI: http://www.basesoftware.com.br
*/

/**
* Ativação
*
* Função executada ao ativar o plugin
*
*/
function dadosimob_ativar(){
    
    add_option( 'dadosimob_status', true );
    add_option( 'dadosimob_apelido', '' );
    add_option( 'dadosimob_codigo', '' );
    
    update_option('dadosimob_status', true);
    
    // HOOK de desinstalação
    register_uninstall_hook( __FILE__, 'dadosimob_desinstalar' );
}

/**
* Desativação
*
* Função executada ao desativar o plugin
*
*/
function dadosimob_desativar(){
    update_option('dadosimob_status', false);
}

/**
* Desinstalação
*
* Função executada ao desinstar (deletar) o plugin
*
*/
function dadosimob_desinstalar(){
    delete_option('dadosimob_status');
    delete_option('dadosimob_apelido');
    delete_option('dadosimob_codigo');
}

/**
* Menu
*
* Adiciona novo menu no painel de admin
*
*/
function dadosimob_menu(){
    add_menu_page('Dados Gerais', 'Imobiliária', 10, 'dados-imobiliaria', 'dadosimob_admin', plugin_dir_url( __FILE__ ).'icon_m.png', 21031989);

}

/**
* Configurações
*
* Página de configuração do plugin
*
*/
function dadosimob_admin(){
    $msg = '';

    if($_POST){

        update_option( 'dadosimob_apelido', $_POST['apelido'] );
        update_option( 'dadosimob_codigo', $_POST['codigo'] );
        
        $msg = '<div class="updated" id="message"><p>Dados atualizados com sucesso!</p></div>';
    }
    
?>
    <style>
       #icon-dados-imobiliaria{ background: transparent url(<?php echo plugin_dir_url( __FILE__ ).'icon_b.png'; ?>) no-repeat; }
    </style>
    <div class="wrap">
        <?php screen_icon(); ?>

        <h2>Imobiliária - Dados Gerais</h2>
        <?php echo $msg; ?>
        <form action="" method="post">
        <dt>
            <dd>
                Apelido: <input type="text" name="apelido" value="<?php echo get_option( 'dadosimob_apelido' );?>"/><br />
                Código: <input type="text" name="codigo" value="<?php echo get_option( 'dadosimob_codigo' );?>"/>
            </dd>
        </dt>
        <br />
        <input type="submit" name="Submit" class="button-primary" value="Salvar altera&ccedil;&otilde;es" /> 
        </form>
    </div>
    

<?php 
}

/**
* Mensagens no painel
*
* Função que verifica e mostra mensagens de Sucesso, Alerta e Erro no painel de administração
*
* @return string echo na mensagem formatada
*/
function dadosimob_msgAviso(){
    $dadosimob = get_option( 'dadosimob_opcoes' );
    $sucesso = '<div id="imob-sucesso" class="updated fade"><p>';
    $alerta = '<div id="imob-alerta" class="updated fade"><p>';
    $erro = '<div id="imob-erro" class="error fade"><p>';
    $fim = '</p></div>';
    $msg = '';

    if( get_option( 'dadosimob_apelido' ) == '' || get_option( 'dadosimob_codigo' ) == '' ){
        $msg .= $alerta;
        $msg .= '<strong style="color:red;">Aten&ccedil;&atilde;o:</strong> Voc&ecirc; deve configurar os <strong>Dados da Imobili&aacute;ria</strong>.';
        $msg .= $fim;
    }

    echo $msg;
}



// HOOK de ativação
register_activation_hook( __FILE__, 'dadosimob_ativar' );
 
// HOOK de desativação
register_deactivation_hook( __FILE__, 'dadosimob_desativar' );

// ACTION para adicionar novo menu
add_action('admin_menu', 'dadosimob_menu');

// ACTION de avisos no painel de administração
add_action('admin_notices', 'dadosimob_msgAviso');

// Adiciona ShortCode [ImobCodigo] e [ImobApelido]
function dadosimob_codigo(){ return get_option('dadosimob_codigo');}
function dadosimob_apelido(){ return get_option('dadosimob_apelido');}
add_shortcode( 'ImobCodigo', 'dadosimob_codigo' );
add_shortcode( 'ImobApelido', 'dadosimob_apelido' );
