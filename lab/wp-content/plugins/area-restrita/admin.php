<?php
    $msg = '';

    if($_POST){
        $dados = str_replace("\\", "", $_POST);

        update_option('arearestrita_label', $dados['label']);
        update_option('arearestrita_textoenviar', $dados['txtenviar']);
        update_option('arearestrita_css', $dados['css']);
        update_option('arearestrita_labelcss', $dados['csslabel']);

        $msg = '<div class="updated" id="message"><p>Dados atualizados com sucesso!</p></div>';

    }
    
    $label = get_option('arearestrita_label');


?>
    <style>
       #icon-dados-imobiliaria{ background: transparent url(<?php echo plugin_dir_url( __FILE__ ).'icon_b.png'; ?>) no-repeat; }
    </style>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        function mostraCampoCssLabel(){
            if ( (jQuery('input[name="label"]:checked').val() == 's')) {
                jQuery('.csslabel').show();
            } else {
                jQuery('.csslabel').hide();
            }
            
        }
        mostraCampoCssLabel();

        jQuery('input[name="label"]').change(function() {
          mostraCampoCssLabel();
        });
        
    });
</script>
    <div class="wrap">
        <?php screen_icon(); ?>

        <h2>&Aacute;rea Restrita</h2>
        <?php echo $msg; ?>
        
        <form method="POST" action="">
            
            <h3>Opções</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"> <label for="tipo">Mostrar a label dentro do campo? </label> </th>
                    <td>
                        <input type="radio" name="label" value="s" <?php if($label=='s') {echo 'checked="checked"';} ?> /> Sim <br />
                        <input type="radio" name="label" value="n" <?php if($label == 'n') {echo 'checked="checked"';} ?> /> Não
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="txtenviar"> Texto para o botão de envio: </label> </th>
                    <td> 
                        <input type="text" id="frase" name="txtenviar" class="regular-text" value="<?php echo get_option('arearestrita_textoenviar'); ?>" /> 
                    </td>
                </tr>
            </table>
            
            <h3>Personalização</h3>
            <table class="form-table">
                <tr valign="top" colspan="2">
                    <th scope="row"> <label for="css"> CSS pra o formulário:</label> </th>
                    <td> <textarea name="css" rows="8" cols="100"> <?php echo get_option('arearestrita_css'); ?></textarea> 
                    </td>
                </tr>
            </table>
            
            <table class="form-table csslabel">
                <tr valign="top" colspan="2">
                    <th scope="row"> <label for="csslabel"> CSS específico para label dentro do campo:</label> </th>
                    <td> <textarea name="csslabel" rows="8" cols="100"> <?php echo get_option('arearestrita_labelcss'); ?></textarea> 
                    </td>
                </tr>
            </table>
                
        
            <table class="form-table">
                <tr valign="top" colspan="2">
                    <td>
                        <input type="submit" name="Submit" class="button-primary" value="Salvar altera&ccedil;&otilde;es" /> 
                    </td>
                </tr>
            </table>
            
        </form>
    </div>

        <h3>Documentação</h3>
        Abaixo alguns comentários e instruções válidos para a utilização desse plugin.
        
        <h4>&bull; Usabilidade</h4>
        Pode ser implementada das seguintes formas:
        <ul>
            <li>- <strong>Widget</strong></li>
            <li>- <strong>Tag PHP</strong>: <br />
                <em> &lsaquo;?php if (function_exists('mostrarAreaRestrita')){ echo mostrarAreaRestrita() } ?&rsaquo; </em> <br />
            </li>
        </ul>
        <span class="description">
            Em todas as formas não há configurações extras a serem feitas diretamente nelas.<br />
            Todas as configuração deverão ser feitas nessa página ou na página de configuração do plugin <strong>"Dados da Imobiliária"</strong>.
        </span>
        
        <h4>&bull; Personalização</h4>
        <p>Para formatar visualmente o plugin, altere o css no campo indicado. <br />
        Abaixo código html exemplo de um gerado pelo plugin:</p>
        <div class="description">
            &lsaquo;div id="arearestrita"&rsaquo;<br />
            &nbsp;&lsaquo;form target="_top" action="http://{apelido}.wsrun.net/php/sistema.php" name="formulario" method="post" id="flogin"&rsaquo;<br />
            &nbsp;&nbsp;&lsaquo;input type="hidden" value="Frame_Parceiro" name="action" /&rsaquo;<br />
            &nbsp;&nbsp;&lsaquo;input type="hidden" value="{codigo}" name="codigo" /&rsaquo;<br />
            &nbsp;&nbsp;&lsaquo;input type="text" autocomplete="off" value="" id="login" name="login" title="Login" class="cmp login" /&rsaquo;<br />
            &nbsp;&nbsp;&lsaquo;input type="password" autocomplete="off" id="senha" name="senha" class="cmp senha" /&rsaquo;<br />
            &nbsp;&nbsp;<span style="color:blue;">&lsaquo;input type="text" id="senhatxt" class="cmp senha" title="Senha" /&rsaquo;</span><br />
            &nbsp;&nbsp;&lsaquo;script language="JavaScript"&rsaquo;<br />
            &nbsp;&nbsp;&nbsp;function abrejanela(link) {<br />
            &nbsp;&nbsp;&nbsp;&nbsp;var msgWindow;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;msgWindow=window.open(link,'Senha','resizable=no,width=500,height=500,dependent=yes,scrollbars=yes');<br />
            &nbsp;&nbsp;&nbsp;}<br />
            &nbsp;&nbsp;&lsaquo;/script&rsaquo;<br />
            &nbsp;&nbsp;&lsaquo;div class="fpw"&rsaquo;&lsaquo;a onclick="abrejanela('http://{apelido}.wsrun.net/php/email.php?action=email_senha&Codigo_Parceiro={codigo}');" href="#"&rsaquo;Esqueceu sua senha?&lsaquo;/a&rsaquo;&lsaquo;/div&rsaquo;<br />
            &nbsp;&nbsp;&lsaquo;input type="submit" name="ok" id="ok" class="ok" value="Enviar" /&rsaquo;<br />
            &nbsp;&lsaquo;/form&rsaquo;<br />
            &lsaquo;/div&rsaquo;<br />
        </div>
        <p> OBS 1: O ítem em azul é um campo máscara que serve apenas para mostrar a label "Senha" dentro do campo de senha. <br />
            Ele deve ficar sobreposto com position:absolute ao campo real de senha. <br />
        </p>
        <p> OBS 2: Dependendo das configurações feitas nesta página, o html poderá mudar. <br />
            Para ter certeza de como o html está sendo gerado pelo plugin, ao implementar no tema, veja o código fonte.
        </p>
        
        <h4>&bull; Importante</h4>
        - Esse plugin não irá funcionar caso o plugin <strong>"Dados da Imobiliária"</strong> não esteja devidamente instalado e configurado.<br />
        - Não tente instalar mais de um plugin de Área Restrita no mesmo wordpress.