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
ob_start();

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
  add_option( 'Activated_Plugin', 'Plugin-Slug' );
  add_option( 'plugin_stats', 'YES', '', 'yes' );
  
  $table_name = $wpdb->prefix . "language_content";       

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
        
function innometricForm_admin_menu() {
    include('info_form.php');
}
function show_settings_menu() {
	include('info_settings.php'); 
}
//function show_info_menu() {
//	include('email.php');
//}
function show_activate_menu() {
	include('activate_pages.php');
}
function welcome_screen(){
    include ('welcome_screen.php');
}
function Form_admin_actions1() {
        add_menu_page("Innometrics", "Innometrics", 1,__FILE__ , "innometricForm_admin_menu",'../wp-content/plugins/'. PROF_FOLDER .'/images/sidebar_icon_active.png');
        add_submenu_page(__FILE__,'Activate','Activate','8','innometricsactivate','show_activate_menu');
        add_submenu_page(__FILE__,'Settings','Settings','8','innometricssetting','show_settings_menu');   
        add_submenu_page(__FILE__,'','','8','welcome_screen','welcome_screen');
}
		add_action('admin_menu', 'Form_admin_actions1');
                


function myscript() {
$activated_pages = get_option('activated_pages');
$script_activate = 0;
    if(!empty($activated_pages)){
        $activate_data=unserialize($activated_pages);
        $current_page_id = get_the_ID();
        foreach($activate_data as $key => $value){
            if($current_page_id==$value){
                $script_activate=1;
            }
        }
    }
    if ($script_activate==1) {?>
    <script type="text/javascript">
        <?php echo get_option('javascript_code');?>
    </script>
    <?php
    }
}

add_action( 'wp_footer', 'myscript' ,200);

function curl($url,$params = array(),$is_coockie_set = false)
{
 
if(!$is_coockie_set){
/* STEP 1. let's create a cookie file */
$ckfile = tempnam ("/tmp", "CURLCOOKIE");
 
/* STEP 2. visit the homepage to set the cookie properly */
$ch = curl_init ($url);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec ($ch);
}
 
$str = ''; $str_arr= array();
foreach($params as $key => $value)
{
$str_arr[] = urlencode($key)."=".urlencode($value);
}
if(!empty($str_arr))
$str = '?'.implode('&',$str_arr);
 
/* STEP 3. visit cookiepage.php */
 
$Url = $url.$str;
 
$ch = curl_init ($Url);
curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
 
$output = curl_exec ($ch);
return $output;
}

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
