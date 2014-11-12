<?php
//ob_start();
require_once(ABSPATH .'wp-load.php');
require_once(ABSPATH .'wp-includes/formatting.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );
define('PROF_FOLDER', dirname(plugin_basename(__FILE__)));
global $wpdb;
$locale = get_locale();
if (isset($_POST['Submit'])) {
    $javascript_code = stripslashes($_POST['javascript_code']);
    $plugin_stats = array_key_exists('plugin_stats', $_POST) ? trim($_POST['plugin_stats']) : 'no';
    $track_all_sites = array_key_exists('track_all_sites', $_POST) ? trim($_POST['track_all_sites']) : '';
    $track_all_site = ($track_all_sites == 'YES') ? 'track_all' : '';

    if ($plugin_stats == 'YES') {
        $plugin_stat = 'YES';
        $to = "alexandre@connecty.com";
        $subject= "Innometrics Added";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: '."admin@innometrics.com". "\r\n";
        $headers .= 'Cc:  '. "\r\n";
        $headers .= 'Bcc: '. "\r\n";
        mail($to, $subject, email_data2(), $headers);
    }

    $bv = get_option('javascript_code');
    if($bv!=FALSE || trim($bv)=="")
        update_option( 'javascript_code', $javascript_code, '', 'yes' );
    else
    {
        if(trim($bv)=="")
            update_option( 'javascript_code', $javascript_code, '', 'yes' );
        else
        {
            delete_option( 'javascript_code');
            add_option( 'javascript_code', $javascript_code, '', 'yes' );
        }
    }

    $bv2 = get_option('track');
    if($bv2!=FALSE || trim($bv2)=="")
        update_option( 'track', $track_all_site , '', 'yes' );
    else
    {
        if(trim($bv2)=="")
            update_option( 'track', $track_all_site , '', 'yes' );
        else
        {
            delete_option( 'track');
            add_option( 'track', $track_all_site, '', 'yes' );
        }
    }

    $bv3 = get_option('plugin_stats');
    if($bv3!=FALSE || trim($bv3)=="")
        update_option( 'plugin_stats', $plugin_stat , '', 'yes' );
    else
    {
        if(trim($bv3)=="")
            update_option( 'plugin_stats', $plugin_stat , '', 'yes' );
        else
        {
            delete_option( 'plugin_stats');
            add_option( 'plugin_stats', $plugin_stat, '', 'yes' );
        }
    }
}

$page = array_key_exists('tab', $_GET) && $_GET['tab'] == 'status' ? 'status' : 'settings';
?>
<div class="wrap">

    <div class="set_outer">

        <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
        <h1 class="lowerheader"><?php $setting_word = mylang_translate('Settings'); echo $setting_word;?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url( admin_url('admin.php?page=innometricssetting') ); ?>"
               class="nav-tab <?php echo $page == 'settings' ? 'nav-tab-active' : '';?>"> <?php echo mylang_translate('general'); ?></a>
            <a href="<?php echo esc_url( admin_url('admin.php?page=innometricssetting&tab=status') ); ?>"
               class="nav-tab <?php echo $page == 'status' ? 'nav-tab-active' : '';?>">
                <?php $system_status = mylang_translate("system_status"); echo $system_status;?></a>
        </div>
        <?php if($page == 'settings') { ?>
        <div class="setting_div" id="setting_div">
            <form name="setting_form" method="post" action="" onsubmit="" id="setting_form" enctype="multipart/form-data">
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th><?php echo mylang_translate("update_code")?></th>
                            <td>
                                <p><?php echo mylang_translate("Setting_code_line")?>
                                <a href="#"><?php echo mylang_translate('instructions_here');?></a></p>
                                <br />
                                <textarea rows="6" cols="80" class="javascript_code" name="javascript_code"><?php echo get_option('javascript_code'); ?></textarea>
                            </td>
                        </tr>

                        <?php if(get_option('track')=='track_all'){ ?>
                            <tr valign="top">
                                <th><?php echo mylang_translate("Tracking_All_Sites")?></th>
                                <td>
                                    <p><input id="setting_track_all" type="checkbox" name="track_all_sites" value="YES" <?php echo get_option('track')=="track_all" ? 'checked="checked"':'';?> />
                                    <?php echo mylang_translate('With_this'); ?> <a href="#"><?php echo mylang_translate('for_more'); ?></a></p>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr valign="top">
                            <th><?php echo mylang_translate("Plug_in_stats")?></th>
                            <td>
                                <p><input id="plugin_stats" type="checkbox" name="plugin_stats" value="YES" <?php echo get_option('plugin_stats')=="YES" ? 'checked="checked"':'';?> />
                                <?php echo mylang_translate('pluginstat_line'); ?> <a href="#"><?php echo mylang_translate('pluginstat_info'); ?></a></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th></th>
                            <td>
                                <input class="button button-large button-primary" type="submit" name="Submit" value="<?php echo mylang_translate('save_changes'); ?>" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <?php } else { ?>
        <div class="system_status_div">

            <!--div class="updated pink-notice">
                <p><?php //echo mylang_translate('sys_stats_notice');?>:</p>
                <p class="submit"><a href="javascript:void(0);" class="button-primary"><?php //echo mylang_translate('get_stats_report');?></a></p>
            </div-->

            <?php
            $site_url = get_site_url();
            $my_theme = wp_get_theme();
            $reflectionObject = new ReflectionObject($my_theme);
            $property = $reflectionObject->getProperty('headers');
            $property->setAccessible(true);
            $items = $property->getValue($my_theme);
            $theme_name = $items['Name'];
            $theme_Version = $items['Version'];
            $theme_AuthorURI = $items['AuthorURI'];

            ?>
            <table class="status_table widefat">
                <thead>
                <tr>
                    <th style="width: 30%;"><?php echo mylang_translate('environment'); ?>:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php _e('OS'); ?>:</td>
                    <td><?php echo PHP_OS; ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php echo Translate1('Site Url');?>:</td>
                    <td><?php echo $site_url; ?></td>
                </tr>
                <tr>
                    <td><?php echo Translate1('Home Url'); ?>:</td>
                    <td><?php echo home_url(); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php _e('MYSQL'); ?>:</td>
                    <td><?php if ( function_exists( 'mysql_get_server_info' ) ) echo esc_html( mysql_get_server_info() );?></td>
                </tr>
                <tr>
                    <td><?php $Web_Version = mylang_translate('Web_Version'); print_r($Web_Version); ?>:</td>
                    <td><?php echo get_bloginfo( 'version' ); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php $Web_Server = mylang_translate('Web_Server'); print_r($Web_Server); ?>:</td>
                    <td><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td>
                </tr>
                <tr>
                    <td><?php $WP_Version = mylang_translate('WP_Version'); print_r($WP_Version); ?>:</td>
                    <td><?php bloginfo('version'); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php $PHP_Version = mylang_translate('PHP_Version'); print_r($PHP_Version); ?>:</td>
                    <td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
                </tr>
                <tr>
                    <td><?php $multisite = mylang_translate('multisite'); print_r($multisite); ?>:</td>
                    <td><?php if ( is_multisite() ) echo __( 'Yes'); else echo __( 'No'); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php $memory_limit = mylang_translate('memory_limit'); print_r($memory_limit); ?>:</td>
                    <td><?php $memory = WP_MEMORY_LIMIT;
                        echo size_format( $memory );
                        ?></td>
                </tr>
                <tr>
                    <td><?php $Debug_Mode = mylang_translate('Debug_Mode'); print_r($Debug_Mode); ?>:</td>
                    <td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo __( 'Yes'); else echo __( 'No'); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php $wp_language = mylang_translate('wp_language'); print_r($wp_language); ?>:</td>
                    <td><?php echo get_bloginfo('language'); ?></td>
                </tr>

                <?php if ( function_exists( 'ini_get' ) ) : ?>
                    <tr>
                        <td><?php $php_time = mylang_translate('php_time'); print_r($php_time); ?>:</td>
                        <td><?php echo ini_get('max_execution_time'); ?></td>
                    </tr>
                    <tr class="alternate">
                        <td><?php $php_max_input = mylang_translate('php_max_input'); print_r($php_max_input); ?>:</td>
                        <td><?php echo ini_get('max_input_vars'); ?></td>
                    </tr>
                    <tr>
                        <td><?php $SUHOSIN = mylang_translate('SUHOSIN'); print_r($SUHOSIN); ?>:</td>
                        <td><?php echo extension_loaded( 'suhosin' ) ? __( 'Yes') : __( 'No'); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
                <thead>
                <tr>
                    <th colspan="2"><?php $Code_text = mylang_translate('Code_text'); print_r($Code_text);?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php print_r($Code_text); ?>:</td>
                    <td><?php $js_code = get_option('javascript_code');
                        $textareaData = '<li>'.str_replace(array("\r","\n\n","\n"),array('',"\n","</li>\n<li>"),trim($js_code,"\n\r")).'</li>';
                        ?><div class="code_text"> <?php
                            echo '<ul>'.$textareaData.'</ul>'; ?> </div></td>
                </tr>
                </tbody>
                <thead>
                <tr>
                    <th colspan="2"><?php echo Translate1('Themes'); ?>:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php $Theme_Name = mylang_translate('Theme_Name'); print_r($Theme_Name);?>:</td>
                    <td><?php echo $theme_name; ?></td><br/>
                </tr>
                <tr class="alternate">
                    <td><?php $Theme_Version = mylang_translate('Theme_Version'); print_r($Theme_Version); ?>:</td>
                    <td><?php echo $theme_Version; ?></td>
                </tr>
                <tr>
                    <td><?php $Author_URI = mylang_translate('Author_URI'); print_r($Author_URI); ?>:</td>
                    <td><?php echo $theme_AuthorURI; ?></td>

                </tr>
                </tbody>
                <thead>
                <tr>
                    <th colspan="2"><?php echo Translate1('Plugins'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php $Installed_Plugins = mylang_translate('Installed_Plugins'); print_r($Installed_Plugins); ?>:</td>
                    <td><div class="plugins_data"><?php $siteplugins = get_plugins();
                        $no_of_plugins=0;
                        foreach($siteplugins as $siteplugins){
                            $no_of_plugins = $no_of_plugins + 1;
                            $pluginname = $siteplugins['Name'];
                            $PluginURI = $siteplugins['PluginURI'];
                            $Version = $siteplugins['Version'];
                            $AuthorName = $siteplugins['AuthorName'];
                            $AuthorURI = $siteplugins['AuthorURI'];
                            echo '<a href="'.$PluginURI.'">'.$pluginname.'</a> by <a href="'.$AuthorURI.'">'.$AuthorName.'</a> version '.$Version.'<br/>';
                        } ?></td>

                </tr>
                <tr class="alternate">
                    <td><?php $no_of_plugins_text = mylang_translate('no_of_plugins'); print_r($no_of_plugins_text); ?>:</td>
                    <td><?php echo $no_of_plugins; ?></td>
                </tr>
                </tbody>

                <thead>
                <tr>
                    <th colspan="2"><?php echo $setting_word ?></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td><?php echo Translate1('Force SSL'); ?>:</td>
                    <td><?php echo get_option( 'woocommerce_force_ssl_checkout' ) === 'yes' ? ''.__( 'Yes').'' : ''.__( 'No').''; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
        <?php } ?>

    </div>
<?php
function woocommerce_scan_template_files( $template_path ) {
    $files         = scandir( $template_path );
    $result        = array();
    if ( $files ) {
        foreach ( $files as $key => $value ) {
            if ( ! in_array( $value, array( ".",".." ) ) ) {
                if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
                    $sub_files = woocommerce_scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
                    foreach ( $sub_files as $sub_file ) {
                        $result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
                    }
                } else {
                    $result[] = $value;
                }
            }
        }
    }
    return $result;
}

function email_data2(){
    $site_url = get_site_url();
    $my_theme = wp_get_theme();
    $reflectionObject = new ReflectionObject($my_theme);
    $property = $reflectionObject->getProperty('headers');
    $property->setAccessible(true);
    $items = $property->getValue($my_theme);
    $theme_name = $items['Name'];
    $theme_Version = $items['Version'];
    $theme_AuthorURI = $items['AuthorURI'];
    if ( function_exists( 'mysql_get_server_info' ) )
    { $mysql = esc_html( mysql_get_server_info() );
    }
    if ( function_exists( 'phpversion' ) )
    { $phpver = esc_html( phpversion() );}
    if ( is_multisite() ){$multi = 'Yes';} else {$multi = 'No';}

    $memory = WP_MEMORY_LIMIT;
    $mem_size = size_format( $memory );

    if ( defined('WP_DEBUG') && WP_DEBUG ) {$debug = 'Yes';} else {$debug = 'No';}
    $suhosin_res = extension_loaded( 'suhosin' ) ? __( 'Yes') : __( 'No');

    $html = '
<html><head></head>
<body>
<table class=widefat>
    <thead>
    <tr>
        <th>Variable Name:</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>OS:</td> <td>'.PHP_OS.'</td>
    </tr>
    <tr>
        <td>Site Url:</td>';
    $html .= '<td>'.$site_url.'</td></tr>
    <tr>
        <td>Home Url:</td> <td>'.home_url().'</td>
    </tr>
    <tr>
        <td>MYSQL:</td> <td>'.$mysql.'</td>
    </tr>
    <tr>
        <td>WC Version:</td> <td>'.get_bloginfo( 'version' ).'</td>
    </tr>
    <tr>
        <td>Web Server info:</td> <td>'.$_SERVER["SERVER_SOFTWARE"].'</td>
    </tr>
    <tr>
        <td>WP Version:</td> <td>'.bloginfo('version').'</td>
    </tr>
    <tr>
        <td>PHP Version:</td> <td>'.$phpver.'</td>
    </tr>
    <tr>
        <td>WP Multisite Enabled:</td> <td>'.$multi.'</td>
    </tr>
    <tr>
        <td>WP Memory Limit:</td> <td>'.$mem_size.'</td>
    </tr>
    <tr>
        <td>WP Language:</td> <td>'.get_bloginfo('language').'</td>
    </tr>
    <tr>
        <td>WP Debug Mode:</td> <td>'.$debug.'</td>
    </tr>
    <tr>
        <td>WP Debug Mode:</td> <td>'.ini_get('max_execution_time').'</td>
    </tr>
    <tr>
        <td>PHP Max Input Vars:</td> <td>'.ini_get('max_input_vars').'</td>
    </tr>
    <tr>
        <td>SUHOSIN Installed:</td> <td>'.$suhosin_res.'</td>
    </tr>
    </tbody>
    <thead>
    <tr>
        <th colspan="2">Theme</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Theme Name:</td> <td>'.$theme_name.'</td>
    </tr>
    <tr>
        <td>Theme Version:</td> <td>'.$theme_Version.'</td>
    </tr>
    <tr>
        <td>Author URI:</td> <td>'.$theme_AuthorURI.'</td>
    </tr>
    </tbody>
    <thead>
    <tr>
        <th colspan="2">Plugins</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Installed Plugins:</td> <td><div class="plugins_data">';
    $siteplugins = get_plugins();
    foreach ($siteplugins as $siteplugins) {
        $pluginname = $siteplugins['Name'];
        $PluginURI = $siteplugins['PluginURI'];
        $Version = $siteplugins['Version'];
        $AuthorName = $siteplugins['AuthorName'];
        $AuthorURI = $siteplugins['AuthorURI'];
        $html .= '<a href="'.$PluginURI.'">'.$pluginname.'</a> by <a href="'.$AuthorURI.'">'.$AuthorName.'</a> version '.$Version.'<br/>';
    }

    $html .=' </td></tr></tbody></table></body></html>';
    //echo $html;
    return $html;
}
