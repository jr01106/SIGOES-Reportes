<?php /*
class validacionTitulo 
{
public function __construct()
{
	add_action('admin_footer',Array($this,'validacionTitulo'),100);
}
function validacionTitulo() { 
 global $wpdb;
 $Palabras = $wpdb->get_results( "SELECT mala_palabra FROM diccionario_malas_palabras", ARRAY_A);
 $i = 0;
 foreach ($Palabras As $Datos){
 $json[$i] = $Datos[mala_palabra];
 $i++;
 }
 ?>
<script>
jQuery(document).ready(function() {
// publish button validation
var $malas_palabras = <?php echo json_encode($json);?>;
					
	// title en blanco -->publicar
	jQuery('#publish').click(function(){
		$title_value = jQuery.trim(jQuery('#title').val());
		if($title_value == 0 && $title_value != " "){
			alert('Por favor inserte un titulo');
			jQuery('.spinner').css("visibility", "hidden");
			jQuery('#title').focus();
			return false;
		}
	});
	
	// malas palabras  -->publicar
	jQuery('#publish').click(function(){
		$my_title = jQuery.trim(jQuery('#title').val());
		$no_malas_palabras = $my_title.toLowerCase();		// convertir a minuscula
		for (var i = 0; i < $malas_palabras.length; i++) {
			//if(/calvo/.test(cadena))
			if($no_malas_palabras.indexOf($malas_palabras[i]) != -1){		// busca un match con malas_palabras[n]
				alert('Por favor introdusca un titulo sin esta palabra: ' + $malas_palabras[i]);
				jQuery('.spinner').css("visibility", "hidden");
				jQuery('#title').focus();
				return false;
			}	
		}
	});
	// caracteres especiales  -->publicar
	jQuery('#publish').click(function(){
		$letra_title = jQuery.trim(jQuery('#title').val()); 
		for (var i = 0; i < $letra_title.length; i++) { // .inArray busca match en $letra_title.charAt letra por letra
			if(jQuery.inArray($letra_title.charAt(i), ["@","#","$","%","/","&","*","^","{","}","[","]","+","<",">"])>=0){
				alert('Por favor inserte un titulo sin caracteres especiales: '+$letra_title.charAt(i));
				jQuery('.spinner').css("visibility", "hidden");
				jQuery('#title').focus();
				return false;
			}
		}

	});
	// draft button validation --> Borrador
	jQuery('#save-post').click(function(){
		$title_value = jQuery.trim(jQuery('#title').val());
		if($title_value == 0 && $title_value != " "){
			alert('Por favor inserte un titulo');
			jQuery('.spinner').css("visibility", "hidden");
			jQuery('#title').focus();
			return false;
		}
	});
	//
	// caracteres especiales --> Borrador
	jQuery('#save-post').click(function(){
		$letra_title = jQuery.trim(jQuery('#title').val());
		for (var i = 0; i < $letra_title.length; i++) {
			if(jQuery.inArray($letra_title.charAt(i), ["@","#","$","%","/","&","*","^","{","}","[","]","+","<",">"])>=0){
				alert('Por favor inserte un titulo sin caracteres especiales: '+$letra_title.charAt(i));
				jQuery('.spinner').css("visibility", "hidden");
				jQuery('#title').focus();
				return false;
			}
		}

	});
	// malas palabras --> Borrador
	jQuery('#save-post').click(function(){
		$my_title = jQuery.trim(jQuery('#title').val());
		$no_malas_palabras = $my_title.toLowerCase();		// convertir a minuscula
		for (var i = 0; i < $malas_palabras.length; i++) {
			//if(/calvo/.test(cadena))
			if($no_malas_palabras.indexOf($malas_palabras[i]) != -1){		// busca un match con malas_palabras[n]
				alert('Por favor introdusca un titulo sin esta palabra: ' + $malas_palabras[i]);
				jQuery('.spinner').css("visibility", "hidden");
				jQuery('#title').focus();
				return false;
			}	
		}
	});
});
</script>
<?php
  }
}
$iniciar = new validacionTitulo();
?>

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */

class reporteSigoes extends WP_List_Table {  

/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Titulo', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Titulos', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );
	}
} // fin class reporteSigoes
$tabla_actual = new reporteSigoes();