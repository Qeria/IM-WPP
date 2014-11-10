<?php 
ob_start();
	require_once(ABSPATH .'wp-load.php');
	require_once(ABSPATH .'wp-includes/formatting.php');
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	define('PROF_FOLDER', dirname(plugin_basename(__FILE__)));
	global $wpdb;
        $error_msg='';
	if (isset($_POST['Submit'])){
            $error_msg='';
$javascript_code=stripslashes($_POST['javascript_code']);
    if(!empty($javascript_code)){
            $bv = get_option('javascript_code');
            if($bv!=FALSE || trim($bv)=="")
            {
                update_option( 'javascript_code', $javascript_code, '', 'yes' );
            }
            else
            {
               if(trim($bv)=="")
               {
                  update_option( 'javascript_code', $javascript_code, '', 'yes' ); 
               }
               else
               {
               delete_option( 'javascript_code'); 
               add_option( 'javascript_code', $javascript_code, '', 'yes' );
               }
            }    
    }
    else{
        $error_msg='error_msg';
        $error_text='error_text';
    }
}
if (isset($_POST['submit_track'])){
		$track=$_POST['track'];
                if($track=='track_all'){
                    $latestposts = $wpdb->get_results( "SELECT * FROM `wp_posts` WHERE `post_type` IN ('page','post') AND `post_status`='publish' ORDER BY ID ASC");
                    foreach ($latestposts as $latestposts){
                        $act[] = $latestposts->ID;
                    }
                    $serialize_activated_pages = serialize($act);

                        $bv7 = get_option('activated_pages');
                        if($bv7!=FALSE || trim($bv7)=="")
                        {
                            update_option( 'activated_pages', $serialize_activated_pages, '', 'yes' );
                        }
                        else
                        {
                           if(trim($bv7)=="")
                           {
                              update_option( 'activated_pages', $serialize_activated_pages, '', 'yes' ); 
                           }
                           else
                           {
                           delete_option( 'activated_pages'); 
                           add_option( 'activated_pages', $serialize_activated_pages, '', 'yes' );
                           }
                        }
                }
		$bv1 = get_option('track');
			if($bv1!=FALSE || trim($bv1)=="")
			{
				update_option( 'track', $track, '', 'yes' );
			}
			else
			{
			   if(trim($bv1)=="")
			   {
				  update_option( 'track', $track, '', 'yes' ); 
			   }
			   else
			   {
			   delete_option( 'track'); 
			   add_option( 'track', $track, '', 'yes' );
			   }
			}
}
if (isset($_POST['plugin_stats_submit'])){
		$plugin_stats=$_POST['plugin_stats'];
		add_option( 'plugin_stats_first', 'yes', '', 'yes' );
	if($plugin_stats=='YES'){
	$plugin_stat = 'YES';
                    $txt= email_data();
            $to = "alexandre@connecty.com";
            $subject= "Innometrics Added";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: '."admin@innometrics.com". "\r\n";
    $headers .= 'Cc: '. "\r\n";
    $headers .= 'Bcc: '. "\r\n";

            wp_mail($to,$subject,$txt,$headers);
         
	}else{
	$plugin_stat = 'NO';
	}
	$bv3 = get_option('plugin_stats');
        if($bv3!=FALSE || trim($bv3)=="")
        {
            update_option( 'plugin_stats', $plugin_stat , '', 'yes' );
        }
        else
        {
           if(trim($bv3)=="")
           {
              update_option( 'plugin_stats', $plugin_stat , '', 'yes' ); 
           }
           else
           {
           delete_option( 'plugin_stats'); 
           add_option( 'plugin_stats', $plugin_stat, '', 'yes' );
           }
        }
        
        $plugin_bv3 = get_option('start_again');
        if($plugin_bv3!=FALSE || trim($plugin_bv3)=="")
        {
            update_option( 'start_again', 'yes' , '', 'yes' );
        }
        else
        {
           if(trim($plugin_bv3)=="")
           {
              update_option( 'start_again', 'yes' , '', 'yes' ); 
           }
           else
           {
           delete_option( 'start_again'); 
           add_option( 'start_again', 'yes', '', 'yes' );
           }
        }

}
if(isset($_POST['start_again'])){
    delete_option( 'plugin_stats');
    delete_option( 'track');
    delete_option( 'javascript_code');
    delete_option( 'plugin_stats_first');
//    update_option( 'start_again', 'no' , '', 'yes' ); 
    $plugin_bv1 = get_option('start_again');
        if($plugin_bv1!=FALSE || trim($plugin_bv1)=="")
        {
            update_option( 'start_again', 'no' , '', 'yes' );
        }
        else
        {
           if(trim($plugin_bv1)=="")
           {
              update_option( 'start_again', 'no' , '', 'yes' ); 
           }
           else
           {
           delete_option( 'start_again'); 
           add_option( 'start_again', 'no', '', 'yes' );
           }
        }
}

?>
<div class="wrap">
	<div class="inner_wrap">	
    <form name="info_form" method="post" action="" onsubmit="" id="info_form" enctype="multipart/form-data">  
        <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
			<div class="lowerheader"><?php $welcome = mylang_translate('welcome');print_r($welcome); ?></div>
				<div class="bottom_header">
                                    <?php $plugin_allow = mylang_translate('plugin_allow');print_r($plugin_allow); ?>
                                    <a href="http://innometrics.kayako.com/"><?php $please_contact = mylang_translate('please_contact');print_r($please_contact); ?></a></div>
			<?php	
                        $start_again_form=get_option('start_again');
                        $start_again_val = trim($start_again_form);
                        if($start_again_val=='yes'){
                            ?><form name="start_again" method="post" action="" onsubmit="" id="start_again" enctype="multipart/form-data">  
                                <button class="start_again_button" name="start_again" type="submit" value=""><?php $Start_again = mylang_translate('Start_again');print_r($Start_again); ?></button>
                            </form>
                        
                               <?php   } 
				$javas_code = get_option('javascript_code');
				if(!empty($javas_code)){ ?>
					<div class="block_check"><p><?php $Code_Provided = mylang_translate('Code_Provided');print_r($Code_Provided); ?></p>
					</div>
				<?php } 
				else{ ?>
					<div class="block1">
					<p><?php $main_paste = mylang_translate('main_paste');echo $main_paste." "; ?><a href="http://innometrics.kayako.com/Knowledgebase/Article/View/4/4/how-to-implement-data-capture-on-my-website"><?php $instructions_here = mylang_translate('instructions_here');print_r($instructions_here); ?></a></p><br/>
					<div class="code_js_out">
                                            <textarea rows="6" cols="80" class="javascript_code_main <?php echo $error_msg; ?>" name="javascript_code" ><?php echo $javascript_code; ?></textarea>  
                                            <div class="<?php echo $error_text ?>" style="display: none;">* <?php $Code_required = mylang_translate('Code_required');print_r($Code_required); ?></div>				
                                        </div>
						 <p class="submit">  
						<input class="submit_button" type="submit" name="Submit" value="<?php $save_changes = mylang_translate('save_changes');print_r($save_changes); ?>" />  
						</p>  
					</div>
				<?php }
				?>
		</form> 
				
				<?php
                                $id='';
                                $bv2='';
                                $bv2 = trim(get_option('track'));
                                
                                if(!empty($bv2)){
                                   
                                    $id="block_check";
                                }else{$id="block2";}
                                ?> 
                                <div class="<?php echo $id; ?>" id='block2'>
				<?php
                                    if($bv2=="track_all"){?>
				<p><?php $Now_tracking = mylang_translate('Now_tracking');print_r($Now_tracking); ?></p><br/>
				<?php }
				elseif($bv2=="track_some"){	
				?>
				<p><?php $start_tracking = mylang_translate('start_tracking');print_r($start_tracking); ?></p><br/>
				<?php } else{?>
				<p><?php $instal_it = mylang_translate('instal_it');print_r($instal_it); ?></p><br/>
					<?php 
					}
						if(!empty($javas_code)){ 
                                                    if(!empty($bv2)){
                                                    }
                                                    else{
                                                    ?>
						<div class="block2_radio" id='block2_radio'>
							<form name="info_form1" method="post" action="" onsubmit="" id="info_form1" enctype="multipart/form-data"> 
							
							<input id="check_track" type="hidden" name="check_track" value="<?php echo $bv2; ?>" ></input>
								<input id="track_all" type="radio" name="track" value="track_all" ><?php $all_tracking = mylang_translate('all_tracking');print_r($all_tracking); ?></input><br/>
								<input id="track_some" type="radio" name="track" value="track_some" ><?php $some_tracking = mylang_translate('some_tracking');print_r($some_tracking); ?></input><br/>
							
								<p class="submit">  
								<input class="submit_button" type="submit" name="submit_track" value="<?php $save_changes = mylang_translate('save_changes');print_r($save_changes); ?>" />  
								</p>  
							</form> 
						</div>
						
					<?php } }
						?>
				</div>
                                <?php $bv3='';
                                $bv3 = get_option('plugin_stats_first');
                                if(!empty($bv3)){
                                    $block3="block_check";
                                }else{$block3="block3";}
                                ?>
				<div class="<?php echo $block3; ?>" id='block3'>
				<?php if(!empty($bv2)){?>
				<form name="info_form2" method="post" action="" onsubmit="" id="info_form2" enctype="multipart/form-data"> 
					<p><?php $Profile_Cloud = mylang_translate('Profile_Cloud');print_r($Profile_Cloud); ?><a href="#"><?php $sent_do = mylang_translate('sent_do');print_r($sent_do); ?></a></p>
						<?php $bv3 = get_option('plugin_stats'); 
							if($bv3=="YES"){
							$check = 'Checked';}
							else{$check = '';}?>
					<input id="plugin_stats" type="checkbox" name="plugin_stats" value="YES" <?php echo $check;?>><?php $Allow_Innometrics = mylang_translate('Allow_Innometrics');echo $Allow_Innometrics." "; ?><a href="#"><?php $sent_do = mylang_translate('sent_do');print_r($sent_do); ?></a></input><br/>
					<p class="submit">  
						<input class="submit_button" type="submit" name="plugin_stats_submit" value="<?php $save_changes = mylang_translate('save_changes');print_r($save_changes); ?>" />  
					</p> 
				</form>	
				<?php 
				}
				else{
				?>
				<p><?php $final_step = mylang_translate('final_step');print_r($final_step); ?></p>
				<?php } ?>
				</div>
		
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
