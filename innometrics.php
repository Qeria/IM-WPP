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
define('PROF_TABLE_PREFIX', $wpdb->prefix);
require_once(ABSPATH .'wp-load.php');
require_once(ABSPATH .'wp-includes/formatting.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );

wp_enqueue_script( 'plugincustom', '/wp-content/plugins/' . PROF_FOLDER .  '/js/plugincustom.js', array());
wp_enqueue_style('my_css_dsslider', '/wp-content/plugins/' . PROF_FOLDER . '/style.css');

add_action( 'wp_footer', 'myscript' ,200);
add_action('admin_menu', 'Form_admin_actions1');
add_filter('set-screen-option', 'activate_set_option', 10, 3);

//register_deactivation_hook( __FILE__, 'innometrics_plugin_deactivation');
register_uninstall_hook(    __FILE__, 'innometrics_plugin_uninstall');

function innometrics_plugin_uninstall() {
    if (get_option('plugin_stats') == 'yes') send_stats('uninstall');

    delete_option('stats_sent');
    delete_option('javascript_code');
    delete_option('track');
    delete_option('activated_pages');
    delete_option('setup_done');
    delete_option('plugin_stats');
}

function load_page()
{
    $page = array_key_exists('page', $_GET) ? $_GET['page'] : '';
    switch($page) {
        case 'innometricssetting':
            $name = 'info_settings'; break;
        case 'innometricsactivate':
            $name = 'activate_pages'; break;
        case 'im-wpp/innometrics.php':
            $name = 'info_form';
            if (!(get_option('track') && get_option('javascript_code') && get_option('setup_done'))
                || array_key_exists('edit', $_GET))
                break;
        default:
            $name = 'welcome_screen';
    }
    include ($name.'.php');
}

function Form_admin_actions1()
{
    add_menu_page("Innometrics WP Integration", "Innometrics", 1,__FILE__ , "load_page",'../wp-content/plugins/'
        . PROF_FOLDER .'/images/sidebar_icon_active.png');
    $hook = add_submenu_page(__FILE__,'Activate','Activate','8','innometricsactivate','load_page');
    add_action("load-$hook", 'activate_screen_option');
    add_submenu_page(__FILE__,'Settings','Settings','8','innometricssetting', 'load_page');
    add_submenu_page(true,'Innometrics WP Integration','Innometrics','8','welcome_screen','load_page');
}

function activate_screen_option()
{
    add_screen_option('per_page', array(
        'label' => 'Items',
        'default' => 4,
        'option' => 'innometrics_activate_row_limit'
    ));
}

function activate_set_option($status, $option, $value)
{
    if ( 'innometrics_activate_row_limit' == $option ) return $value;

    return $status;
}

function mylang_translate($key)
{
    $locale = get_locale();
    $lang_data = include("language/{$locale}.php");
    if (!$lang_data) $lang_data = include('language/en_US.php');

    return array_key_exists($key, $lang_data) ? $lang_data[$key] : $key;
}

function notify_pending_track()
{
    $notify = false;
    if (!(get_option('javascript_code') && get_option('setup_done'))) $notify = true;

    if (get_option('track') == 'track_all' || !(get_option('track') == 'track_some' && count(activated_pages())))
        $notify = true;

    $notify ? pink_notify('pending_notification', (get_current_screen()->id != 'innometrics_page_innometricssetting'
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

function activated_pages($set = array(), $remove = false)
{
    if ($set) {
        if (!is_array($set)) $set = array($set);
        $array = $remove ? array_diff(activated_pages(), $set) : array_unique(array_merge(activated_pages(), $set));
        update_option('activated_pages', implode(',', $array));
    }

    return array_filter(array_map('trim', explode(',', get_option('activated_pages'))), 'is_numeric');
}

function myscript()
{
    if (get_option('track') == 'track_all' || in_array(get_the_ID(), activated_pages())) {?>
        <script type="text/javascript">
            <?php echo get_the_ID() . get_option('javascript_code');?>
        </script>
    <?php
    }
}

function send_stats($state = 'install')
{
    if (get_option('plugin_stats') == 'yes') {
        if ($state == 'install' && intval(get_option('stats_sent')) > 0) return;
        update_option('stats_sent', intval(get_option('stats_sent')) + 1);
        ob_start();
        include PROF_FILE_PATH.'/includes/system_stats.php';
        $email = ob_get_clean();
        //echo($email);die;
        $to = 'apinheiro@qeria.com';
        $subject = "Innometrics WordPress plugin {$state}";
        $headers = "From: " . get_option('admin_email') . "\r\n";
        $headers .= "Reply-To: ". get_option('admin_email') . "\r\n";
        $headers .= "CC: coolandsolo@yahoo.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $email, $headers);
    }
}
