<?php



/*
* remove BuddyB video js files
* 
*
*/


function bp_init_callbackx() {
 remove_action( 'bp_nouveau_enqueue_scripts', 'bp_nouveau_video_enqueue_scripts' );

}
//add_action( 'bp_init', 'bp_init_callbackx' );






/*
* add wpstream in mennu
* 
*
*/


add_action('bp_setup_nav', 'wpstream_buddypress_setup_nav', 11 );




/*
*
*  Buddy Buss Wpstream Section Profile title
*
*/

function wpstream_buddyboss_title() {
	esc_html_e( 'My Live Video','wpstream');
}


/*
*
*  Buddy Buss Wpstream Section Profile Content
*
*/

function wpstream_buddyboss_content() {
    global $wpstream_plugin;?>
    
    
    <div class="wpstream-bb-profile-wrapper">
      
              
                <?php
                $current_user               = wp_get_current_user();
                $user_role                  ='';
                if(is_user_logged_in()){
                    $user_role                  = $current_user->roles[0];                 
                }
                $author_id                  = get_current_user_id();
                $profile_owner              = bp_get_displayed_user();
                $free_events                = wpstream_buddyboss_load_free_events();
                $budyboss_selected_channel  = intval( get_user_meta($profile_owner->id,'budyboss_selected_channel',true) );
                $extra_roles                = get_option( 'wpstream_stream_role', true );
                if(!is_array($extra_roles)){
                    $extra_roles=array();
                }

                $extra_roles[]='administrator';
              


                // if the role permits
                if (is_array($extra_roles) && in_array($user_role, $extra_roles)){
               

                    if(count($free_events) !== 0 && $profile_owner->id == $author_id ){
                        print '<h4>'.esc_html('Select the Channel','wpstream').'</h4>';
                        print wpstream_buddyboss_display_select_free_events($budyboss_selected_channel,$free_events);
                    }

                    
                    if($profile_owner->id == $author_id ){
                        print '<h4>'.esc_html('Channel Controls','wpstream').'</h4>';
                        echo  $wpstream_plugin->wpstream_live_stream_unit_wrapper(   $budyboss_selected_channel,'front' );
                    }

        
                
                }else{
                    if($profile_owner->id == $author_id ){
                        esc_html_e('Your account level does not have the permission to do live streaming!','wpstream');
                    }
                }

                $attributes['id']                   =   $budyboss_selected_channel;  
                $attributes['user_id']              =   $profile_owner->id ;  
                
                if($profile_owner->id == $author_id ){
                    print '<h4>'.esc_html('Preview','wpstream').'</h4>';
                }


                echo  $wpstream_plugin->wpstream_insert_player_elementor($attributes);

       
   
                ?>

                
        
    </div>
<?php
}



/*
*
* export the select elments for free events
*
*/


function wpstream_buddyboss_display_select_free_events($budyboss_selected_channel,$free_events){
    $return_array = '<select id="wpstream_buddyboss_select_channel">';
  
    foreach ($free_events as $key=>$title){
        $is_selected='';
        if($budyboss_selected_channel==$key){
            $is_selected = ' selected ';
        }
        $return_array.='<option value="'.intval($key).'" '.esc_attr($is_selected).'>'.esc_html($title).'</option>';
    }
    $return_array .='</select>'; 
    return $return_array;

}

/*
* Load Free Events
* 
*
*/

function wpstream_buddyboss_load_free_events(){

    $author_id = get_current_user_id();
    if($author_id==0){
        return array();
    }

    $args_free = array(
        'posts_per_page'    => -1,
        'post_type'         => 'wpstream_product',
        'post_status'       => 'publish',
        'author'            =>  $author_id 
        

    );
   
    $event_list_free = new WP_Query($args_free);
    
    $return_array=array();
    if( $event_list_free->have_posts() ):
        while ($event_list_free->have_posts()): $event_list_free->the_post();
            $the_id =   get_the_ID();
            $return_array[$the_id] = get_the_title($the_id) ;
        endwhile;
    endif;

    return $return_array;

}

/*
*
* 
*
*/

function wpstream_buddyboss_screen_wpstream() {
        add_action( 'bp_template_title', 'wpstream_buddyboss_title' );
        add_action( 'bp_template_content', 'wpstream_buddyboss_content' );

        $template = apply_filters( 'bp_core_template_plugin', 'members/single/plugins' );
        bp_core_load_template( apply_filters( 'bp_stream_filter', $template ) );

}



/*
*
* Buddy Booss add wpstream menu on profile
*
*/
function wpstream_buddypress_setup_nav() {
	global $bp;
	// Determine user to use.
	if ( bp_displayed_user_domain() ) {
		$user_domain = bp_displayed_user_domain();
	} elseif ( bp_loggedin_user_domain() ) {
		$user_domain = bp_loggedin_user_domain();
	} else {
		return;
	}

	$parent_slug = 'wpstream';
	$parent_url  = trailingslashit( $user_domain . $parent_slug );
	$tab_slug    = 'wpstream-menu';

	// parent nav
	$parent_nav = array(
		'name'                => __( 'Live Video', 'wpstream' ),
		'slug'                => $parent_slug,
		'parent_slug'         => $bp->profile->slug,
		'screen_function'     => 'wpstream_buddyboss_screen_' . $parent_slug,
		'default_subnav_slug' => $tab_slug,
		'position'            => 60,
		'item_css_id'         => 'wpstream-bb-nav-' . $parent_slug,
	);


	
    bp_core_new_nav_item( $parent_nav );
  
	
}





/*
*
* Filter that runs in timeline - before activity is displayed
*
*/

add_action( 'bp_before_activity_entry', 'wpstream_bb_before_activity_function',99,1 );

function wpstream_bb_before_activity_function($activity) {
    global $activities_template;
    global $wpstream_plugin;
    $activity_id = $activities_template->activity->id;
    $notes="wpstream_bb_before_activity_function";
    $is_wpstream_bb_show_id     = bp_activity_get_meta( $activity_id, 'is_wpstream_bb_show_id', true );
    if($is_wpstream_bb_show_id==='yes'){
        $wpstream_bb_show_id        = bp_activity_get_meta( $activity_id, 'wpstream_bb_show_id', true );

        $is_wpstream_bb_show_id_past_event     = bp_activity_get_meta( $activity_id, 'is_wpstream_bb_show_id_past_event', true );

        if($is_wpstream_bb_show_id_past_event=='yes'){
            $activities_template->activity->content=  esc_html__( 'My Live Stream has ended. Join me next time! ', 'wpstream' ) ;
        }else{
            $event_status               =   $wpstream_plugin->main->wpstream_live_connection->wpstream_check_event_status_api_call($wpstream_bb_show_id,$notes);
               
 

            if($event_status['status']=='stopped' || $event_status['status']=='ended' || $event_status['status']=='stopping'    ){
                // we mark the event as a past one
                bp_activity_update_meta( $activity_id, 'is_wpstream_bb_show_id_past_event','yes' );
            }


        }
    }

}


/*
*
* Filter that runs in timeline - after activity is displayed
*
*/

add_action( 'bp_after_activity_entry', 'wpstream_bb_after_activity_function',99,1 );

function wpstream_bb_after_activity_function($activity) {
    global $activities_template;
    global $wpstream_plugin;
    $notes='';
    $chat_url='';
    // Get the ID of the current activity item
    $activity_id                = $activities_template->activity->id;
    $is_wpstream_bb_show_id     = bp_activity_get_meta( $activity_id, 'is_wpstream_bb_show_id', true );

    if($is_wpstream_bb_show_id==='yes'){
        $wpstream_bb_show_id        = bp_activity_get_meta( $activity_id, 'wpstream_bb_show_id', true );

        $is_wpstream_bb_show_id_past_event     = bp_activity_get_meta( $activity_id, 'is_wpstream_bb_show_id_past_event', true );

  
        if($is_wpstream_bb_show_id_past_event !=='yes'){

            $notes                      =   "check from buddy boss timeline";
            $event_status               =   $wpstream_plugin->main->wpstream_live_connection->wpstream_check_event_status_api_call($wpstream_bb_show_id,$notes);
               
    
            // event is live 
            if(isset($event_status['status']) && $event_status['status']=='active'){
                $autoplay           =   'autoplay';        
                //live event
                $hls_playback_url='';
                if(isset($event_status['hls_playback_url'])){
                    $hls_playback_url        =   $event_status['hls_playback_url'];
                    
                    update_post_meta($wpstream_bb_show_id,'stream_name',$event_status['stream_name']);
                    update_post_meta($wpstream_bb_show_id,'hls_key_retrieval_url',$event_status['hls_key_retrieval_url']);
                    delete_transient(  'free_event_streamName_'.$event_status['stream_name']);

                    $live_conect_array      =   explode('live.streamer.wpstream.net',$hls_playback_url);
                    $live_conect_views      =   $live_conect_array[0].'live.streamer.wpstream.net';
                    $live_conect_views      =   $wpstream_plugin->main->wpstream_player->remove_http($live_conect_views);
            
                }
                $video_trailer='';
                $now='';
                $is_muted =false;
                print '<script type="text/javascript">
                //<![CDATA[
                    jQuery(document).ready(function(){
             
                        var wrapper = jQuery(".wpstream_live_player_wrapper[data-product-id='.$wpstream_bb_show_id.'] ");
                        var nowstring=wrapper.attr("data-now");
                     
             
                        wpstream_player_initialize({
                            videoElementId: nowstring,
                            trailerUrl: "'.$video_trailer.'",
                            contentUrl: "'.$hls_playback_url.'",
                            statsUri:  "'.$live_conect_views.'",
                            autoplay: '.var_export($autoplay, true).',
                            muted: '.var_export($is_muted, true).',
                            playTrailerButtonElementId: "wpstream_live_video_play_trailer_btn_'.$now.'",
                            muteTrailerButtonElementId: "wpstream_live_video_mute_trailer_btn_'.$now.'",
                            unmuteTrailerButtonElementId: "wpstream_live_video_unmute_trailer_btn_'.$now.'",
                        });
                        ';
                    print'});
                //]]>
                </script>';


            
                if(trim($hls_playback_url) !==''){
                    print '<script type="text/javascript">
                        //<![CDATA[
                            jQuery(document).ready(function(){
                                var wrapper = jQuery(".wpstream_live_player_wrapper[data-product-id='.$wpstream_bb_show_id.'] ");
                                var nowstring=wrapper.attr("data-now");
                                var player_wrapper =   jQuery(".wpstream_live_player_wrapper");
                                wpstream_read_websocket_info("'.$wpstream_bb_show_id.'","wpstream_live_player_wrapper"+nowstring, player_wrapper ,"'.$chat_url.'", "'.$hls_playback_url.'");
                            });
                        //]]>
                    </script>';
                }   
            }
        }
    }
    return $activity;
}




/*
*
* Ajax function to select the streaming channel
*
*
*/


add_action( 'wp_ajax_wpstream_buddy_boss_select_channel_function', 'wpstream_buddy_boss_select_channel_function' );  
    

function wpstream_buddy_boss_select_channel_function(){
    $show_id    =   intval($_POST['show_id']);
    $userId     =   get_current_user_id();
    $show_data  =   get_post($show_id);

    if( $show_data->post_author != $userId ){
        exit('You are not allowed to do that.This channel does not belong to you!');
    }

    $update = update_user_meta($userId,'budyboss_selected_channel',$show_id);

    
    echo json_encode( 
        array(
            'saved'               =>  boolval($update),
        )
    );
    die();
                        

}

/*
* Generate player to be added in timeline
*
*
*/

add_action( 'wp_ajax_wpstream_buddyb_integrations_generate_player_html', 'wpstream_buddyb_integrations_generate_player_html' );  
    

function wpstream_buddyb_integrations_generate_player_html(){
    ob_start();
    $show_id                 =   intval($_POST['show_id']);  
    wpstream_bp_init_callback($show_id);
    $data=ob_get_contents();
    ob_end_clean();
    echo json_encode( array(
        'success'=>  true, 
        'link'  =>  ($data) 
    ));


    die();

}

/*
*
*
*
*/


function wpstream_bp_init_callback($show_id) {
    global $wpstream_plugin;

    $attributes['id']                   =  $show_id;  
    $attributes['user_id']              =   ''; 
    $content = $wpstream_plugin->wpstream_insert_player_elementor($attributes);
    
    $profile_owner      = bp_get_displayed_user();
    $profile_link       = $profile_owner->domain.'wpstream';

    $new_activity = array(
      // 'content' => '<div class="wpstreaam_bb_see_mee_live"><a href="'.esc_url($profile_link).'">'.esc_html__('Tune in to my Live Stream!','wpstream').'</a></div>'.$content,
       'content' => '<div class="wpstreaam_bb_see_mee_live">'.esc_html__('Tune in to my Live Stream!','wpstream').'</div>'.$content,
      
       'user_id' => get_current_user_id(),
    );

    $activity_id       = bp_activity_post_update( $new_activity );
    


    bp_activity_update_meta( $activity_id, 'wpstream_bb_show_id', $show_id );
    bp_activity_update_meta( $activity_id, 'is_wpstream_bb_show_id','yes' );
 }
 





 

/*
*
* Filter to allow video js
*
*/

add_filter( 'bp_activity_allowed_tags', 'wpstream_bb_activity_allowed_tags_callback', 999, 1 );
function wpstream_bb_activity_allowed_tags_callback( $allow_html_tags ) {
    $allow_html_tags['div'] = array(
        'class' => array(),
        'id'    => array(),
        'data-now'=>array(),
        'data-me'=>array(),
        'data-product-id'=>array(),
        'data-autoplay'=>array(),
        'playsinline'=>array(),
        'tabindex'=>array(),
        'lang'=>array(),
        'translate'=>array(),
        'role'=>array(),
        'aria-label'=>array(),
    );

    $allow_html_tags['video'] = array(
        'class' => array(),
        'id'    => array(),
        'playsinline'=>array(),
        'data-autoplay'=>array(),
        'controls'=>array(),
        'muted'=>array(),
        'poster'=>array(),

    );


    $allow_html_tags['source']=array(
        'src'=>array(),
        'type'=>array(),
    );
    $allow_html_tags['script']=array(
        'src'=>array(),
        'type'=>array(),
    );
    $allow_html_tags['a']=array(
        'class' => array(),
        'href'  => array(),
        'rel'   => array(),
        'title' => array(),
        'target'=> array(),
        'aria-label'      => array(),
        'class'           => array(),
        'data-bp-tooltip' => array(),
        'id'              => array(),
        'rel'             => array(),
      
    );

   return $allow_html_tags;
}





?>