<?php
    global $wpdb;

    if (isset($_POST['activatepages_apply'])|| isset($_POST['activatepages_apply2'])){
        $set='';
        $actionpages = $_POST['activatepages_checkbox'];

         if($_POST['activatepages_apply'])
             {$set='bulk_action1';}
         else{$set='bulk_action2';}
        $action = $_POST[$set];
        
        /***When action apply for Start Tracking***/
        if($action=='start_tracking'){
            $activated_pages = get_option('activated_pages');
            if($activated_pages==FALSE){
                $serialize_activated_pages = serialize($actionpages);
                add_option( 'activated_pages', $serialize_activated_pages, '', 'yes' );
            }
            else{
                $activate_data=unserialize($activated_pages);
                $activate_data = array_merge($activate_data,$actionpages);
                $activate_unique_data = array_unique($activate_data);
                $serialize_activated_pages = serialize($activate_unique_data);
                
                if(trim($activated_pages)=="")
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
        /***When action apply for Stop Tracking***/
        elseif($action=='stop_tracking'){
            $activated_pages = get_option('activated_pages');
            if($activated_pages==FALSE){
            }
            else{
                $activate_data=unserialize($activated_pages);
                $new_array = array_diff($activate_data, $actionpages);
                $serialize_activated_pages = serialize($new_array);
                
                if(trim($activated_pages)=="")
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

        
        $activated_pages1 = get_option('activated_pages');
        $act_page = unserialize($activated_pages1); 
    }   
    elseif(isset($_POST['start_submit'])){
        $action_singlepage = array($_POST['start_submit']);
        $activated_pages = get_option('activated_pages');

            if($activated_pages==FALSE){
                $serialize_activated_pages = serialize($action_singlepage);
                add_option( 'activated_pages', $serialize_activated_pages, '', 'yes' );
            }
            else{
                $activate_data=unserialize($activated_pages);
                $activate_data = array_merge($activate_data,$action_singlepage);
                $activate_unique_data = array_unique($activate_data);
                $serialize_activated_pages = serialize($activate_unique_data);
                
                if(trim($activated_pages)=="")
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
    elseif(isset($_POST['stop_submit'])){
        $action_singlepage = array($_POST['stop_submit']);
        $activated_pages = get_option('activated_pages');
        
            if($activated_pages==FALSE){
            }
            else{
                $activate_data=unserialize($activated_pages);
                $new_array = array_diff($activate_data, $action_singlepage);
                $serialize_activated_pages = serialize($new_array);
                
                if(trim($activated_pages)=="")
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
    else{
        $activated_pages1 = get_option('activated_pages');
        $act_page = unserialize($activated_pages1);        

    }
    $var='';
    $all_active = $page_active = $post_active = '';
        if($_GET['action']=='show'){
            
            if(isset($_GET['type'])){
                if($_GET['type']=="pages"){
                    $var = "('page')";
                    $page_active = 'current_active';
                }
                elseif($_GET['type']=="posts"){
                    $var = "('post')";
                    $post_active = 'current_active';
                }
            }
        }
        else{
            $all_active = 'current_active';
            $var = "('page','post')";
        }
	$total_query_page = $wpdb->get_results("SELECT * FROM `wp_posts` WHERE `post_type`='page' AND `post_status`='publish'");
        $total_page = $wpdb->num_rows;
      	$total_query_posts = $wpdb->get_results("SELECT * FROM `wp_posts` WHERE `post_type`='post' AND `post_status`='publish'");
        $total_posts =  $wpdb->num_rows;

//	$total_query = $wpdb->get_results("SELECT * FROM `wp_posts` WHERE `post_type` IN ".$var." AND `post_status`='publish'");
        $total =  $total_page + $total_posts;//$wpdb->num_rows;
	$items_per_page = 10;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
            $offset = ( $page * $items_per_page ) - $items_per_page;

            $latestposts = $wpdb->get_results( "SELECT * FROM `wp_posts` WHERE `post_type` IN ".$var." AND `post_status`='publish' ORDER BY ID ASC LIMIT ${offset}, ${items_per_page}");

 ?>
<div class="activate_list_page">
                <div class="pluginheader"><img src="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/innometrics_logo.png"/></div>
    <?php notify_pending_track() ?>
                        <div class="lowerheader"><?php $Activate_Tracking = mylang_translate('Activate_Tracking');print_r($Activate_Tracking); ?></div>
                        <div class="bottom_header"><?php $Activate_innometrics = mylang_translate('Activate_innometrics');print_r($Activate_innometrics); ?><br/></div>    
        <div class="Activatepage_list" id="users_list">
<div class="page_count">
    <?php $current_path = get_site_url();  
            $Post_text = mylang_translate('Post_text');
        $Page_text = mylang_translate('Page_text');
    ?> 
    <span><a href="<?php echo $current_path.'/wp-admin/admin.php?page=innometricsactivate';?>" class="<?php echo $all_active;?>">All <div class="count_num">(<?php echo $total;?>)</div></a></span>  |  </span><span><a href="<?php echo $current_path.'/wp-admin/admin.php?page=innometricsactivate&action=show&type=pages';?>" class="<?php echo $page_active;?>"><?php echo $Page_text." ";?><div class="count_num"><?php echo "(".$total_page.")";?></div></a>  |  </span><span><a href="<?php echo $current_path.'/wp-admin/admin.php?page=innometricsactivate&action=show&type=posts';?>" class="<?php echo $post_active;?>"><?php echo $Post_text." ";?><div class="count_num"><?php echo "(".$total_posts.")";?></div></a></span>
</div>
            <div class="my_pagination">
        <?php
        echo paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ));?>
                </div>            
<form id="activate_form" name="send_certificate" method="post" action="" > 
    <div class="send_certificate">
        <select name="bulk_action1" >
            <option value="" selected><?php $Bulk_Action_text = mylang_translate('Bulk_Action_text');print_r($Bulk_Action_text); ?></option>
            <option value="start_tracking"><?php $Start_Tracking_text = mylang_translate('Start_Tracking_text');print_r($Start_Tracking_text); ?></option>
            <option value="stop_tracking"><?php $Stop_Tracking_text = mylang_translate('Stop_Tracking_text');print_r($Stop_Tracking_text); ?></option>
        </select>
        <input id="bulk_submit1" type="submit" name="activatepages_apply" value="<?php $Apply = mylang_translate('Apply');print_r($Apply); ?>" />
    </div>
        <table class="wp-list-table widefat" border="1">
        <thead>
        <tr>
        <th><input id="checkAll" type="checkbox" name="" value="YES"></input></th>
        <th scope="col" id="post_title_block" class="manage-column column-username sortable desc" style=""><span><?php $Page_post = mylang_translate('Page_post');print_r($Page_post); ?></span></th>
        <th scope="col" id="status" class="manage-column column-username sortable desc" style=""><span><?php $Enable_Disable = mylang_translate('Enable_Disable');print_r($Enable_Disable); ?></span></th>
        <th scope="col" id="publish_date" class="manage-column column-name sortable" style=""><span><?php $Date_text = mylang_translate('Date_text');print_r($Date_text); ?></span></th>
        <th scope="col" id="action" class="manage-column column-name sortable" style=""><span><?php $Actions_text = mylang_translate('Actions_text');print_r($Actions_text); ?></span></th>
        </tr>
        </thead>
        <tbody id="the-list" data-wp-lists="list:user">
        <?php
        $i='1';
        $Tracking = mylang_translate('Tracking');
        $NotTracking = mylang_translate('NotTracking');
        $Post_text = mylang_translate('Post_text');
        $Page_text = mylang_translate('Page_text');
        $published_text = mylang_translate('published_text');
        foreach ($latestposts as $latestposts){
        $activated_pages1 = get_option('activated_pages');
        $act_page = unserialize($activated_pages1); 
            $track = 0;
            $checked = '';
            if(!empty($act_page)){
                foreach($act_page as $act_page){
                    if($act_page == $latestposts->ID){
                       $track = 1;
                       //$checked = 'Checked';
                    }
                }
            }    
            ?>                
                <tr>

            <td class="formcheckbox"><input type="checkbox" name="activatepages_checkbox[]" id="<?php echo $latestposts->ID;?>" value="<?php echo $latestposts->ID;?>"></input></td>
            <?php $permalink = get_permalink( $latestposts->ID);
            ?>
            <td class="post_title_block"><div class="actpost_title"><a href="<?php echo $permalink;?>"><?php echo $latestposts->post_title; 
            if($latestposts->post_type=='page'){echo "  (".$Page_text.")";}else{echo "  (".$Post_text.")";}
            ?></a></div>
            <div class="page_id_section">ID: <?php echo $latestposts->ID;?></div>
            </td>
            <?php 
            if($track==1){
                
                ?>            
                <td class="status_active"><?php print_r($Tracking); ?> <img class="dot_img" href="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/green_dot.gif"/></td>
            <?php }else{ 
                ?>            
                <td class="status_notactive"><?php print_r($NotTracking); ?> <img class="dot_img" href="../wp-content/plugins/<?php echo PROF_FOLDER; ?>/images/red_dot.gif"/></td>
            <?php
            }?>
            
            <?php $new_date=date('Y/m/d', strtotime($latestposts->post_date));?>
            <td class="publish_date"><?php echo $new_date;?></br><?php print_r($published_text); ?></td>
            <?php 
            
            if($track==1){
                ?>            
                <td class=""><button class="singlesubmit_button" name="stop_submit" type="submit" value="<?php echo $latestposts->ID;?>"><?php print_r($Stop_Tracking_text);?></button></td>
            <?php }else{ ?>            
                <td class=""><button class="singlesubmit_button" name="start_submit" type="submit" value="<?php echo $latestposts->ID;?>"><?php print_r($Start_Tracking_text);?></button></td> 
              
  
            <?php
            }?>
            
        </tr>
        <?php }?>
        </tbody>
        <thead>
        <tr>
        <th><input id="checkAll" type="checkbox" name="" value="YES"></input></th>
        <th scope="col" id="post_title_block" class="manage-column column-username sortable desc" style=""><span><?php print_r($Page_post);?></span></th>
        <th scope="col" id="status" class="manage-column column-username sortable desc" style=""><span><?php print_r($Enable_Disable);?></span></th>
        <th scope="col" id="publish_date" class="manage-column column-name sortable" style=""><span><?php print_r($Date_text);?></span></th>
        <th scope="col" id="action" class="manage-column column-name sortable" style=""><span><?php print_r($Actions_text);?></span></th>
        </tr>
        </thead>
        </table>
    <div class="send_certificate">
        <select name="bulk_action2" >
            <option value="" selected><?php print_r($Bulk_Action_text);?></option>
            <option value="start_tracking"><?php print_r($Start_Tracking_text);?></option>
            <option value="stop_tracking"><?php print_r($Stop_Tracking_text);?></option>
        </select>
        <input id="bulk_submit2" type="submit" name="activatepages_apply2" value="<?php print_r($Apply);?>" />
    </div>
</form>
            <div class="my_pagination">
        <?php
        echo paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ));?>
                </div>
        </div>
</div>
