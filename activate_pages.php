<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Activate_List_Table extends WP_List_Table {

    public $total_items = 0;
    public $total_pages = 0;
    public $total_posts = 0;
    public $activated_pages = array();

    function __construct() {
        parent::__construct( array(
            'singular'  => __( 'page', 'page' ),     //singular name of the listed records
            'plural'    => __( 'pages', 'page' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?

        ) );

        add_action( 'admin_head', array( &$this, 'admin_header' ) );
        $this->activated_pages = activated_pages();
    }

    function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'my_list_test' != $page )
            return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 5%; }';
        echo '.wp-list-table .column-pagetitle { width: 40%; }';
        echo '.wp-list-table .column-author { width: 35%; }';
        echo '.wp-list-table .column-isbn { width: 20%;}';
        echo '</style>';
    }

    function no_items() {
        _e( 'No pages found.' );
    }

    function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'post_title':
                return '<strong><a href="' . $item['guid'] . '">' . $item[$column_name] . ' (' . $item['post_type'] . ')</a></strong><br />'
                . '<span style="opacity:0.6;">ID: ' . $item['ID'] . '</span>';
            case 'status':
                return '<span class="'.($this->is_tracked($item['ID'])?'yes-tracking':'').'">'.($this->is_tracked($item['ID'])?'':'Not').' Tracking <span class="bull-icon"></span></span>';
            case 'date':
                return date('Y/m/d', strtotime($item[ $column_name ]));
            case 'action':
                return '<a href="" class="toggle_tracking '.($this->is_tracked($item['ID'])?'stop':'start').'">'.($this->is_tracked($item['ID'])?'Stop':'Start').' Tracking</a>';
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function is_tracked($id) {
        return in_array($id, $this->activated_pages);
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'post_title'  => array('item',false),
            'status'   => array('status',false),
            'date' => array('date',false)
        );
        return $sortable_columns;
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'post_title' => __( 'Post/Page', 'page' ),
            'status'    => __( 'Status', 'page' ),
            'date'      => __( 'Last Modified', 'page' ),
            'action'      => __( 'Actions', 'page' )
        );
        return $columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'start'    => 'Start Tracking',
            'stop'    => 'Stop Tracking'
        );
        return $actions;
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="ids[]" value="%s" />', $item['ID']
        );
    }

    function prepare_items() {
        $args = array(
            'post_type' => array('post', 'page'),
            'perm' => 'readable'
        );
        //Sets per page
        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option');
        $per_page = get_user_meta($user, $option, true);
        if (empty($per_page) || $per_page < 1)
            $per_page = $screen->get_option( 'per_page', 'default' );

        $args['posts_per_page'] = $per_page;
        $args['paged'] = (array_key_exists('paged', $_GET) && $_GET['paged']) ? $_GET['paged'] : 1;
        if (array_key_exists('s', $_GET) && $_GET['s']) $args['s'] =  $_GET['s'];
        if (array_key_exists('type', $_GET) && $_GET['type']) $args['post_type'] =  $_GET['type'];
        //$args['post_type'] =  'post';

        $the_query = new WP_Query($args);
        if ( $the_query->have_posts() ) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $this->items[] =  array(
                    'ID' => $the_query->post->ID,
                    'post_title' => $the_query->post->post_name,
                    'status' => $the_query->post->post_status,
                    'guid' => $the_query->post->guid,
                    'post_type' => $the_query->post->post_type,
                    'date' => $the_query->post->post_modified
                );
            }
        }
        /* Restore original Post Data */
        wp_reset_postdata();

        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        //$per_page = 5;
        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option');
        $per_page = get_user_meta($user, $option, true);
        if (empty($per_page) || $per_page < 1)
            $per_page = $screen->get_option( 'per_page', 'default' );

        $this->total_items = $the_query->found_posts;

        $this->set_pagination_args(array(
            'total_items' => $this->total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page                     //WE have to determine how many items to show on a page
        ));

        $all_query = new WP_Query('post_type[]=page&post_type[]=post&perm=readable');
        $this->total_items = $all_query->found_posts;
        $page_query = new WP_Query('post_type=page&perm=readable');
        $this->total_pages = $page_query->found_posts;
        $post_query = new WP_Query('post_type=post&perm=readable');
        $this->total_posts = $post_query->found_posts;
    }
} //class

$activate = new Activate_List_Table();
$to_do = $activate->current_action();
if ($to_do && isset($_REQUEST['ids'])) {
    activated_pages($_REQUEST['ids'], $to_do == 'stop');
    wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce', 'action', 'action2'), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
    exit;
}
elseif (!empty($_GET['_wp_http_referer'])) {
    wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce', 'action', 'action2'), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
    exit;
}

$activate->prepare_items();
?>
<div class="wrap">
    <h2>
        <?php echo mylang_translate('Activate_Tracking');?>
    </h2>
    <p><?php echo mylang_translate('Activate_innometrics');?> </p>
    <?php notify_pending_track() ?>
    <ul class="subsubsub">
        <li><a href="?page=<?php echo $_GET['page']?>" class="<?php echo (!array_key_exists('type', $_GET) && (!array_key_exists('s', $_GET) ||  !$_GET['s']))?'current':''?>">All <span class="count">(<?php echo $activate->total_items?>)</span></a> |</li>
        <li><a href="?page=<?php echo $_GET['page']?>&type=page" class="<?php echo (array_key_exists('type', $_GET) && $_GET['type']=='page')?'current':''?>">Pages <span class="count">(<?php echo $activate->total_pages?>)</span></a> |</li>
        <li><a href="?page=<?php echo $_GET['page']?>&type=post" class="<?php echo (array_key_exists('type', $_GET) && $_GET['type']=='post')?'current':''?>">Posts <span class="count">(<?php echo $activate->total_posts?>)</span></a></li>
        <?php if (array_key_exists('s', $_GET) && $_GET['s']): ?><li><em class="subtitle">Search results for "<?php echo $_GET['s']?>"</em></li><?php endif; ?>
    </ul>

    <form method="get">
        <input type="hidden" name="page" value="innometricsactivate">
        <input type="hidden" name="_wpnonce" value="1">
        <input type="hidden" name="_wp_http_referer" value="1">
        <?php
        $activate->search_box( 'search', 'search_id' );
        $activate->display(); ?>
    </form>
</div>