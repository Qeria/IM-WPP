<?php
if (isset($_POST['Submit'])) {
    update_option('javascript_code', array_key_exists('javascript_code', $_POST) ? trim(stripslashes($_POST['javascript_code']) ) : '');
    update_option('track', array_key_exists('javascript_code', $_POST) && $_POST['track_all_sites'] ? 'track_all' : get_option('track'));
}
?>
<div class="wrap">
    <div class="set_outer">
        <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
        <h1 class="lowerheader"><?php $setting_word = mylang_translate('Settings'); echo $setting_word;?></h1>
        <?php notify_pending_track() ?>
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
                <tr valign="top">
                    <th><?php echo mylang_translate("Tracking_All_Sites")?></th>
                    <td>
                        <p><input id="setting_track_all" type="checkbox" name="track_all_sites" value="track_all" <?php echo get_option('track')=="track_all" ? 'checked="checked"':'';?> />
                            <?php echo mylang_translate('With_this'); ?> <a href="#"><?php echo mylang_translate('for_more'); ?></a></p>
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
</div>