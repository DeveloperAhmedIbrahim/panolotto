/*global $, jQuery, wpstream_integrations_vars*/
jQuery(document).ready(function ($) {
    "use strict";
    if(wpstream_integrations_vars.is_buddyboss==='yes'){
        wpstream_buddy_boss_select_channel();
    }
});


/*
*
*  On this function we receive note that the event is on
*
*/


function wpstream_integration_notifications(show_id){
    if(wpstream_integrations_vars.is_buddyboss==='yes'){
        wpstream_buddyb_generate_player_html(show_id);
    }
}


/*
*
*  Select streaming channet on BuddyBoss
*
*/


function wpstream_buddy_boss_select_channel(){
    jQuery('#wpstream_buddyboss_select_channel').on('change',function(){

        var ajaxurl = wpstream_integrations_vars.admin_url+ 'admin-ajax.php';;
        var show_id = jQuery('#wpstream_buddyboss_select_channel').val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            timeout: 300000,

            data: {
                'action'            :   'wpstream_buddy_boss_select_channel_function',
                'show_id'           :   show_id,                           
            },
            success: function (data) {
      
                if(data.saved===true){
                    location.reload();
                }
                
            },
            error: function (jqXHR,textStatus,errorThrown) {             
            }
        });
    });
}



/*
*
* BuddyB - generate html player to add in timeline
*
*/

function wpstream_buddyb_generate_player_html(show_id,user_id){
  
    var ajaxurl = wpstream_integrations_vars.admin_url+ 'admin-ajax.php';;
    var nonce = wpstream_integrations_vars.buddy_nonce;
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        dataType: 'json',
        timeout: 300000,

        data: {
            'action'            :   'wpstream_buddyb_integrations_generate_player_html',                     
            'show_id'           :   show_id,
           

        },
        success: function (data) {
           
        },
        error: function (jqXHR,textStatus,errorThrown) {   
           
        }
    });
}