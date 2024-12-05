/*global $, jQuery, */
var counters={};
    
jQuery(document).ready(function ($) {
    "use strict";
    

    generate_download_link();
    generate_delete_link();
    wpstream_handle_video_selection();    
    wpstream_upload_images_in_wpadmin();
 
    
    function social_media_toggle(social_class){
        
        jQuery('.'+social_class).on('change',function(){

            if( $(this).prop('checked') ){
                jQuery($(this).parent().parent().find( '.'+social_class+'_container' )).slideDown('100');
            }else{
                jQuery($(this).parent().parent().find( '.'+social_class+'_container' )).slideUp('100');
            }  

        });
    }
    
    
    
    jQuery('.wpstream_notices .notice-dismiss').on('click',function(){
       
        var ajaxurl     = wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
        var notice_type = $(this).parent().attr('data-notice-type');
        var nonce       = $('#wpstream_notice_nonce').val();
        

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'                    :   'wpstream_update_cache_notice',
                'notice_type'               :   notice_type,
                'security'                  :   nonce
            },
            success: function (data) {     

            
            },
            error: function (errorThrown) { 
              
            }
        });
    });
    
    
    
    
    function generate_delete_link(){
        $('.wpstream_delete_media').on('click',function(){
            
           
            var ajaxurl             =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
            var video_name          =   $(this).attr('data-filename').trim();
            var acesta              =   $(this);
            var parent              =   $(this).parent();

          
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    'action'            :   'wpstream_get_delete_file',
                    'video_name'        :   video_name

                },
                success: function (data) {
                    
                    if(data.success===true){
                        parent.remove();
                    }
                   

                },
                error: function (errorThrown) {
                }
            });
        });

    
    }
    
    function generate_download_link(){
            
        $('.wpstream_get_download_link').on('click',function(){
            var ajaxurl             =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
            var video_name          =   $(this).attr('data-filename');
            var acesta              =   $(this);
            var parent              =   $(this).parent();

            jQuery(this).remove();
            parent.find('.wpstream_download_link').show().text('please wait...');



            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    'action'            :   'wpstream_get_download_link',
                    'video_name'        :   video_name,

                },
                success: function (data) {
                    
                   
                    
                    if(data.success===true){
                        parent.find('.wpstream_download_link').show().text(wpstream_admin_control_vars.download_mess);
                        parent.find('.wpstream_download_link').show().attr('href',data.url);
                    }else{
                        var error_message=data.error;
                        
                        if(data.error==='NOT_ENOUGH_TRAFFIC'){
                            error_message = 'Not Enough data to download!';
                            
                        }
                        
                        parent.find('.wpstream_download_link').show().text(error_message); 
                    }
                    
                    

                },
                error: function (errorThrown) {
                }
            });
        });
    
    }

    
  
    
    
    $( '.inputfile' ).each( function(){
		var $input	 = $( this ),
			$label	 = $input.next( 'label' ),
			labelVal = $label.html();

		$input.on( 'change', function( e )
		{
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				$label.find( 'span' ).html( fileName );
			else
				$label.html( labelVal );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
    });
    


    /*
    *
    * File Upload
    * 
    */


    var form = $('.direct-upload');
    var filesUploaded = [];
    var folders = [];

   // var new_file_name='';  
    
    form.fileupload({
        
        url: form.attr('action'),
        type: form.attr('method'),
       
        datatype: 'xml',
            add: function (event, data) {
               
          
               if( data.files[0].type!=='video/mp4' && data.files[0].type!=='video/quicktime'){
                    jQuery('#wpstream_uploaded_mes').empty().html(wpstream_admin_control_vars.not_accepted);
                   return;
               }
                    
          
             
               var file_size    = (parseInt(data.files[0].size,10))/1000000;
               var user_storage = jQuery('#wpstream_storage').val();
               var user_band    = jQuery('#wpstream_band').val();
               
         
               
               if(file_size > user_storage || file_size>user_band){
                    jQuery('#wpstream_uploaded_mes').empty().html(wpstream_admin_control_vars.no_band_no_store);
                    return;
               }
               
               
                $('#wpstream_label_action').text(wpstream_admin_control_vars.uploading)
                
                jQuery('#wpstream_uploaded_mes').empty().html();
                // Show warning message if your leaving the page during an upload.
                window.onbeforeunload = function () {
                    return 'You have unsaved changes.';
                };

                var file = data.files[0];
                
               
                form.find('input[name="Content-Type"]').val(file.type);
                form.find('input[name="Content-Length"]').val(file.size);

                // Actually submit to form to S3.
                data.submit();

                // Show the progress bar
                // Uses the file size as a unique identifier
                var bar = $('<div class="progress" data-mod="'+file.size+'"><div class="bar"></div></div>');
                $('.progress-bar-area').append(bar);
                bar.slideDown('fast');
            },
            progress: function (e, data) {
                // This is what makes everything really cool, thanks to that callback
                // you can now update the progress bar based on the upload progress.
                var percent = Math.round((data.loaded / data.total) * 100);
                $('.progress[data-mod="'+data.files[0].size+'"] .bar').css('width', percent + '%').html(percent+'%');
            },
            
            fail: function (e, data) {
             
                // Remove the 'unsaved changes' message.
                window.onbeforeunload = null;
                $('.progress[data-mod="'+data.files[0].size+'"] .bar').css('width', '100%').addClass('red').html('');
                $('.bar').remove();
                $('#wpstream_uploaded_mes').empty().html(wpstream_admin_control_vars.upload_failed);
                $('#wpstream_label_action').empty().html(wpstream_admin_control_vars.upload_failed2);
            },
             
            error: function (e, data) {
          
                // Remove the 'unsaved changes' message.
                window.onbeforeunload = null;
                $('.progress[data-mod="'+data.files[0].size+'"] .bar').css('width', '100%').addClass('red').html('');
                $('.bar').remove();
                $('#wpstream_uploaded_mes').empty().html(wpstream_admin_control_vars.upload_failed);
                $('#wpstream_label_action').empty().html(wpstream_admin_control_vars.upload_failed2);
            },
            done: function (event, data) {
               
                window.onbeforeunload = null;
                $('.bar').remove();
                $('#wpstream_uploaded_mes').empty().html(wpstream_admin_control_vars.upload_complete);
                $('#wpstream_label_action').text(wpstream_admin_control_vars.upload_complete2);

                var new_file_name=data.files[0].name;

                
                var new_file_name_array =  data.files[0].name.split(".");
                var temp_file_name      =  new_file_name_array[0].split(' ').join('_');
                temp_file_name          =  temp_file_name.replace(/\W/g, '');           
                new_file_name           =  temp_file_name+'.'+new_file_name_array[new_file_name_array.length-1];
                
                
                
                
                var onclick_string=' Are you sure you wish to delete '+new_file_name+' ? ';

                var to_insert='<div class="wpstream_video_wrapper"><div class="wpstream_video_title"><div class="wpstream_video_notice"></div></div><div class="wpstream_video_title">';
                to_insert=to_insert+'<strong class="storage_file_name">File Name :</strong><span class="storage_file_name_real">'+new_file_name+' </span></div>';
                to_insert=to_insert+'<div class="wpstream_delete_media"  '; 
                to_insert=to_insert+' onclick=" return confirm('+onclick_string+') "';
                to_insert=to_insert+' data-filename="'+new_file_name+'"  >delete file</div>';
                to_insert=to_insert+'<div class="wpstream_get_download_link" data-filename="'+new_file_name+'">download</div> ';
                to_insert=to_insert+'<a href="" class="wpstream_download_link">Click to download! The url will work for the next 20 minutes!</a></div>';
                
                $('#video_management_title').after(to_insert);
                
         

                $('.wpstream_get_download_link').unbind('click');
                $('.wpstream_delete_media').unbind('click');

                generate_download_link();
                generate_delete_link();
                
                setTimeout(function(){        window.location.href = window.location.href; }, 1000);

               
            }
    });
            
    
  

    
   
    
    
    jQuery('#product-type').on('change',function(){
        
        var product_type= jQuery('#product-type').val();
        if(product_type==='live_stream' || product_type==='video_on_demand' || product_type==='wpstream_bundle' ){
            jQuery('._sold_individually_field').show();
        }
        
    });
    
    if(wpstream_findGetParameter('new_video_name')!=='' && wpstream_findGetParameter('new_video_name')!=null ){
        jQuery('#product-type').val('video_on_demand').trigger('change');
    }
    
    if(wpstream_findGetParameter('new_stream')!=='' && wpstream_findGetParameter('new_stream')!=null ){
        jQuery('#product-type').val('live_stream').trigger('change');
    }
    
    var product_type=  jQuery('#product-type').val();
  console.log('xxx '+product_type); 
    if ( product_type === 'video_on_demand' ) {
        jQuery('.show_if_video_on_demand' ).show();      
    }else  if ( product_type === 'live_stream' ) {
        jQuery( '.show_if_live_stream' ).show();
    } else  if ( product_type === 'wpstream_bundle' ) {
        jQuery( '.show_if_wpstream_bundle' ).show();   
        console.log ('we do click');
        var element= jQuery('.general_tab');
        console.log(element);
        jQuery('.general_tab').trigger('click');   
          $('a[href="#general_product_data"]').click();
          $('.product_data_tabs .tab.general_tab').click();
    }
            
  

  
    
    function wpstream_findGetParameter(parameterName) {
        var result = null,
            tmp = [];
        location.search
            .substr(1)
            .split("&")
            .forEach(function (item) {
              tmp = item.split("=");
              if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }


    jQuery('#_subscript_live_event').change(function(){
        //alert('move it'+product_type);
        if ( product_type === 'video_on_demand' || product_type === 'live_stream' || product_type === 'wpstream_bundle' ) {
        
        }else{
            var value= jQuery(this).val();     
            if(value==="no"){
                jQuery("._movie_url_field").parent().removeClass("hide_if_subscription").show();
            }else{
                jQuery("._movie_url_field").parent().addClass("hide_if_subscription").hide();
            }
        }
    });

    jQuery('#_subscript_live_event').trigger('change');
   

    $('#wpstream_product_type').change(function(){
        jQuery('.video_free').hide();
        jQuery('.video_free_external').hide();

        jQuery('.wpstream_option_vod_source').hide();
  
        if( jQuery('#wpstream_product_type').val()=== "2"){
            jQuery('.video_free').show();
            jQuery('.wpstream_show_recording').show();
        }
        if( jQuery('#wpstream_product_type').val()=== "3"){
            jQuery('.video_free_external').show();
            jQuery('.wpstream_show_external').show();
        }
    });
    $('#wpstream_product_type').trigger('change');
    





    $('.close_event').click(function(event){
        event.preventDefault();
        var ajaxurl             =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
        var acesta              =   $(this);
        var parent              =   $(this).parent().parent();
        var notification_area   =   $(this).parent().find('.event_list_unit_notificationx');
        var show_id             =   parseFloat( $(this).attr('data-show-id') );
        var nonce               =   $('#wpstream_start_event_nonce').val();
        //$(this).unbind();
        notification_area.text('Closing Event');
    
        
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action'            :   'wpstream_close_event',
                'security'          :   nonce,
                'show_id'           :   show_id
            },
            success: function (data) {       
                parent.remove();            
            },
            error: function (errorThrown) {
              
            }
        });
        
    });
    
  
    
    
 


});



/*
* Upload images in admin
*
*/
function wpstream_upload_images_in_wpadmin(){
    console.log('wpstream_upload_images_in_wpadmin');
    var idList          = ["category_featured_image_button"];

    for (var i = 0; i < idList.length; i++) {
        var currentId = idList[i];
        jQuery('#'+currentId).on( 'click', function(event) {
            var parent=jQuery(this).parent();
            wpstream_admin_return_uploaded_image().then(function(image) {      
                parent.find('.wpestate_landing_upload').val(image.url);
                parent.find('.wpestate_landing_upload_id').val(image.id);
              
            });
          
        });

    }
}


/*
* return uploaded image
*
*/
function wpstream_admin_return_uploaded_image(){

    return new Promise(function(resolve, reject) {
        var mediaUploader = wp.media({
          frame: "post",
          state: "insert",
          multiple: false
        });
    
        mediaUploader.on("insert", function(){
          var image = mediaUploader.state().get("selection").first().toJSON();
          resolve(image);
        });
    
        mediaUploader.open();
      });
}





/*
* handle video selection for recording
*
*/

function wpstream_handle_video_selection(){

    jQuery('#wpstream_free_video_external_button').on( 'click', function(event) {
        var parent=jQuery(this).parent();
            wpstream_admin_return_uploaded_image().then(function(image) { 
                parent.find('#wpstream_free_video_external').val(image.url);
            });
    });
}



/*
* return uploaded image
*
*/
function wpstream_admin_return_uploaded_image(){


    return new Promise(function(resolve, reject) {
        var mediaUploader = wp.media({
        frame: "post",
        state: "insert",
        multiple: false
        });

        mediaUploader.on("insert", function(){
        var image = mediaUploader.state().get("selection").first().toJSON();
        resolve(image);
        });

        mediaUploader.open();
    });
}

