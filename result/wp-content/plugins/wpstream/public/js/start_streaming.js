/*global $, jQuery, wpstream_start_streaming_vars*/
var counters={};

jQuery(document).ready(function ($) {
    "use strict";
    
    wpstream_check_live_connections();
    wpestate_start_modal_actions();
    wpestate_start_modal_error_actions();
    wpstream_tooltip();
    wpstream_copy_to_clipboard();
    wpstream_webcaster_actions();
    wpstream_save_options_actions();
    wpstream_bind_start_and_stop();

    wpstream_bind_stats_link();
    wpstream_adjust_settings_general();
  
        
});




/*
*
* genereal settings - low latency vs bitrate vs encrypt
*
*/


function wpstream_adjust_settings_general(){
   
    jQuery('.theme_options_tab_wpstream .wpstream_slider').on('click',function(event){
        event.stopPropagation();
        var parent = jQuery(this).parent();
        var input_element = parent.find('.wpstream_event_option_item');
        var attr_value = input_element.attr('data-attr-ajaxname');
      

        if( attr_value === 'adaptive_bitrate' || attr_value==='low_latency'){
            
            var timer = setTimeout(function() {
               
                if( jQuery('.wpstream-setting-adaptive_bitrate .wpstream_event_option_item').is(':checked') || 
                    jQuery('.wpstream-setting-low_latency .wpstream_event_option_item').is(':checked') ){
                    jQuery('.wpstream-setting-encrypt .wpstream_event_option_item') .removeAttr('checked'); 
                }   
            }, 300);

        }

        if( attr_value === 'encrypt'){
            var timer = setTimeout(function() {
               
                if( jQuery('.wpstream-setting-encrypt .wpstream_event_option_item').is(':checked') ){
                    jQuery('.wpstream-setting-low_latency .wpstream_event_option_item') .removeAttr('checked'); 
                    jQuery('.wpstream-setting-adaptive_bitrate .wpstream_event_option_item') .removeAttr('checked'); 
                }
            }, 300);

        }

    });
}










/*
*
* Bind block stats link
*
*/

function wpstream_bind_stats_link(){
 
    jQuery('.wpstream_statistics_channel,.start_webcaster').each(function(event){
        var selected_icon = jQuery(this);
            selected_icon.on('click',function(event){
                if(selected_icon.hasClass('wpstream_inactive_icon')){
                    event.preventDefault();
                    event.stopPropagation();
                }
                
            });
        
    })


}




/*
*
* Bind Start and Stop channel
*
*/
    

function wpstream_bind_start_and_stop(){
    
    jQuery('.start_event.wpstream_button').each(function(element){
        var start_button=jQuery(this);    
        wpstream_bind_start_event(start_button);
    });


    jQuery('.wpstream_stop_event').each(function(element){
        var stop_button=jQuery(this);
        wpstream_bind_stop_event(stop_button);
    });

}


/*
*
* Bind start  channel action
*
*/
var start_onboarding='';
function wpstream_bind_start_event(button){
   
    button.click(function(event){
        event.preventDefault();
        button.unbind('click');
        var ajaxurl             =   wpstream_start_streaming_vars.admin_url + 'admin-ajax.php';
        var acesta              =   jQuery(this);
        var notification_area   =   jQuery(this).parent().find('.event_list_unit_notification');
        var curent_content      =   jQuery(this).parent().find('.server_notification');
        var is_record           =   false;
        var is_encrypt          =   false;
        var show_id             =   parseFloat( jQuery(this).attr('data-show-id') );
        var nonce               =   jQuery('#wpstream_start_event_nonce').val();
        var parent              =   jQuery(this).parent();
        
        if( jQuery(this).parent().find('.record_event').is(":checked") ){
            is_record   =   true;
        }
        
        if( jQuery(this).parent().find('.encrypt_event').is(":checked") ){
            is_encrypt   =   true;
        }
        parent.find('.wpstream_show_settings').addClass('wpstream_inactive_icon');
        jQuery(this).addClass('wpstream_turning_on').empty().html(wpstream_start_streaming_vars.start_streaming_action+'<div class="wpstream_loader"></div><div class="wpstream_tooltip">'+wpstream_start_streaming_vars.turning_on_tooltip+'</div>');
        parent.find('.wpstream_channel_status').text(wpstream_start_streaming_vars.channel_turning_on);

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            timeout: 300000,

            data: {
                'action'            :   'wpstream_give_me_live_uri',
                'security'          :   nonce,
                'show_id'           :   show_id,
                'is_record'         :   is_record,
                'is_encrypt'        :   is_encrypt,
                'start_onboarding'  :   start_onboarding,
               
                
            },
            success: function (data) {
                
              
       
                if(data.conected===true){
             
                        if(data.event_data==='err1'){
                        
                            wpstream_show_error_on_start(wpstream_start_streaming_vars.error1,parent)
                        
                        }else if(data.event_data ==='failed event creation'){
                        
                            wpstream_show_error_on_start(wpstream_start_streaming_vars.failed_event_creation,parent)
                        
                        }else{
                            curent_content.empty();
                            var counter =  setInterval( function (){ 
                            wpstream_check_live_connections_on_start(parent,show_id,data.event_data,data)},10000);
                            counters['stop'+show_id]=counter;
                    
                        }
                        
            
                }else{
                    wpstream_show_error_on_start(data.error,parent)
                }
                
            },
            error: function (jqXHR,textStatus,errorThrown) {             
            }
        });
        
    });
    
}


/*
*
* Bind Stop channel action
*
*/

function wpstream_bind_stop_event(button){
    button.click(function(event){


        if(!confirm(wpstream_start_streaming_vars.turn_off_confirm)){
            return false;
        }
        button.unbind('click');
        

        var ajaxurl             =   wpstream_start_streaming_vars.admin_url + 'admin-ajax.php';
        var show_id             =   parseFloat( jQuery(this).attr('data-show-id') );
        var nonce               =   jQuery('#wpstream_start_event_nonce').val();
        var parent              =   jQuery(this).parent();
        var thisButton          =   jQuery(this);
      
        thisButton.removeClass('wpstream_stop_event');
        thisButton.addClass('wpstream_turning_on').empty().html(wpstream_start_streaming_vars.stop_streaming_action+'<div class="wpstream_loader"></div><div class="wpstream_tooltip">'+wpstream_start_streaming_vars.turning_off_tooltip+'</div>');
        parent.find('.wpstream_channel_status').text(wpstream_start_streaming_vars.channel_turning_off);
        parent.find('.start_webcaster').addClass('wpstream_inactive_icon');
        parent.find('.wpstream_stream_pro').addClass('wpstream_inactive_icon');
        parent.find('.wpstream_live_data').addClass('wpstream_inactive_icon');



        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            timeout: 300000,

            data: {
                'action'            :   'wpstream_turn_of_channel',
                'security'          :   nonce,
                'show_id'           :   show_id
               
                
            },
            success: function (data) {
       
                if(data.conected===true){
                    
                    thisButton.unbind('click');
         
                    var counter =  setInterval( function (){ 
                        wpstream_check_live_connections_on_start(parent,show_id,'',data)},10000);
                        counters["stop"+show_id]=counter;

                }else{
               
                    wpstream_show_error_on_stop(data.error,parent)
                }
                
            },
            error: function (jqXHR,textStatus,errorThrown) {
             
            }
        });
        
    
    
    });
    
}


/*
*
* Enable copy to clipboard
*
*/

function wpstream_enable_cliboard(parent){
    
    jQuery(parent).find('.copy_live_uri').click(function(){
      
        var value_uri = jQuery(parent).find('.wpstream_live_uri_text').text();
        var temp = jQuery("<input>");
        jQuery("body").append(temp);
        jQuery(temp).val(value_uri).select();
        document.execCommand("copy");
        jQuery(temp).remove();
        
    });
    
    jQuery(parent).find('.copy_live_key').click(function(){
        var value_uri = jQuery(parent).find('.wpstream_live_key_text').text();
        var temp = jQuery("<input>");
        jQuery("body").append(temp);
        jQuery(temp).val(value_uri).select();
        document.execCommand("copy");
        jQuery(temp).remove();
        
    });
}


/*
 *
 * Check live events/channels on starts 
 * 
 * 
 * 
*/


function wpstream_check_live_connections_on_start( parent,show_id,server_id,data){
      
    // server_url is dns change
  
    wpstream_check_event_status_in_js(show_id,'wpstream_check_live_connections_on_start',
        function(server_status){
      
            if(server_status.status==='active' ){
              
            
                wpstream_event_ready_make_actions_visible(parent);

                parent.find('.start_webcaster').attr('data-webcaster-url',server_status.webcaster_url);
                parent.find('.wpstream_live_uri_text').text(server_status.obs_uri);
                parent.find('.wpstream_live_key_text').text(server_status.obs_stream);

                var larix_rtmp= server_status.obs_uri+server_status.obs_stream;
                parent.find('.wpstream_larix_rtmp').text(larix_rtmp); 

                var   larix_qr ='larix://set/v1?conn[][url]='+encodeURIComponent(larix_rtmp);
                parent.find('.wpstream_start_with_larix_mobile').attr('href',larix_qr); 

                parent.find('.wpstream_larix_rtmp').text(larix_rtmp); 
                parent.find('.larrix_test').text(larix_rtmp); 

                var print_qrcode= "https://chart.googleapis.com/chart?cht=qr&chs=110x110&chl="+encodeURIComponent(larix_qr) +"&choe=UTF-8";
                parent.find('.print_qrcode').attr('src',print_qrcode); 
                
                parent.parent().find('.wpstream_live_data').attr('href',server_status.live_data_url);         
                clearInterval( counters["stop"+show_id]);


                if (typeof wpstream_integration_notifications === 'function') {
                    wpstream_integration_notifications(show_id);
                }

                
            }else if(server_status.status==='stopped' ){    
             
                clearInterval( counters["stop"+show_id]);
                wpstream_event_stopped_make_actions(parent);

            }else if(server_status.status==='error' ){
               
                clearInterval( counters["stop"+show_id]);
                var curent_content = parent.find('.wpstream_channel_status');
                curent_content.html('<div class="wpstream_channel_status not_ready_to_stream"><span class="dashicons dashicons-dismiss"></span>'+wpstream_start_streaming_vars.failed_event_creation+'</div>');
            }
    });

}




/*
 * check channell id status
 * 
 * @param {type} channel_id
 * @param {type} callback
 * @returns {undefined}
 * 
 * 
*/

function wpstream_check_event_status_in_js(channel_id,notes,callback){

    var ajaxurl             =   wpstream_start_streaming_vars.admin_url + 'admin-ajax.php';
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        timeout: 3000000,

        data: {
            'action'            :   'wpstream_check_event_status',
            'channel_id'        :   channel_id,
            'notes'             :   notes,
         
        },
        success: function (data) {

            var obj = data;
            var channel_status = obj.status;
             
            if(channel_status=='active' || channel_status=='stopped'  ){
                callback(obj);
            }else if(channel_status=='error'){
                callback(obj);
            }else{
                callback(false); 
            }
            
        }, error: function (jqXHR,textStatus,errorThrown) {
          
        }
  });
}


/*
*
* Make actions visible on event if ready
*
*
*/
function wpstream_event_ready_make_actions_visible(parent){

    var  actionButton = parent.find('.start_event');
    parent.addClass('wpstream_show_started');
    actionButton.unbind('click');
    wpstream_bind_stop_event(actionButton);
    
    actionButton.addClass('wpstream_stop_event').removeClass('start_event').html(wpstream_start_streaming_vars.stop_streaming+'<div class="wpstream_tooltip">'+wpstream_start_streaming_vars.turned_off_tooltip+'</div>');
    parent.find('.wpstream-button-icon').removeClass('wpstream_inactive_icon');
    parent.find('.wpstream_show_settings').addClass('wpstream_inactive_icon');
    parent.find('.wpstream_channel_status').text(wpstream_start_streaming_vars.channel_on);
 
    //for onboarding
    var check_against='3';
    var check_against_camera_icon='5';
    if(jQuery('#woocommerce-product-data').length>0){
        check_against='5'
        check_against_camera_icon='7';
    }       
   
    
    var bubble_Step =jQuery('#wpstream_onboard_bubble').attr('data-bubble-step');

    if(bubble_Step===check_against){
        jQuery('#wpstream_onboard_bubble_tile').text('Channel is now ON');
        jQuery('#wpstream_onboard_bubble_content').text('You are ready to GO LIVE. Click Next to see how.');
    }
    if(bubble_Step===check_against_camera_icon){
        jQuery('#wpstream_onboard_bubble_tile').html('Go LIVE');
        jQuery('#wpstream_onboard_bubble_content').html('To Go Live now, click the <div class=\"wpstream_sample_icon_settings wpstream_sample_icon_camera\"></div> icon. The broadcast app will open in a new window.');
        
        if( jQuery('.event_list_unit .wpstream_button').hasClass('wpstream_stop_event') ){
            jQuery('.wpstream_sample_icon_camera').css('cursor','pointer');
          
            jQuery('.wpstream_sample_icon_camera').on('click',function(){
                jQuery('.start_webcaster').trigger('click');
                jQuery(this).unbind('click');

            })   
        }

    }
}


/*
*
* Make actions visible on event if stopped
*
*
*/
function wpstream_event_stopped_make_actions(parent){
 
    var  actionButton = parent.find('.wpstream_turning_on');
    wpstream_bind_start_event(actionButton);
    parent.removeClass('wpstream_show_started');
    actionButton.removeClass('wpstream_turning_on');
    actionButton.addClass('start_event');
    actionButton.html( wpstream_start_streaming_vars.start_streaming+'<div class="wpstream_tooltip">'+wpstream_start_streaming_vars.turned_on_tooltip+'</div>');

    parent.find('.wpstream-button-icon').removeClass('wpstream_inactive_icon');
    parent.find('.wpstream_stream_pro').addClass('wpstream_inactive_icon');
    parent.find('.start_webcaster').addClass('wpstream_inactive_icon');
    parent.find('.wpstream_statistics_channel').addClass('wpstream_inactive_icon');

    parent.find('.wpstream_channel_status').text(wpstream_start_streaming_vars.channel_off);
}



function wpstream_show_error_on_start(text,parent){
    //You don't have enough data to start a new event!
    jQuery('.wpstream_error_content').text(text);
    jQuery('.wpstream_modal_background').show();
    jQuery('.wpstream_error_modal_notification').show();
    wpstream_event_stopped_make_actions(parent);

}

function wpstream_show_error_on_stop(text,parent){
    //You don't have enough data to start a new event!
    jQuery('.wpstream_error_content').text(text);
    jQuery('.wpstream_modal_background').show();
    jQuery('.wpstream_error_modal_notification').show();
    wpstream_event_ready_make_actions_visible(parent);

}


/*
*
* Streaming modal trigger functions
*  
*
*/
function wpestate_start_modal_actions(){

    jQuery('.wpstream_close_modal').on('click',function(event){
        jQuery(this).parent().hide();
        jQuery('.wpstream_modal_background').hide();
    });

    document.addEventListener('keydown', function(event) {
        if(event.keyCode == 27){
            jQuery('.wpstream_modal_form').hide();
            jQuery('.wpstream_modal_background').hide();
        }
    });


    jQuery('.wpstream-trigger-modal').on('click',function(event){

        if( jQuery(this).hasClass('wpstream_inactive_icon') ) {
            return;
        };

        jQuery('.wpstream_modal_background').show();
        var modal_class=jQuery(this).attr('data-modal');
        var parent =jQuery(this).closest('.event_list_unit');
        jQuery('.wpstream_modal_background').show();
        parent.find("."+modal_class).show();
    })

    jQuery('.wpstream_external_broadcast_options').change(function(event){
        var new_option = jQuery(this).val();
        var parent = jQuery(this).parent();

        parent.find('.external_software_streaming').hide();
        parent.find('.'+new_option).show();
    });


}



/*
*
* Tooltips for buttons
*  
*
*/ 

function wpstream_tooltip(){

    jQuery( ".wpstream_tooltip_wrapper" ).hover(
    function() {

        if(jQuery( this ).hasClass('wpstream_inactive_icon')){
            jQuery( this ).find('.wpstream_tooltip_disabled').css('opacity',1);
        }else{
            jQuery( this ).find('.wpstream_tooltip').css('opacity',1);
        }


    }, function() {
        jQuery( this ).find('.wpstream_tooltip').css('opacity',0);
        jQuery( this ).find('.wpstream_tooltip_disabled').css('opacity',0);
    }
);
}

/*
*
* Modal errors
*  
*
*/ 


function wpestate_start_modal_error_actions(){
    jQuery('.wpstream_error_ok').on('click',function(event){
        jQuery(this).parent().hide();
        jQuery('.wpstream_modal_background').hide();
        jQuery(this).parent().find('.wpstream_error_content').text('');
    });
}




/*
*
* Copy to Clipboard
*  
*
*/ 

function wpstream_copy_to_clipboard(){
       
    jQuery('.copy_live_uri').on('click',function(){
        var value_uri = jQuery(this).parent().find('.wpstream_live_uri_text').text();
        var temp = jQuery("<input>");
        jQuery("body").append(temp);
        jQuery(temp).val(value_uri).select();
        document.execCommand("copy");
        jQuery(temp).remove();
        
    });
    
    jQuery('.copy_live_key').on('click',function(){
        var value_uri = jQuery(this).parent().find('.wpstream_live_key_text').text();
        var temp = jQuery("<input>");
        jQuery("body").append(temp);
        jQuery(temp).val(value_uri).select();
        document.execCommand("copy");
        jQuery(temp).remove();
    });
}

/*
*
* Webcaster button action
*  
*
*/ 

function wpstream_webcaster_actions(){   
    jQuery('.start_webcaster').on('click',function(){    
        if(jQuery(this).hasClass('wpstream_inactive_icon')){
            return;
        }
        var caster_url = jQuery(this).attr('data-webcaster-url');
        jQuery(this).parent().find('.external_software_streaming').slideUp()
        window.open(caster_url, '_blank', 'location=yes,scrollbars=yes,status=yes');
    })
}



/*
*
*  per event - low latency vs bitrate vs encrypt
*
*/


function wpstream_adjust_settings(input_element){

   
   
    var attr_value = input_element.attr('data-attr-ajaxname');
    console.log(attr_value);

 

    if( attr_value === 'adaptive_bitrate' || attr_value==='low_latency'){
        console.log('perform ');

        var timer = setTimeout(function() {
           
            if( jQuery('.wpstream-setting-adaptive_bitrate .wpstream_event_option_item').is(':checked') || 
                jQuery('.wpstream-setting-low_latency .wpstream_event_option_item').is(':checked') ){
                jQuery('.wpstream-setting-encrypt .wpstream_event_option_item') .removeAttr('checked');           
            }      
        }, 300);

    }

    if( attr_value === 'encrypt'){
        console.log('perform2 ');

        var timer = setTimeout(function() {
           
            if( jQuery('.wpstream-setting-encrypt .wpstream_event_option_item').is(':checked') ){
                   
                jQuery('.wpstream-setting-low_latency .wpstream_event_option_item') .removeAttr('checked'); 
                jQuery('.wpstream-setting-adaptive_bitrate .wpstream_event_option_item') .removeAttr('checked'); 
           
            }

           
        }, 300);

    }

}

/*
*
* Save options actions
*  
*
*/ 

function wpstream_save_options_actions(){
    jQuery('.wpestate_settings_modal .wpstream_event_option_item').on('click',function(){

        wpstream_adjust_settings(jQuery(this));

        
        var holder  =   jQuery(this).parents('.wpstream_event_streaming_local');
        var show_id =   jQuery(this).parents('.event_list_unit').find('.start_event').attr('data-show-id');
        var optionarray ={};
        var nonce               =   jQuery('#wpstream_start_event_nonce').val();
        var timer = setTimeout(function() {
            holder.find('.wpstream_event_option_item').each(function(){
                optionarray[jQuery(this).attr('data-attr-ajaxname')]=jQuery(this).prop("checked") ? 1 : 0 ;
            });
       

            var myJSON = JSON.stringify(optionarray);
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                timeout: 300000,
                data: {
                    'action'            :   'wpstream_update_local_event_settings',
                    'show_id'           :   show_id,
                    'option'            :   optionarray,
                    'security'          :   nonce
                                
                },
                success: function (data) {
                },
                error: function (jqXHR,textStatus,errorThrown) {
                }
                
            });
        }, 300);
        
    });
    
}


/*
*
* Function to check live connections
*  
*
*/ 
 
function wpstream_check_live_connections(){
    // was .pending_streaming.pending_trigger
    if( jQuery('.event_list_unit.pending_trigger').length>0 ){
        jQuery('.event_list_unit.pending_trigger').each(function(){
            var acesta      =   jQuery(this);
            var show_id     =   jQuery(this).attr('data-show-id');
            var server_id   =   jQuery(this).attr('data-server-id');            

            wpstream_check_live_connections_from_database(acesta,show_id,server_id);
            var counter_long     =   '';
            counter_long =  setInterval( function (){ wpstream_check_live_connections_from_database(acesta,show_id,server_id)},60000);
            counters[show_id]=counter_long;

        });
    }
 
}



  
/*
*
* check live connection
*
*
*/

    
function wpstream_check_live_connections_from_database( acesta,channel_id,server_id){
    var server_status = wpstream_check_event_status_in_js(channel_id,'wpstream_check_live_connections_from_database',
        function(server_status){
  
            if(server_status.status==='active' ){
                wpstream_event_ready_make_actions_visible( acesta );
                acesta.find('.wpstream_ready_to_stream .start_webcaster').attr('data-webcaster-url',server_status.webcaster_url);
                acesta.find('.wpstream_live_uri_text').text(server_status.obs_uri);
                acesta.find('.wpstream_live_key_text').text(server_status.obs_stream);
            
                var larix_rtmp= server_status.obs_uri+server_status.obs_stream;
                acesta.find('.wpstream_larix_rtmp').text(larix_rtmp);

                acesta.find('.larrix_test').text(larix_rtmp); 

                var   larix_qr ='larix://set/v1?conn[][url]='+encodeURIComponent(larix_rtmp);
                acesta.find('.wpstream_start_with_larix_mobile').attr('href',larix_qr); 
                
                var print_qrcode= "https://chart.googleapis.com/chart?cht=qr&chs=110x110&chl="+encodeURIComponent(larix_qr) +"&choe=UTF-8";

                acesta.find('.print_qrcode').attr('src',print_qrcode); 
                acesta.find('.wpstream_live_data').attr('href',server_status.live_data_url);
                clearInterval( counters["stop"+channel_id]);
         
            }else if(server_status.status==='error' ){
                
                clearInterval( counters["stop"+channel_id]);
                var curent_content = acesta.find('.wpstream_channel_status ');
                
                curent_content.html('<div class="wpstream_channel_status not_ready_to_stream"><span class="dashicons dashicons-dismiss"></span>'+wpstream_start_streaming_vars.failed_event_creation+'</div>');
            }
        });

}



/*
*
* Check event/channel status
*
*
*/

    
function wpstream_check_server_status(url_param,callback) {

    var url = url_param ;
    var status='';
    jQuery.ajax({
        url: url,
        type: "get",
        cache: false,
        dataType: 'jsonp', // it is for supporting crossdomain
        crossDomain : true,
        asynchronous : false,
        timeout : 1500, // set a timeout in milliseconds
        callback:'',
        complete : function(xhr) {
    
            if(xhr.status == "200" || xhr.status == "400") {
                callback(true);
            }
            else {
                callback(false);
            }
        }
    });
}
    