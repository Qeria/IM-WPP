<?php
/*
Plugin Name: Innometrics WP Plug-in
Description: Innometrics Wordpress Plug-in provides an easy way integration for your website with Profile Cloud.
Version: 1.8
Author: Innometrics
Author URI: http://www.innometrics.com/
Plugin URI: http://www.innometrics.com/resources/
Text Domain: innometrics
*/

define('PROF_FOLDER', dirname(plugin_basename(__FILE__)));
define('PROF_URL', $siteurl.'/wp-content/plugins/' . PROF_FOLDER);
define('PROF_FILE_PATH', dirname(__FILE__));
define('PROF_DIR_NAME', basename(PROF_FILE_PATH));
global $wpdb;
$comp_table_prefix=$wpdb->prefix;
define('PROF_TABLE_PREFIX', $comp_table_prefix);
require_once(ABSPATH .'wp-load.php');
require_once(ABSPATH .'wp-includes/formatting.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );

wp_enqueue_script( 'plugincustom', '/wp-content/plugins/' . PROF_FOLDER .  '/js/plugincustom.js', array());
wp_enqueue_style('my_css_dsslider', '/wp-content/plugins/' . PROF_FOLDER . '/style.css');

function innometrics_plugin_activate() {
    add_option( 'Activated_Plugin', 'Plugin-Slug');
    add_option( 'plugin_stats', 'YES', '', 'yes' );
    /* activation code here */
}
register_activation_hook( __FILE__, 'innometrics_plugin_activate' );

function load_innometrics_plugin() {
    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {
        delete_option( 'Activated_Plugin' );
        wp_redirect( admin_url( 'admin.php?page=welcome_screen' ) );
        exit;
    }
}
add_action( 'admin_init', 'load_innometrics_plugin' );

function load_page() {
    $page = array_key_exists('page', $_GET) ? $_GET['page'] : '';
    switch($page) {
        case 'innometricssetting':
            $name = 'info_settings'; break;
        case 'innometricsactivate':
            $name = 'activate_pages'; break;
        case 'im-wpp/innometrics.php':
            $name = 'info_form';
            if (!(get_option('track') && get_option('javascript_code')))
                break;
        default:
            $name = 'welcome_screen';
    }
    include ($name.'.php');
}

function Form_admin_actions1() {
    add_menu_page("Innometrics WP Integration", "Innometrics", 1,__FILE__ , "load_page",'../wp-content/plugins/'
        . PROF_FOLDER .'/images/sidebar_icon_active.png');
    add_submenu_page(__FILE__,'Activate','Activate','8','innometricsactivate','load_page');
    add_submenu_page(__FILE__,'Settings','Settings','8','innometricssetting', 'load_page');
    add_submenu_page(true,'Innometrics WP Integration','Innometrics','8','welcome_screen','load_page');
}
add_action('admin_menu', 'Form_admin_actions1');

function myscript() {
    if (get_option('track') == 'track_all'
        || in_array(get_the_ID(), array_filter(explode(',', get_option('activated_pages')))))
    {?>
        <script type="text/javascript">
            <?php echo get_option('javascript_code');?>
        </script>
    <?php
    }
}

add_action( 'wp_footer', 'myscript' ,200);

function mylang_translate($key)
{
    $locale = get_locale();
    $lang_data = include("language/{$locale}.php");
    if (!$lang_data) $lang_data = include('language/en_US.php');

    return $lang_data[$key];
}

function notify_pending_track()
{
    (!(get_option('track') && get_option('javascript_code')) && get_option('notify_incomplete'))
        ? pink_notify('pending_notification', (get_current_screen()->id != 'innometrics_page_innometricssetting'
        ? 'pending_notification_button' : '')) : null ;
}

function pink_notify($text_lang = '', $button_lang = '')
{
    $html = '';
    if ($text_lang || $button_lang) {
        $html .= '<div class="updated pink-notice">';
        $html .= '<p>' . mylang_translate($text_lang) . '</p>';
        $html .= $button_lang ? '<p class="submit"><a href="'.admin_url('admin.php?page=innometricssetting').'" class="button-primary">'
            . mylang_translate($button_lang) . '</a></p>' : '';
        $html .= '</div>';
    }
    echo $html;
}
