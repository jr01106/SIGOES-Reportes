<?php 


///////////////////////////////////////////////////////////////////////////////////////////
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

    class FT_WP_Table extends WP_List_Table
    {

        private $order;
        private $orderby;
        private $posts_per_page = 25;

        public function __construct()
        {
            parent :: __construct(array(
                'singular' => "ftraveler",
                'plural' => "ftraveler",
                'ajax' => true
            ));

            $this->set_order();
            $this->set_orderby();
            $this->prepare_items();
            $this->display();
        }

        private function get_sql_results()
        {
            global $wpdb;
/*SELECT 
    ID,post_title,
    post_author,
    (SELECT meta_value FROM wp_usermeta WHERE user_id = post_author AND meta_key = 'first_name') nombre,
    (SELECT meta_value FROM wp_usermeta WHERE user_id = post_author AND meta_key = 'last_name') apellido,
    (SELECT 
    (CASE meta_value
WHEN 'a:1:{s:13:"administrator";b:1;}' THEN 'Administrador' 
WHEN 'a:1:{s:21:"gestionador_proyectos";b:1;}' THEN 'Gestionador Proyectos' 
WHEN 'a:1:{s:22:"gestionador_coyuntural";b:1;}' THEN 'Gestionador Coyuntural' 
WHEN 'a:1:{s:22:"gestionador_streaming";b:1;}' THEN 'Gestionador Streaming' 
END)  AS rol 
     
    FROM wp_usermeta WHERE user_id = post_author AND meta_key = 'wp_capabilities') rol,

    post_type,post_status,post_date
    FROM  wp_posts; 
*/
            $args = array('ID','post_title','post_type','post_status','post_author','Nombre_Autor','Rol','post_date' );// agregar columnas propias 
            $sql_select = implode(', ', $args);
            //$sql_results = $wpdb->get_results("SELECT " . $sql_select . " FROM " . $wpdb->posts);
            $sql_results = $wpdb->get_results(
"SELECT ID,
        post_title, 
        post_type,  
        post_status,
        post_author,
(SELECT meta_value FROM wp_usermeta WHERE user_id = post_author AND meta_key = 'first_name') Nombre_Autor,
(SELECT meta_value FROM wp_usermeta WHERE user_id = post_author AND meta_key = 'last_name') Apellido_Autor,
(SELECT CASE meta_value 
WHEN 'a:1:{s:13:"administrator";b:1;}'          THEN 'Administrador' 
WHEN 'a:1:{s:21:"gestionador_proyectos";b:1;}'  THEN 'Gestionador Proyectos' 
WHEN 'a:1:{s:22:"gestionador_coyuntural";b:1;}' THEN 'Gestionador Coyuntural' 
WHEN 'a:1:{s:22:"gestionador_streaming";b:1;}'  THEN 'Gestionador Streaming' 
END AS Rol_Autor 
FROM  wp_usermeta WHERE user_id = post_author AND meta_key = 'wp_capabilities') Rol_Autor,            
post_date 
FROM  wp_posts" );


            return $sql_results;
        }

        public function set_order()
        {
            $order = 'DESC';
            if (isset($_GET['order']) AND $_GET['order'])
                    $order = $_GET['order'];
            $this->order = esc_sql($order);
        }

        public function set_orderby()
        {
            $orderby = 'create_date';
            if (isset($_GET['orderby']) AND $_GET['orderby'])
                    $orderby = $_GET['orderby'];
            $this->orderby = esc_sql($orderby);
        }

        /**
         * @see WP_List_Table::ajax_user_can()
         */
        public function ajax_user_can()
        {
            return current_user_can('edit_posts');
        }

        /**
         * @see WP_List_Table::no_items()
         */
        public function no_items()
        {
            _e('No se encontraron Posts');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views()
        {
            return array();
        }


        /**
         * @see WP_List_Table::get_columns()
         */
        public function get_columns()
        {
            $columns = array(  
                'ID' => __('ID'),
                'post_title' => __('Titulo'),
                'post_type' => __('Categoria'),
                'post_status' => __('Estado'),
                'post_author' => __('ID_Autor'),  
                'Nombre_Autor' => __('Nombre_Autor'), 
                'Apellido_Autor' =>__('Apellido_Autor'),
                'Rol_Autor' =>__('Rol_Autor'),
                'post_date' => __('Fecha_Creacion')
            );
            return $columns;
        }

        /**
         * @see WP_List_Table::get_sortable_columns()
         */
        public function get_sortable_columns()
        {
            $sortable = array(
                'ID' => array('ID', true),
                'Titulo' => array('post_title', true),
                'Categoria' => array('categoria', true),
                'post_status' => array('Estado', true),
                'ID_Autor' => array('post_author', true),  
                'Nombre_Autor' => array('Nombre_Autor', true), 
                'Apellido_Autor' => array('Apellido_Autor', true),
                'Rol_Autor' => array('Rol_Autor', true),
                'Fecha_Creacion' => array('fecha_creado', true)
            );
            return $sortable;
        }

        /**
         * Prepare data for display
         * @see WP_List_Table::prepare_items()
         */
        public function prepare_items()
        {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array(
                $columns,
                $hidden,
                $sortable
            );

            // SQL results
            $posts = $this->get_sql_results();
            $mi_sql_result = $posts;
            empty($posts) AND $posts = array();

            # >>>> Pagination
            $per_page = $this->posts_per_page;
            $current_page = $this->get_pagenum();
            $total_items = count($posts);
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ));
            $last_post = $current_page * $per_page;
            $first_post = $last_post - $per_page + 1;
            $last_post > $total_items AND $last_post = $total_items;

            // Setup the range of keys/indizes that contain 
            // the posts on the currently displayed page(d).
            // Flip keys with values as the range outputs the range in the values.
            $range = array_flip(range($first_post - 1, $last_post - 1, 1));

            // Filter out the posts we're not displaying on the current page.
            $posts_array = array_intersect_key($posts, $range);
            # <<<< Pagination
            // Prepare the data
            $permalink = __('Edit:');
            foreach ($posts_array as $key => $post) {
                $link = get_edit_post_link($post->ID);
                $no_title = __('Sin titulo');
                $title = !$post->post_title ? "<em>{$no_title}</em>" : $post->post_title;
                $posts[$key]->post_title = "<a title='{$permalink} {$title}' href='{$link}'>{$title}</a>";
                //$Nombre_Autor_Actual = __(the_author_meta('first_name')); // the_author_firstname($post->post_author)
                //$post->post_author != 0 ? "<em>{$Nombre_Autor_Actual}</em>" : $post->post_author;
            }
            $this->items = $posts_array;
        }

        /**
         * A single column
         */
        public function column_default($item, $column_name)
        {
            return $item->$column_name;
        }

        /**
         * Override of table nav to avoid breaking with bulk actions & according nonce field
         */
        public function display_tablenav($which)
        {

            ?>
            <div class="tablenav <?php echo esc_attr($which); ?>">
                <!-- 
                <div class="alignleft actions">
                <?php # $this->bulk_actions( $which );    ?>
                </div>
                -->
                <?php
                $this->extra_tablenav($which);
                $this->pagination($which);

                ?>
                <br class="clear" />
            </div>
            <?php
        }

        /**
         * Disables the views for 'side' context as there's not enough free space in the UI
         * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
         * 
         * @see WP_List_Table::extra_tablenav()
         */
        public function extra_tablenav($which)
        {
            global $wp_meta_boxes;
            $views = $this->get_views();
            if (empty($views)) return;

            $this->views();
        }

    }

}

//$$numero_de_col = WP_List_Table::get_column_count();
//$numero_de_pag = WP_List_Table::get_items_per_page();
// $fp = fopen( Ruta.'/controller/fichero1.csv' , "w+" ); 

 //for(i=0;i<=$numero_de_col;i++) {     //fputcsv( $fp, $valor, ";" );    }



//$output = stream_get_contents( $fp );
     //fclose($fp);

/*
get_column_count — Return number of visible columns
get_column_info — Get a list of all, hidden and sortable columns, with filter applied
get_columns — Get a list of columns. The format is: 'internal-name' => 'Title' 
*/

?>
