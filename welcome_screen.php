<?php
/**
 * Welcome Page Class
 * @package 	Innometrics
 * @version     1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="welcome_header">
    <div class="header_outer">
        <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
        <?php notify_pending_track() ?>
        <h1><?php $welcome = mylang_translate('welcome');print_r($welcome); ?></h1>

        <div class="about-text">
            <?php
            $welcome_thanx = mylang_translate('welcome_thanx');print_r($welcome_thanx);
            ?>
        </div>
    </div>

    <div class="innometric-badge">
        <img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/wecome_logo.png"/>
        <p id="text" class="image_text">
            <?php
            echo $version = 'Version'.'1.8';
            ?>
        </p>
    </div>
</div>
<p class="innometrics-actions">
    <a href="<?php echo admin_url('admin.php?page=innometricssetting'); ?>" class="button-primary"><?php $setting_word = mylang_translate('Settings');echo $setting_word;?></a>
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.innometrics.com/" data-text="Profile Cloud - Customer Data Management System" data-via="Innometrics" data-size="large" data-hashtags="innometrics">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</p>

<h2 class="nav-tab-wrapper">
    <a class="nav-tab nav-tab-active" id="whats_tab" href=""><?php echo mylang_translate('whats_new'); ?></a>
    <a class="nav-tab" id="credit_tab" href=""><?php echo mylang_translate('Credits'); ?></a>
</h2>

<div class="wrap about-wrap">

    <div class="middle_section">
        <div id="whats_new">
            <div class="left">
                <h4><?php _e( 'More language available' ); ?></h4>
                <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' ); ?></p>
            </div>
            <div class="right">
                <h4><?php _e( 'More language available' ); ?></h4>
                <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' ); ?></p>
            </div>
        </div>
        <div id="credits">
            <div class="left">
                <div class='text'>
                    <h4><?php _e( 'Thank you for all the support' ); ?></h4>
                    <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' ); ?></p>
                </div>
            </div>
            <div class="right">
                <div class='text'>
                    <h4><?php _e( 'Thank you for all the support' ); ?></h4>
                    <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' ); ?></p>
                    <p><?php _e( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English.' ); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php if ( strtotime( '2014/11/04' ) > current_time( 'timestamp' ) ) { ?>
        <div class="welcome_bottom_image">
            <div class="left">
                <h3><?php _e( 'Profile Cloud LondonBreakfast Briefing' ); ?></h3>
                <h4><?php _e( 'Haymarket Hotel - London SW1Y 4HX' ); ?></h4>
                <p><?php echo __( 'Breakfast seminr for maketers and CRM expert looking to re-think how they engage with customers across digital plateforms. Hear about trends, opportunities, and tools, and how you can re-invigorate your existing marketing technologies.' ); ?></p>
                <a href="#" class="image_register"><?php $Register = mylang_translate('Register');print_r($Register); ?></a>
            </div>
            <div class="right">
                <?php $site_url = get_site_url();?>
                <img src="<?php echo $site_url;?>/wp-content/plugins/<?php echo PROF_FOLDER;?>/images/event_day-t.png"/>
            </div>
        </div>
    <?php } ?>


    <div class="return-to-dashboard">
        <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'innometricssetting' ), 'admin.php' ) ) ); ?>"><?php $goto_innometrics = mylang_translate('goto_innometrics');print_r($goto_innometrics); ?></a>
    </div>
</div>


