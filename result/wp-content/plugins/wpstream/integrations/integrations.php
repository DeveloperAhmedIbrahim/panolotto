<?php

function wpstream_check_integrations() {
    if (class_exists('BuddyPress')) {
        require plugin_dir_path( __FILE__ ) . 'buddyboss/buddyboss.php';    
    }
}




/*
*
* Get live events for logged in user
*
*/

function wpestream_integrations_get_current_user_live_events($with_exit='yes'){

    $live_event_for_user = get_transient('wpstream_bb_get_live_event_for_user');
    global $wpstream_plugin;
    if($live_event_for_user===false){
        $live_event_for_user    =    $wpstream_plugin->main->wpstream_live_connection->wpstream_get_live_event_for_user($with_exit);
        set_transient('wpstream_bb_get_live_event_for_user',$live_event_for_user,600);
    }

    return $live_event_for_user;
    
}



?>