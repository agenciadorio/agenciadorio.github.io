<?php

/**
* Javascript
*
* Função retorna string com javascript para frase de exemplo
*
* @return string
*/
function ar_js(){
    
    $javascript = "<script type='text/javascript'>
    jQuery(document).ready(function(){
    // (INICIO) Label de login
        jQuery('#arearestrita input[title]').each(function() {
            if(jQuery(this).val() == '') {
                jQuery('#senha').hide();
                jQuery(this).val(jQuery(this).attr('title')); 
            }

            jQuery(this).focus(function() {
                if(jQuery(this).val() == jQuery(this).attr('title')) {
                    jQuery(this).val(''); 
                }
            });
            jQuery('#senhatxt').focus(function() {
                if(jQuery('#senhatxt').val() == jQuery('#senhatxt').attr('title')) {
                    jQuery('#senhatxt').hide();
                    jQuery('#senha').show().val('').focus();
                }
            });			

            jQuery(this).blur(function() {
                if(jQuery(this).val() == '') {
                    jQuery(this).val(jQuery(this).attr('title'));
                }
            });

            jQuery('#senha').blur(function() {
                if(jQuery('#senha').val() == '') {
                    jQuery('#senha').hide();
                    jQuery('#senhatxt').show().val(jQuery('#senhatxt').attr('title'));
                }
            });
        });
    // (FIM) Label de login
    });
</script>";
    
    return $javascript;
}

/**
* CSS para formulario
*
* Função retorna string com css do formulario
*
* @return string
*/
function ar_css(){
    $css = "<style>
#arearestrita{ 
	background:#f0f0f0; 
	width:256px; 
	position:relative; 
	padding: 15px; 
	font:12px Verdana; 
	color:#333;
}
#arearestrita label{
	display:block;
	margin-top: 5px;
	margin-bottom: 3px;
}
.cmp {
	border: 1px solid #ccc; 
	width: 167px; 
	height: 21px; 
	background: #fff; 
	font:13px Arial; 
	color:#373737;
}
#ok{
	border: 0 none; 
	background: #BBB;
	cursor: pointer;
	padding: 3px 4px;
	margin-top: 5px;
}
.fpw a{ 
	font:11px Tahoma; 
	text-decoration:none;
}
.fpw a:hover{
	text-decoration:underline;
}
        </style>";
    
    return $css;
}

/**
* CSS para label
*
* Função retorna string com css especifico do formulario com label
*
* @return string
*/
function ar_labelcss(){
    $css = "<style>
.fpw{
	margin-top: 30px;
}
.senha {
	position: absolute;
	top: 40px;
	left: 15px;
	margin-top: 5px;
}
        </style>";
    
    return $css;
}

/**
* HTML para formulario
*
* Função retorna string com html do formulario
*
* @return string
*/
function ar_html(){
    $html = '<div id="arearestrita">
	<form target="_top" action="http://'.get_option("dadosimob_apelido").'.wsrun.net/php/sistema.php" name="formulario" method="post" id="flogin">
		<input type="hidden" value="Frame_Parceiro" name="action" />
		<input type="hidden" value="'.get_option("dadosimob_codigo").'" name="codigo" />
                '.ar_htmlComplemento("taglabel_login").'
		<span class="cmp_r cmpr_login"><input type="text" autocomplete="off" value="" id="login" name="login" title="Login" class="cmp login" /></span>
                '.ar_htmlComplemento("taglabel_senha").'
		<span class="cmp_r cmpr_senha"><input type="password" autocomplete="off" id="senha" name="senha" class="cmp senha" /></span>
                '.ar_htmlComplemento("campo_senhaAuxiliar").'
		<script language="JavaScript">
			function abrejanela(link) {
				var msgWindow;
				msgWindow=window.open(link,\'Senha\',\'resizable=no,width=500,height=500,dependent=yes,scrollbars=yes\');
			}
		</script>
		<div class="fpw"><a onclick="abrejanela(\'http://'.get_option("dadosimob_apelido").'.wsrun.net/php/email.php?action=email_senha&Codigo_Parceiro='.get_option("dadosimob_codigo").'\');" href="#">Esqueceu sua senha?</a></div>
		<span class="cmp_r2 cmpr2_ok"><input type="submit" name="ok" id="ok" class="ok" value="'.get_option("arearestrita_textoenviar").'" /></span>
	</form>
</div>';
    
    return $html;
}

/**
* HTML complementar do formulario
*
* Função retorna string com complemento ao html especifico do formulario
*
* @return string
* @param string 
*/
function ar_htmlComplemento($param){
    $html = "";
    
    if(get_option('arearestrita_label') == 's'){
        if ($param == 'campo_senhaAuxiliar'){
            $html = '<input type="text" id="senhatxt" class="cmp senha" title="Senha" />';
        }
        
    } else{
        if($param == 'taglabel_login'){
            $html = '<label for="login">Login:</label>';        
        }
        else if($param == 'taglabel_senha'){
            $html = '<label for="senha">Senha:</label>';
        }
        
    }

    
    return $html;
}