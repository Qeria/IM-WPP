<?php
require_once(ABSPATH .'wp-load.php');
require_once(ABSPATH .'wp-includes/formatting.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );
define('PROF_FOLDER', dirname(plugin_basename(__FILE__)));
global $wpdb;
$code_error = false;
if (array_key_exists('Submit', $_POST)) {
    $javascript_code = array_key_exists('javascript_code', $_POST) ? trim(stripslashes($_POST['javascript_code']) ) : '';
    update_option('javascript_code', $javascript_code);
    if (!$javascript_code) $code_error = true;
}
if (array_key_exists('submit_track', $_POST)) {
    update_option('track', array_key_exists('track', $_POST) ? $_POST['track'] : '');
}

if(array_key_exists('start_again', $_GET)) {
    delete_option('track');
    delete_option('javascript_code');
}
$edit_code = array_key_exists('edit', $_GET) && !$_POST;
?>
<div class="wrap">
	<div class="inner_wrap">
    <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
    <h1 class="lowerheader"><?php $welcome = mylang_translate('welcome');print_r($welcome); ?></h1>
    <p>
        <?php echo mylang_translate('plugin_allow'); ?>
        <a href="http://innometrics.kayako.com/"><?php echo mylang_translate('please_contact'); ?></a>
    </p>
    <form name="info_form" method="post" action="" onsubmit="" id="info_form" enctype="multipart/form-data">
        <?php $javas_code = get_option('javascript_code');
        if ($javas_code && !$edit_code) { ?>
            <div class="block_check"><p><?php echo mylang_translate('Code_Provided'); ?> <a href="<?php echo admin_url('admin.php?page=im-wpp/innometrics.php&edit=edit');?>">Edit</a> </p></div>
        <?php } else { ?>
            <div class="block1">
                <p><?php $main_paste = mylang_translate('main_paste');echo $main_paste . " "; ?><a href="http://innometrics.kayako.com/Knowledgebase/Article/View/4/4/how-to-implement-data-capture-on-my-website"><?php $instructions_here = mylang_translate('instructions_here');print_r($instructions_here); ?></a></p><br/>
                <div class="code_js_out">
                    <textarea rows="6" cols="80" class="javascript_code_main <?php echo $code_error ? 'error_msg' : ''; ?>" name="javascript_code" ><?php echo $javas_code; ?></textarea>
                    <div class="<?php echo $code_error ? 'error_text' : ''; ?>" style="display: none;">* <?php $Code_required = mylang_translate('Code_required');print_r($Code_required); ?></div>
                </div>
                <p class="submit">
                    <input class="submit_button" type="submit" name="Submit" value="<?php $save_changes = mylang_translate('save_changes');print_r($save_changes); ?>" />
                </p>
            </div>
        <?php } ?>
		</form>

        <?php $track = trim(get_option('track'));  $finish = $track && $javas_code && !$edit_code?>
        <div class="<?php echo $finish ? 'block_check' : 'block2'; ?>" id='block2'>
            <?php if($finish) { ?>
                <p><?php echo mylang_translate($track == "track_all" ? 'Now_tracking' : 'start_tracking'); ?>
                    <a href="#"><?php echo mylang_translate('sent_do');?></a></p><br/>
            <?php } else { ?>
                <p><?php echo mylang_translate('instal_it');?></p><br/>
                <?php if($javas_code) { ?>
                <div class="block2_radio" id='block2_radio'>
                    <form name="info_form1" method="post" action="" onsubmit="" id="info_form1" enctype="multipart/form-data">
                        <label>
                            <input id="track_all" type="radio" name="track" value="track_all"
                                <?php echo $track && $track == 'track_all' ? 'checked="checked"' : '' ?> />
                            <?php echo mylang_translate('all_tracking'); ?>
                        </label> <br/>
                        <label>
                            <input id="track_some" type="radio" name="track" value="track_some"
                                <?php echo $track && $track != 'track_all' ? 'checked="checked"' : '' ?> />
                            <?php echo mylang_translate('some_tracking'); ?>
                        </label> <br/>
                        <p class="submit">
                            <input class="submit_button" type="submit" name="submit_track" value="<?php echo mylang_translate('save_changes'); ?>" />
                        </p>
                    </form>
                </div>
            <?php } ?>
            <?php } ?>
        </div>
        <br /><br /><br />
        <br /><br /><br />
        <p class="description"><?php echo mylang_translate('tracking_notice'); ?></p>
	 </div>

</div>
<?php 
function email_data(){
    $site_url = get_site_url();
    $my_theme = wp_get_theme();
    $reflectionObject = new ReflectionObject($my_theme);
    $property = $reflectionObject->getProperty('headers');
    $property->setAccessible(true);
    $items = $property->getValue($my_theme);
    $theme_name = $items['Name'];
    $theme_Version = $items['Version'];
    $theme_AuthorURI = $items['AuthorURI'];    
    $html .= '<table class=widefat>
    <thead>
	<tr>
            <th>Variable Name:</th>
	    <th>Value</th>
	</tr>
    </thead>
    <tbody>
	<tr>
            <td>OS:</td>
	    <td>'.PHP_OS.'</td>
	</tr>
	<tr>
	    <td>Site Url:</td>';
$html .= '<td>'.$site_url.'</td></tr>
        <tr>
            <td>Home Url:</td>
            <td>'.home_url().'</td>
	</tr>

        <tr>
            <td>WC Version:</td>
            <td>'.get_bloginfo( 'version' ).'</td>
	</tr>
        <tr>
            <td>Web Server info:</td>
            <td>'.$_SERVER["SERVER_SOFTWARE"].'</td>
	</tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="2">Theme</th>
            </tr>
        </thead>
        <tbody>
            <tr>
		<td>Theme Name:</td>
		<td>'.$theme_name.'</td>
            </tr>                                
            <tr>
		<td>Theme Version:</td>
		<td>'.$theme_Version.'</td>
            </tr> 
            <tr>
		<td>Author URI:</td>
		<td>'.$theme_AuthorURI.'</td>
            </tr>
        </tbody>
            <thead>
                <tr>
                    <th colspan="2">Plugins</th>
                </tr>
            </thead>    
            <tbody>                                
		<tr>
		<td>Installed Plugins:</td>
                <td><div class="plugins_data">';
					$siteplugins = get_plugins( $plugin_folder );
                                        foreach($siteplugins as $siteplugins){
					$pluginname = $siteplugins['Name'];
					$PluginURI = $siteplugins['PluginURI'];
					$Version = $siteplugins['Version'];
					$AuthorName = $siteplugins['AuthorName'];
					$AuthorURI = $siteplugins['AuthorURI'];
					$html .= '<a href="'.$PluginURI.'">'.$pluginname.'</a> by <a href="'.$AuthorURI.'">'.$AuthorName.'</a> version '.$Version.'<br/>';
				}
    $html .=' </td>
            </tr>
            </tbody>
</table>';
return $html;
}    
