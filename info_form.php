<?php
require_once(ABSPATH .'wp-load.php');
require_once(ABSPATH .'wp-includes/formatting.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );
define('PROF_FOLDER', dirname(plugin_basename(__FILE__)));
global $wpdb;
$code_error = $edit_code = false;
if (array_key_exists('Submit', $_POST)) {
    $javascript_code = array_key_exists('javascript_code', $_POST) ? trim(stripslashes($_POST['javascript_code']) ) : '';
    update_option('javascript_code', $javascript_code);
    if (!$javascript_code) $code_error = true;
}
if (array_key_exists('submit_track', $_POST)) {
    update_option('track', array_key_exists('track', $_POST) ? $_POST['track'] : '');
}
if (array_key_exists('plugin_stats_submit', $_POST)) {
    update_option('plugin_stats', array_key_exists('plugin_stats', $_POST) ? $_POST['plugin_stats'] : '');
    if (array_key_exists('plugin_stats', $_POST) && $_POST['plugin_stats'] == 'yes') send_stats();
}

if(array_key_exists('start_again', $_GET) && !$_POST) {
    delete_option('track');
    delete_option('javascript_code');
    delete_option('plugin_stats');
    delete_option('setup_done');
    $edit_code = true;
}

if (!$_POST && get_option('track') && get_option('javascript_code'))
    update_option('setup_done', 'yes');
else delete_option('setup_done');

$javas_code = get_option('javascript_code');
?>
<div class="wrap">
    <div class="inner_wrap">
        <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
        <h1 class="lowerheader"><?php $welcome = mylang_translate('welcome');print_r($welcome); ?></h1>
        <p>
            <?php echo mylang_translate('plugin_allow'); ?>
            <a href="http://innometrics.kayako.com/"><?php echo mylang_translate('please_contact'); ?></a>
        </p>
        <?php if ($javas_code && !$edit_code) :?>
            <p> <a href="<?php echo admin_url('admin.php?page=im-wpp/innometrics.php&start_again=yes');?>">Start Again?</a> </p>
        <?php endif;?>
        <form name="info_form" method="post" action="" onsubmit="" id="info_form" enctype="multipart/form-data">
            <?php if ($javas_code && !$edit_code) { ?>
                <div class="block_check"><p><?php echo mylang_translate('Code_Provided'); ?> </p></div>
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

        <?php $track = trim(get_option('track'));  $track_n_script = $track && $javas_code && !$edit_code?>
        <div class="<?php echo $track_n_script ? 'block_check' : 'block2'; ?>" id='block2'>
            <?php if($track_n_script) { ?>
                <p><?php echo mylang_translate($track == "track_all" ? 'Now_tracking' : 'start_tracking'); ?></p><br/>
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

        <?php $allow = trim(get_option('plugin_stats'));?>
        <div class="<?php echo (!$track_n_script) ? 'block3' : 'block_check'?>" id='block3'>
            <?php if(!$track_n_script) {?>
                <p><?php $final_step = mylang_translate('final_step');print_r($final_step); ?></p>
            <?php } else {?>
                <p><?php echo mylang_translate('Profile_Cloud'); ?></p>
                <?php if(!$allow) {?>
                    <form name="info_form2" method="post" action="" onsubmit="" id="info_form2" enctype="multipart/form-data">
                        <label>
                            <input id="plugin_stats" type="checkbox" name="plugin_stats" value="yes"
                                <?php echo $allow && $allow == 'yes' ? 'checked="checked"' : '' ?> />
                            <?php echo mylang_translate('Allow_Innometrics'); ?>
                            <a href="#"><?php $sent_do = mylang_translate('sent_do');print_r($sent_do); ?></a>
                        </label>
                        <p class="submit">
                            <input class="submit_button" type="submit" name="plugin_stats_submit" value="<?php echo mylang_translate('save_changes'); ?>" />
                        </p>
                    </form>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

</div>