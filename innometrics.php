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
$js_code = get_option('javascript_code');
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
    if($script_activate==1){
    ?>
    <script type="text/javascript">
        <?php echo $js_code;?>
    </script>
    <?php
    }
}
add_action( 'wp_footer', 'myscript' ,200);

//
//function admin_in_english_add_hooks() {
//	add_filter( 'locale', 'admin_in_english_locale' );
//}
//add_action( 'plugins_loaded', 'admin_in_english_add_hooks' );
//
//function admin_in_english_locale( $locale ) {
//	if ( admin_in_english_should_use_english() ) {
//		return 'sv_SE';
//	}
//	return $locale;
//}
//function admin_in_english_should_use_english() {
//	// frontend AJAX calls are mistakend for admin calls, because the endpoint is wp-admin/admin-ajax.php
//	return admin_in_english_is_admin() && !admin_in_english_is_frontend_ajax();
//}
//
//function admin_in_english_is_admin() {
//	return
//		is_admin() || admin_in_english_is_tiny_mce() || admin_in_english_is_login_page();
//}
//
//function admin_in_english_is_frontend_ajax() {
//	return defined( 'DOING_AJAX' ) && DOING_AJAX && false === strpos( wp_get_referer(), '/wp-admin/' );
//}
//
//function admin_in_english_is_tiny_mce() {
//	return false !== strpos( $_SERVER['REQUEST_URI'], '/wp-includes/js/tinymce/');
//}
//
//function admin_in_english_is_login_page() {
//	return false !== strpos( $_SERVER['REQUEST_URI'], '/wp-login.php' );
//}



 
function curl($url,$params = array(),$is_coockie_set = false)
{
 
if(!$is_coockie_set){
/* STEP 1. let’s create a cookie file */
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
 
function Translate1($word)
{
$word = urlencode($word);
$cur_local = get_locale();
$name_en = explode('_',$cur_local);

$url = 'http://translate.google.com/translate_a/t?client=t&text='.$word.'&hl=en&sl=en&tl='.$name_en['0'].'&ie=UTF-8&oe=UTF-8&multires=1&otf=1&ssel=3&tsel=3&sc=1';

 
$name_en = curl($url);

$name_en = explode('"',$name_en);
return  $name_en[1];
}



function mylang_translate($word)
{
    global $wpdb;
    $cur_local = get_locale();
//    $setting_codeline = $wpdb->get_results( "SELECT * FROM `wp_language_data` WHERE `name`='".$word."'");
//    $val = unserialize($setting_codeline['0']->language_array) ;
//    $text = $val[$cur_local];
//        if(!empty($text)){
//            return $text;
//        }
//        else{
//            $text = $val['en_US'];
//            return $text;
//        }
        
    if($cur_local=='fr_FR'){
        $fetch_record = include('language/fr_FR.php');
        return $fetch_record[$word];
    }
    elseif($cur_local=='da_DK'){
        $fetch_record = include('language/da_DK.php');
        return $fetch_record[$word];
    }
    elseif($cur_local=='de_DE'){
        $fetch_record = include('language/de_DE.php');
        return $fetch_record[$word];
    }    
    elseif($cur_local=='es_ES'){
        $fetch_record = include('language/es_ES.php');
        return $fetch_record[$word];
    } 
    elseif($cur_local=='fi'){
        $fetch_record = include('language/fi.php');
        return $fetch_record[$word];
    }  
    elseif($cur_local=='nl_NL'){
        $fetch_record = include('language/nl_NL.php');
        return $fetch_record[$word];
    }      
    elseif($cur_local=='no'){
        $fetch_record = include('language/no.php');
        return $fetch_record[$word];
    } 
    elseif($cur_local=='pt_BR'){
        $fetch_record = include('language/pt_BR.php');
        return $fetch_record[$word];
    }  
    elseif($cur_local=='sv_SE'){
        $fetch_record = include('language/sv_SE.php');
        return $fetch_record[$word];
    }    
    else{
        $fetch_record = include('language/en_US.php');
        return $fetch_record[$word];
    }   
}
