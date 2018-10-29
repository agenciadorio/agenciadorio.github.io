<?php 
/*
Plugin Name: Vagas SINE
Plugin URI: http://www.sine.com.br
Description: Exibe as vagas do SINE.
Version: 1.0
Author: Site Nacional de Empregos
Author URI: http://www.sine.com.br
*/

$pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);

// Creating the widget 
	// Creating the widget 
class vagas_sine extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'vagas_sine', 

// Widget name will appear in UI
__('Vagas SINE', 'vagas_sine_domain'), 

// Widget description
array( 'description' => __( 'Mostre vagas do sine no seu site', 'vagas_sine_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$funcao = apply_filters( 'widget_funcao', $instance['funcao'] );
$cidade = apply_filters( 'widget_cidade', $instance['cidade'] );

// This is where you run the code and display the output
 $url = "http://wsprdmobile.sine.com.br/App.svc/PesquisarVagasSine";

    $curl = curl_init();

    $request = array(
    'f' => $funcao,  
    'c' => $cidade,
    'p' => 0
    );

    $data = json_encode($request);

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                                                                  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data))                                                                       
    );

    curl_setopt($curl, CURLOPT_URL, $url); 
     // Set the url path we want to call
    $result = curl_exec($curl);

    $json=json_decode($result,true);

    curl_close($curl);

    $vagas = $json['v']; 

    echo __('<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">', 'vagas_sine_domain' );  

    echo __('<style>
            .developed{     font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
            text-align:center;
            display:block;
            font-size:9px;
            font-weight:normal;
            color:#999;
            margin:10px 0; 
            }
            .box-sidebar{
                background:#f5f5f5;
                border:1px solid #e3e3e3;
                border-radius:4px;
                padding:10px 20px;
            }
            .box-sidebar .block-inner{
                margin:20px 0;
            }
            .box-sidebar  .scroll{
                     overflow:scroll;
                     overflow-x: hidden;
                     height:320px;
                     
            }
            .box-sidebar .center{
                margin:0 auto;
                display:block;
            }
            .box-sidebar hr{
                background:#708839;
                height:2px;
                border:none;
                margin:5px 0px;
            }
            .box-sidebar .btn{
                width:50%;
                background:#334e8a;
                padding:3px 15px;
                color:#fff;
                border-radius:4px;
                    font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
                    text-align:center;
                    font-size:14px;

            }
            .box-sidebar ul{
                list-style:none;
                margin:15px 0;
                padding:0;
            }
            .box-sidebar ul li{
                font-size:12px;
                font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
                margin-bottom:5px;
            }
            .box-sidebar ul li span{ color:#999;}
            .box-sidebar ul i{
                color:#708839;
                margin-right:5px;
                width:20px; 
            }
            .btn{
                text-decoration:none;
            }
            .semlinha{
                text-decoration:none;
            }
            </style>',
             'vagas_sine_domain' ); 


    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή',' ');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η','-');

    echo __('<div class="box-sidebar"> ', 'vagas_sine_domain' );  

    if($cidade == '' && $funcao == ''){
        echo __('<a target="_blank" href="http://www.sine.com.br?utm_source=plugin&utm_medium=imagem_topo&utm_campaign=plugin_wordpress"><img width="60%" src="http://www.sine.com.br/img/sine-sidebar.png" class="center"></a>', 'vagas_sine_domain' );  
    }else if($cidade != '' && $funcao == '') {
        echo __('<a target="_blank" href="http://www.sine.com.br/vagas-empregos-em-'.str_replace("/","-",strtolower(str_replace($a, $b, $cidade))).'?utm_source=plugin&utm_medium=imagem_topo&utm_campaign=plugin_wordpress"><img width="60%" src="http://www.sine.com.br/img/sine-sidebar.png" class="center"></a>', 'vagas_sine_domain' );
    }else if($cidade == '' && $funcao != '') {
        echo __('<a target="_blank" href="http://www.sine.com.br/vagas-empregos/'.strtolower(str_replace($a, $b, $funcao)).'?utm_source=plugin&utm_medium=imagem_topo&utm_campaign=plugin_wordpress"><img width="60%" src="http://www.sine.com.br/img/sine-sidebar.png" class="center"></a>', 'vagas_sine_domain' );
    }else if($cidade != '' && $funcao != '') {
        echo __('<a target="_blank" href="http://www.sine.com.br/vagas-empregos-em-'.str_replace("/","-",strtolower(str_replace($a, $b, $cidade))).'/'.strtolower(str_replace($a, $b, $funcao)).'?utm_source=plugin&utm_medium=imagem_topo&utm_campaign=plugin_wordpress"><img width="60%" src="http://www.sine.com.br/img/sine-sidebar.png" class="center"></a>', 'vagas_sine_domain' );
    }
    echo __('
        <hr>
        <div class="scroll">', 'vagas_sine_domain' );
    foreach ($vagas as $vaga) {
        echo __('<div class="block-inner">
                <ul>', 'vagas_sine_domain' );
        echo __('<li><i class="fa fa-suitcase"></i><span>Função:</span> <strong> '. $vaga['f'].'</strong></li>', 'vagas_sine_domain' );
        echo __('<li><i class="fa fa-map-marker"></i><span>Cidade:</span> <strong>'. $vaga['c'].'</strong></li>', 'vagas_sine_domain' );
        if($vaga['s'] == ''){
            echo __('<li><i class="fa fa-money"></i><span>Salário:</span> <strong> A combinar</strong></li>', 'vagas_sine_domain' );
        }else{
            echo __('<li><i class="fa fa-money"></i><span>Salário:</span> <strong>R$'. $vaga['s'].'</strong></li>', 'vagas_sine_domain' );
        }
        if($vaga['e'] == ''){
            echo __('<li><i class="fa fa-building"></i><span>Empresa:</span> <strong> Confidencial </strong></li>', 'vagas_sine_domain' );
        }else{
            echo __('<li><i class="fa fa-building"></i><span>Empresa:</span> <strong>'. $vaga['e'].'</strong></li>', 'vagas_sine_domain' );
        }
        
        //echo '<h6>Descrição: '. $vaga['de'].'</h6>';
        echo __('</ul><a class="btn center" title="Candidatar a vaga de '. $vaga['f'].' em '. $vaga['c'].'" alt="Candidatar a vaga de '. $vaga['f'].' em '. $vaga['c'].'" target="_blank" href="'.$vaga['u'].'?utm_source=plugin&utm_medium=botao_candidatar&utm_campaign=plugin_wordpress">Candidatar</a>', 'vagas_sine_domain' );
        echo __('</div><hr>', 'vagas_sine_domain' );
    }
    echo __('</div>
            </div>
            <a class="semlinha" title="SINE" alt="SINE" target="_blank" href="http://www.sine.com.br?utm_source=plugin&utm_medium=link_powered_by_sine&utm_campaign=plugin_wordpress"><small class="developed">Powered by SINE</small></a>', 'vagas_sine_domain' );

echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Vagas SINE', 'vagas_sine_domain' );
}
if ( isset( $instance[ 'funcao' ] ) ) {
$funcao = $instance[ 'funcao' ];
}
else {
$funcao = __( '', 'vagas_sine_domain' );
}
if ( isset( $instance[ 'cidade' ] ) ) {
$cidade = $instance[ 'cidade' ];
}
else {
$cidade = __( '', 'vagas_sine_domain' );
}
// Widget admin form
?>
<p>
<div class="input-append">
<label for="<?php echo $this->get_field_id( 'funcao' ); ?>"><?php _e( 'Função:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'funcao' ); ?>" name="<?php echo $this->get_field_name( 'funcao' ); ?>" type="text" value="<?php echo esc_attr( $funcao ); ?>" />
<script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<style>
    .ui-autocomplete {
        padding: 0;
    }

    .ui-autocomplete li {
        list-style-type: none;
        cursor: pointer;
        margin: 0;
        left: 0;
        padding: 6px 4px;
        border: 1px solid #ffffff;
        border-top: none;
        background-color: #ffffff;
    }

    .ui-autocomplete li.ui-state-focus {
        background-color: #ffffff;
    }
    .ui-helper-hidden-accessible {
        display: none;
    }
</style>
<script type="text/javascript">
    var cacheFuncoes = {};
    jQuery("#<?php echo $this->get_field_id( 'funcao' ); ?>").autocomplete({
    minLength: 2,
    delay: 100,
    open: function (event, ui) {
        var ul = jQuery(".ui-autocomplete");
        ul.outerWidth(jQuery("#<?php echo $this->get_field_id( 'funcao' ); ?>").outerWidth());
    },
    change: function (event, ui) {
        if (ui.item == null) {
            jQuery("#hfIdCidadeTopo").val(0);
        } else {
            jQuery("#hfIdCidadeTopo").val(ui.item.id);
        }
    },
    source: function (request, response) {
        var term = request.term;
        if (term in cacheFuncoes) {
            response(cacheFuncoes[term]);
            return;
        }

        jQuery.ajax({
            type: "POST",
            url: "http://www.sine.com.br/ajax.aspx/ListarFuncoesAC",
            data: "{ prefixText: \'"+jQuery("#<?php echo $this->get_field_id( 'funcao' ); ?>").val()+"\'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                cacheFuncoes[term] = jQuery.parseJSON(data.d);
                for (var i in cacheFuncoes[term]) {
                    cacheFuncoes[term][i].value = cacheFuncoes[term][i].descricao;
                }
                response(cacheFuncoes[term]);
            },
        });
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return jQuery("<li>")
          .text(item.descricao)
          .appendTo(ul);
};
</script>

</div>



<div class="input-append">
<label for="<?php echo $this->get_field_id( 'cidade' ); ?>"><?php _e( 'Cidade:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'cidade' ); ?>" name="<?php echo $this->get_field_name( 'cidade' ); ?>" type="text" value="<?php echo esc_attr( $cidade ); ?>" />

<script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<style>
    .ui-autocomplete {
        padding: 0;
    }

    .ui-autocomplete li {
        list-style-type: none;
        cursor: pointer;
        margin: 0;
        left: 0;
        padding: 6px 4px;
        border: 1px solid #ffffff;
        border-top: none;
        background-color: #ffffff;
    }

    .ui-autocomplete li.ui-state-focus {
        background-color: #ffffff;
    }
    .ui-helper-hidden-accessible {
        display: none;
    }
</style>
<script type="text/javascript">
    var cacheCidades = {};
    jQuery("#<?php echo $this->get_field_id( 'cidade' ); ?>").autocomplete({
    minLength: 2,
    delay: 100,
    open: function (event, ui) {
        var ul = jQuery(".ui-autocomplete");
        ul.outerWidth(jQuery("#<?php echo $this->get_field_id( 'cidade' ); ?>").outerWidth());
    },
    change: function (event, ui) {
        if (ui.item == null) {
            jQuery("#hfIdCidadeTopo").val(0);
        } else {
            jQuery("#hfIdCidadeTopo").val(ui.item.id);
        }
    },
    source: function (request, response) {
        var term = request.term;
        if (term in cacheCidades) {
            response(cacheCidades[term]);
            return;
        }

        jQuery.ajax({
            type: "POST",
            url: "http://www.sine.com.br/ajax.aspx/ListarCidadesAC",
            data: "{ prefixText: \'"+jQuery("#<?php echo $this->get_field_id( 'cidade' ); ?>").val()+"\'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                cacheCidades[term] = jQuery.parseJSON(data.d);
                for (var i in cacheCidades[term]) {
                    cacheCidades[term][i].value = cacheCidades[term][i].descricao;
                }
                response(cacheCidades[term]);
            },
        });
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return jQuery("<li>")
          .text(item.descricao)
          .appendTo(ul);
};
</script>

</div>



</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['funcao'] = ( ! empty( $new_instance['funcao'] ) ) ? strip_tags( $new_instance['funcao'] ) : '';
$instance['cidade'] = ( ! empty( $new_instance['cidade'] ) ) ? strip_tags( $new_instance['cidade'] ) : '';
return $instance;
}
} // Class vagas_sine ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'vagas_sine' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
?>