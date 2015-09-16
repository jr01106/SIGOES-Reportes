<?php
/*
Plugin Name: SIGOES-Reportes
Plugin URI: http://modulos.egob.sv
Description: Plugin para la implementacion de modulos de proyectos, eventos coyunturales y transmision de streaming
Version: 1.0
Author: Equipo de desarrollo SIGOES
Author URI: http://modulos.egob.sv
Text Domain: SIGOES-Reportes
 */
//Variable que almacena el nombre del directorio del plugin
if(!defined('Ruta')){ 
    define('Ruta', plugin_dir_path(__FILE__));
}

//Ruta relatica del Plugin SIGOES-Comunicados
//define('SIGOES_PLUGIN_DIR',WP_CONTENT_URL.'/plugins/');
define('SIGOES_PLUGIN_DIR', plugin_dir_path(__FILE__));
//require_once(SIGOES_PLUGIN_DIR.'/controller/ProyectoController.php');
//require_once(SIGOES_PLUGIN_DIR.'/controller/EventoController.php');
//require_once(SIGOES_PLUGIN_DIR.'/controller/StreamingController.php');
require_once(SIGOES_PLUGIN_DIR.'/controller/reporteSigoes.php'); //

function Activar_Reporte_Sigoes()
{
    add_menu_page('Reporte SIGOES', 'Reporte SIGOES', 'activate_plugins', 'Reporte_SIOGOES', 'ft_list');
}

add_action('admin_menu', 'Activar_Reporte_Sigoes');


function ft_list()
{
    echo '<div class="wrap"><h2>'. __('Reporte SIGOES') .'</h2>';
    $ftList = new FT_WP_Table();
    echo '</div>';
}
/*function ft_list()
{
    $ftList = new FT_WP_Table();
    echo '</pre><div class="wrap"><h2>'. __('Listing Data') .'</h2>';
    $ftList->prepare_items();
    $ftList->display();
    echo '</div>';
}

/*add_action('','register_wp_my_plugin');
function register_wp_my_plugin(){
   
    register_widget('reporteSigoes');
}
?>