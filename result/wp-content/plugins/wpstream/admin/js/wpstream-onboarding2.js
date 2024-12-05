const ONBOARD=(function(){

    document.addEventListener('DOMContentLoaded',init);
    const BubbleFreeVod = [
        //free live stream
        [
            {id: 1, 
                selector: "#menu-posts-wpstream_product", 
                title: "Hooray! You just created your first Free-To-View Channel.",
                content:"You can check out all your FTV Channels or create new ones from this menu.<div class='onboard_line_break'></div>Just look for ‘Free-To-View Live Channels’.",
                left:"175px",
                right:"-30px",
                top:"-30px",
                arrow:"boarding-left-arrow"},
            {id: 2, 
                selector: "#wpstream-sidebar-meta", 
                title: "Control your Live Channel",
                content:"Look for the 'Live Streaming' box to the right of the page.",
                left:"-430px",
                right:"60px",
                top:"0px",
                arrow:"boarding-right-arrow"},
            {id: 3, 
                selector: ".wpstream_show_settings_wrapper", 
                title: "Channel Settings",
                content:'Clicking on the <div class="wpstream_sample_icon_settings"></div> icon will let you access the channel settings.<div class="onboard_line_break"></div> You can adjust preferences for <strong>recording, autoplay</strong>, etc.',
                left:"-444px",
                right:"0px",
                top:"100px",
                arrow:"boarding-right-arrow"},
            {id: 4, 
                selector: ".wpstream_stream_browser_wrapper", 
                title: "Turn it ON",
                content:"To Go Live, first TURN ON your channel. Go ahead, click the big green button below! <div id=\"wpstream_onboarding_start_chanel\">TURN ON</div> Turning on may take a minute or so. You can wait or move on to the next step.",
                left:"-444px",
                right:"auto",
                top:"0px",
                arrow:"boarding-right-arrow"},  
                
            {id: 5, 
                selector: ".wpstream_show_settings_wrapper", 
                title: "Your Channel Page",
                content:"This is the link to the page with your live stream. Viewers will be able to watch your live stream here.<div class='onboard_line_break'></div>To see it, click <div id=\"wpstream_onboarding_open_chanel\" class=\"wpstream_sample_icon_settings wpstream_sample_icon_open_channel\"></div> Page will open in a new tab.",
                left:"-400px",
                right:"auto",
                top:"100px",
                arrow:"boarding-right-arrow"},    
            {id: 6, 
                selector: ".wpstream_stream_browser_wrapper", 
                title: "Go LIVE",
                content:"To go live, first turn on your channnel.<div class='onboard_line_break'></div> If it's already <strong>Turning ON</strong>, wait until <strong>Channel is ON</strong>",
                left:"-444px",
                right:"auto",
                top:"58px",
                arrow:"boarding-right-arrow"}
        ]  ,
        // paid live stream
        [
            {id: 1, 
                selector: "#menu-posts-product", 
                title: "Hooray! You just created your first Pay-Per-View Channel.",
                content:"You can check out all your PPV Channels or create new ones from this menu. Just look for ‘Products’.",
                left:"175px",
                right:"-30px",
                top:"-30px",
                arrow:"boarding-left-arrow"},
            {id: 2, 
                selector: "#normal-sortables", 
                title: "A Pay-Per-View Channel is a Custom Woocommerce Product",
                content:"Note the type of the product: <strong>Live Channel</strong> </br>This sets it apart from other products you may want to sell.",
                left:"345px",
                right:"-30px",
                top:"-35px",
                arrow:"boarding-left-arrow"},
            {id: 3, 
                selector: "#normal-sortables", 
                title: "Set the Pay-Per-View price here",
                content:"Your users will have to purchase the product in order to be allowed to watch the live stream.",
                left:"550px",
                right:"-30px",
                top:"25px",
                arrow:"boarding-left-arrow"},
            {id: 4, 
                selector: "#wpstream-sidebar-meta", 
                title: "Control your channel from here",
                content:"Look for the 'Live Streaming' box on the side bar.",
                left:"-430px",
                right:"60px",
                top:"0px",
                arrow:"boarding-right-arrow"},
            {id: 5, 
                selector: ".wpstream_show_settings_wrapper", 
                title: "Channel Settings",
                content:'Clicking on the <div class="wpstream_sample_icon_settings"></div> icon will let you access the channel settings.<div class="onboard_line_break"></div> You can adjust preferences for <strong>recording, autoplay</strong>, etc.',
                left:"-444px",
                right:"0px",
                top:"100px",
                arrow:"boarding-right-arrow"},
            {id: 6, 
                selector: ".wpstream_stream_browser_wrapper", 
                title: "Turn it ON",
                content:"To Go Live, first TURN ON your channel. Go ahead, click the big green button below!<div id=\"wpstream_onboarding_start_chanel\">TURN ON</div> Turning on may take a minute or so. You can wait or move on to the next step.",
                left:"-444px",
                right:"auto",
                top:"0px",
                arrow:"boarding-right-arrow"},  
            {id: 7, 
                selector: ".wpstream_show_settings_wrapper", 
                title: "Your Channel Page",
                content:"This is the link to the page with your live stream. Viewers will be able to watch your live stream here.<div class='onboard_line_break'></div>To see it, click <div id=\"wpstream_onboarding_open_chanel\" class=\"wpstream_sample_icon_settings wpstream_sample_icon_open_channel\"></div> Page will open in a new tab.",
                left:"-385px",
                right:"auto",
                top:"105px",
                arrow:"boarding-right-arrow"},    
            {id: 8, 
                selector: ".wpstream_stream_browser_wrapper", 
                title: "Go LIVE",
                content:"To go live, first turn on your channnel.<div class='onboard_line_break'></div> If it's already <strong>Turning ON</strong>, wait until <strong>Channel is ON</strong>",
                left:"-430px",
                right:"auto",
                top:"50px",
                arrow:"boarding-right-arrow"}
        ] ,
        // free vod
        [
            {id: 1, 
                selector: "#menu-posts-wpstream_product_vod", 
                title: "Hooray! You just created your first FTV Video-On-Demand.",
                content:"You can check out all your FTV VODs or create new ones from this menu. Just look for 'Free-To-View VODs'",
                left:"175px",
                right:"-30px",
                top:"-30px",
                arrow:"boarding-left-arrow"},
            {id: 2, 
                selector: "#add_wpstream_product_metaboxes-sectionid", 
                title: "The FTV VOD can be a Recording, self hosted, or external video",
                content:"Recordings are stored in the WpStream cloud. <div class='onboard_line_break'></div> Self hosted videos are videos in your WordPress Media Library.<div class='onboard_line_break'></div>External videos are videos hosted on YouTube or Vimeo.",
                left:"245px",
                right:"-30px",
                top:"30px",
                arrow:"boarding-left-arrow"},
            {id: 3, 
                selector: "#add_wpstream_product_metaboxes-sectionid", 
                title: "Choose a specific recording to create the VOD from",
                content:"You can create new recordings by recording a live channel or uploading video files directly.",
                left:"245px",
                right:"-30px",
                top:"100px",
                arrow:"boarding-left-arrow"},
            {id: 4, 
                selector: "#post-body", 
                title: "The VOD Page",
                content:"This is the page that your <strong>viewers</strong> see when they <strong>watch</strong> your VOD.<div class='onboard_line_break'></div>  To see it yourself, click the permalink. Page will open in a new tab.  <div id=\"wpstream_onboarding_view_vod\">View VOD Page</div>",
                left:"350px",
                right:"0px",
                top:"-10px",
                arrow:"boarding-left-arrow"},
    
            /*{id: 5, 
                selector: "#post-body", 
                title: "And Thats it",
                content:"That's it , you users will be free to view this Video on Demand",
                left:"350px",
                right:"0px",
                top:"155px",
                arrow:""},
                */
        ] ,
        //paid vod
        [
            {id: 1, 
                selector: "#menu-posts-product", 
                title: "Hooray! You just created your first PPV Video-On-Demand.",
                content:"You can check out all your PPV VODs or create new ones from this menu. Just look for ‘Products’",
                left:"175px",
                right:"-30px",
                top:"-30px",
                arrow:"boarding-left-arrow"},
            {id: 2, 
                selector: "#normal-sortables", 
                title: "A Pay-Per-View VOD is a Custom Woocommerce Product",
                content:"Note the type of the product: <strong>Video On Demand</strong>.<div class='onboard_line_break'></div>This sets it apart from other products you may want to sell.",
                left:"345px",
                right:"-30px",
                top:"-35px",
                arrow:"boarding-left-arrow"},
            {id: 3, 
                selector: "#normal-sortables", 
                title: "Set the Pay-Per-View price here",
                content:"Your users will have to purchase the product in order to be allowed to watch the VOD stream. ",
                left:"550px",
                right:"-30px",
                top:"25px",
                arrow:"boarding-left-arrow"},
            {id: 4, 
                selector: "#normal-sortables", 
                title: "Choose a specific recording to create the VOD from ",
                content:"You can create new recordings by recording a live channel or uploading video files directly." ,
                left:"700px",
                right:"-30px",
                top:"135px",
                arrow:"boarding-left-arrow"},
            {id: 5, 
                selector: "#post-body", 
                title: "The VOD Page",
                content:"This is the page that your <strong>viewers</strong> see when they <strong>watch</strong> your VOD.<div class='onboard_line_break'></div>To see it yourself, click the permalink. Page will open in a new tab.<div class='onboard_line_break'></div><div id=\"wpstream_onboarding_view_vod\">View VOD Page</div>.",
                left:"350px",
                right:"0px",
                top:"-10px",
                arrow:"boarding-left-arrow"},
            {id: 4, 
                selector: "#post-body", 
                title: "Your PPV VOD is now for sale.",
                content:"Upon successful purchase, your viewers will be able to watch it as many times as they like.",
                left:"350px",
                right:"-200px",
                top:"25px",
                arrow:""},
    
    
            
        ]
    
    ];
   
    function init(){
        wpstream_by_pass_login();
        wpstream_onboard_login();
        wpstream_onboard_register();
        wpstream_main_on_boarding_function();
        wpstream_on_boarding_click_actions();
        wpstream_create_free_channel_action();
        wpstream_create_ppv_channel_action();
        wpstream_create_free_vod_action();
        wpstream_create_ppv_vod_action();
        wpstream_local_modal_onboarding();
        wpstream_on_boarding_initial_close();
        wpstream_onboard_initial_bubble_prev_action();
     
    }
    
    /*
    *  Start browser broadcaster
    * 
    **/
    function wpstream_start_camera(){
       
        if( jQuery('.event_list_unit .wpstream_button').hasClass('wpstream_stop_event') ){
            jQuery('.wpstream_sample_icon_camera').css('cursor','pointer');
          
            jQuery('.wpstream_sample_icon_camera').on('click',function(){
                jQuery('.start_webcaster').trigger('click');
                jQuery(this).unbind('click');

            })   
        }
    }
    
    
    /*
    *   ByPass Login if already in
    * 
    **/
    function wpstream_by_pass_login(){
        if(jQuery('#wpstream_have_token').length >0 ){
            
            var nextThing = 'wpstream_step_2';
            jQuery('.wpstream_step_wrapper').hide();
            jQuery('#'+nextThing).show();    
           
    
        }else{
            jQuery('.wpstream_on_board_register_wrapper').show();
            jQuery('#wpstream_onboarding_action_login').show();
        }
        jQuery('.wpstream_check_account_status').hide();
    }
    
    
    
    /*
    * On Board Login
    *
    */

    function wpstream_onboard_login(){
        jQuery('.wpstream_onboard_login').on('click',function(){

            var api_username    =   jQuery('#api_username').val();
            var api_password    =   jQuery('#api_password').val();
            var ajaxurl         =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
            var nonce           =   jQuery('#wpstream_onboarding_nonce').val();
         
        
            jQuery('.wpstream_onboarding_notification').removeClass('onboarding_error').text('Sending data. Please Stand by...').show();
        
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    'action'                    :   'wpstream_on_board_login',
                    'api_username'              :   api_username,
                    'api_password'              :   api_password,
                    'security'                  :   nonce
                },
                success: function (data) {     
                    if(!data.success){
                        jQuery('.wpstream_onboarding_notification').addClass('onboarding_error').text(data.error).show();
                    }else{
                        jQuery('.wpstream_onboarding_notification').text('Login successful, please stand by...').show();
                        setTimeout(function() {

                            var nextThing = 'wpstream_step_2';
                            jQuery('.wpstream_step_wrapper').hide();
                            jQuery('#'+nextThing).show();    
                        
                        }, 2500);
                    }

                
                },
                error: function (errorThrown) { 
                
                }
            });
            
        });
    }



    /*
    * On Board Register
    *
    */
    function wpstream_onboard_register(){
    
        jQuery('.wpstream_onboard_register').on('click',function(){
            wpstream_on_board_actual_register();           
        });


        jQuery('#wpstream_register_email, #wpstream_register_password,#wpstream_register_captcha').keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                wpstream_on_board_actual_register();          
            }
        });

    }


    function wpstream_on_board_actual_register(){
            var button=jQuery(this);
            button.css('pointer-events','none');
         
            var wpstream_register_email         =   jQuery('#wpstream_register_email').val();
            var wpstream_register_password      =   jQuery('#wpstream_register_password').val();
            var ajaxurl                         =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
            var nonce                           =   jQuery('#wpstream_onboarding_nonce').val();
            var wpstream_register_captcha       =   jQuery('#wpstream_register_captcha').val();
            var wpstream_register_captcha_id    =   jQuery('#wpstream_register_captcha_id').val();

          
        
            if(wpstream_register_email ==='' || wpstream_register_password==='' || wpstream_register_captcha===''){
                jQuery('.wpstream_onboarding_notification').addClass('onboarding_error').text('Please fill all the fields!').show();
                button.css('pointer-events','auto');
                return;
            }


            jQuery('.wpstream_onboarding_notification').removeClass('onboarding_error').text('Sending data. Please Stand by...').show();
        
         
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    'action'                    :   'wpstream_on_board_register',
                    'wpstream_register_email'   :   wpstream_register_email,
                    'wpstream_register_password':   wpstream_register_password,
                    'wpstream_register_captcha' :   wpstream_register_captcha,
                    'wpstream_register_captcha_id': wpstream_register_captcha_id,
                    'security'                  :   nonce
                },
                success: function (data) {     
                   
                    if(data.success){
                        if(data.token==='false' || data.token===false){
                            jQuery('.wpstream_onboarding_notification').addClass('onboarding_error').text('We couldn\'t authenticate with your new credentials').show();
                        }else{
                            jQuery('.wpstream_onboarding_notification').text('Register successful, please stand by...').show();
                            setTimeout(function() {

                                var nextThing = 'wpstream_step_2';
                                jQuery('.wpstream_step_wrapper').hide();
                                jQuery('.wpstream_onboarding_notification').empty().hide();
                                jQuery('#'+nextThing).show();    
                            
                            }, 2500);
                        }
                    }else{
                        jQuery('.wpstream_onboarding_notification').addClass('onboarding_error').text(data.message).show();
                        wpstream_fetchCaptcha();
                      
                    }
                    button.css('pointer-events','auto');
                
                },
                error: function (errorThrown) { 
                    button.css('pointer-events','auto');
                }
            });
        
    }



    /*
    * Check and Start the on Board
    *
    */


    async function wpstream_fetchCaptcha () {
      
        var result;
        var requestOptions = {
            method: 'POST',
            redirect: 'follow'
          };
          
          fetch("https://baker.wpstream.net/user/getcapthca", requestOptions)
            .then(response => response.json())
            .then(result => wpstream_process_capthca(result))
            .catch(error => console.log('error', error));

    }


    function wpstream_process_capthca(response){
     

        jQuery('#wpstream_capthca').empty().append(response.capthca);
        jQuery('#wpstream_register_captcha_id').val(response.capthca_id);
        jQuery('#wpstream_register_captcha').empty();
      
    }




    /*
    * Check and Start the on Board
    *
    */


    function wpstream_main_on_boarding_function(){

        jQuery('#wpstream_trigger_quick_start').on('click',function(){
            
            jQuery(".wpstream_on_boarding_wrapper").show();
            jQuery(".wpstream_modal_background_onboard").show();

            jQuery('.wpstream_on_board_login_wrapper,#wpstream_onboarding_action_register,.wpstream_onboarding_notification').hide();
            jQuery('#wpstream_step_1,.wpstream_close_initial_onboarding,.wpstream_on_board_register_wrapper').show(); 
            wpstream_by_pass_login();
        });


        let params = (new URL(document.location)).searchParams;
        let onboard = params.get('onboard_start');
    
        if(onboard!=='yes'){
            return;
        }

        jQuery('.wpstream_on_boarding_wrapper').show();
        jQuery('.wpstream_modal_background_onboard').show();



    }

    /*
    * WpStream on Boarding CLick actions
    *
    */


    function wpstream_on_boarding_click_actions(){
        jQuery('#wpstream_onboarding_action_login').on('click',function(){
            jQuery(this).hide();
            jQuery('.wpstream_onboarding_notification').hide();
            jQuery('.wpstream_on_board_register_wrapper').hide();
            jQuery('.wpstream_on_board_login_wrapper').show();
            jQuery('#wpstream_onboarding_action_register').show();     
        });

        jQuery('#wpstream_onboarding_action_register').on('click',function(){
            jQuery(this).hide();
            jQuery('.wpstream_onboarding_notification').hide();
            jQuery('.wpstream_on_board_login_wrapper').hide();
            jQuery('.wpstream_on_board_register_wrapper').show();
            jQuery('#wpstream_onboarding_action_login').show();     
        });


        jQuery('.wpstream_action_next_step').on('click',function(){
            var nextThing = jQuery(this).attr('data-nextthing');
            jQuery('.wpstream_step_wrapper').hide();
            jQuery('#'+nextThing).show();       
        });
    }


    /*
    * Create free Channel action
    *
    */

    function wpstream_create_free_channel_action(){
        jQuery('#wpstream_on_board_create_channel').on('click',function(){
            wpstream_actual_create_free_channel_action();            
        });
        
        jQuery('#wpstream_onboarding_channel_name').keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                wpstream_actual_create_free_channel_action();      
            }
        });


    }

    function wpstream_actual_create_free_channel_action(){
        jQuery('#wpstream_onboard_live_notice').removeClass('onboarding_error').text('Creating your FTV live channel. Please Stand by...').show();
        var channel_name    =   jQuery('#wpstream_onboarding_channel_name').val();
        var ajaxurl         =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
        var nonce           =   jQuery('#wpstream_onboarding_nonce').val();
        
      
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action'                    :   'wpstream_on_board_create_channel',
                'channel_name'              :   channel_name,
                'security'                  :   nonce
            },
            success: function (data) {     
                
                if(data.success){
                    var new_link = data.link;
                    var decoded = new_link.replace(/&amp;/g, '&');
                    window.location.href=decodeURI(decoded);
                }
            
            },
            error: function (errorThrown) { 
            
            }
        });
    }

    /*
    * Create PPV Channel action
    *
    */

    function wpstream_create_ppv_channel_action(){
        jQuery('#wpstream_onboard_live_ppv_action').on('click',function(){
            wpstream_actual_create_ppv_channel_action();            
        });


        jQuery('#wpstream_onboarding_channel_name_ppv,#wpstream_onboarding_event_price_ppv').keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                wpstream_actual_create_ppv_channel_action();      
            }
        });

    }


    function wpstream_actual_create_ppv_channel_action(){
        jQuery('#wpstream_onboard_live_ppv_notice').removeClass('onboarding_error').text('Creating your Pay Per View live channel. Please Stand by...').show();
     
        var channel_name    =   jQuery('#wpstream_onboarding_channel_name_ppv').val();
        var channel_price   =   jQuery('#wpstream_onboarding_event_price_ppv').val();
        var ajaxurl         =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
        var nonce           =   jQuery('#wpstream_onboarding_nonce').val();
        
    
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action'                    :   'wpstream_on_board_create_channel_ppv',
                'channel_name'              :   channel_name,
                'channel_price'             :   channel_price,
                'security'                  :   nonce
            },
            success: function (data) {     

                if(data.success){
                    var new_link = data.link;
                    var decoded = new_link.replace(/&amp;/g, '&');
                    window.location.href=decodeURI(decoded);
                }
            
            },
            error: function (errorThrown) { 
            
            }
        });
    }


    /*
    * Create Free VOD Action
    *
    */


    function wpstream_create_free_vod_action(){
        jQuery('#wpstream_onboard_vod_free_action').on('click',function(){
            wpstream_actual_create_free_vod_action();
        });

        jQuery('#wpstream_onboarding_vod_name, #wpstream_free_vod_file_name').keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                wpstream_actual_create_free_vod_action();      
            }
        });
    }


    function wpstream_actual_create_free_vod_action(){
        jQuery('#wpstream_onboard_vod_free_notice').removeClass('onboarding_error').text('Creating your VOD. Please Stand by...').show();
        var channel_name    =   jQuery('#wpstream_onboarding_vod_name').val();
        var file_name       =   jQuery('#wpstream_free_vod_file_name').val();
        var ajaxurl         =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
        var nonce           =   jQuery('#wpstream_onboarding_nonce').val();
        
  
        if(file_name===''){
           jQuery('#wpstream_onboard_vod_free_notice').empty().addClass('onboarding_error').show().text('Please select a recording from the list')
           return;
        }

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action'                    :   'wpstream_on_board_create_free_vod',
                'channel_name'              :   channel_name,
                'file_name'                 :   file_name,
                'security'                  :   nonce
            },
            success: function (data) {     
                if(data.success){
                    var new_link = data.link;
                    var decoded = new_link.replace(/&amp;/g, '&');
                    window.location.href=decodeURI(decoded);
                }
            
            },
            error: function (errorThrown) { 
                jQuery('#wpstream_onboard_vod_free_notice').empty().addClass('onboarding_error').show().text('Something did not work .Please try again.')
            }
        });
    }


    /*
    * Create PPV VOD Action
    *
    */


    function wpstream_create_ppv_vod_action(){
        jQuery('#wpstream_onboard_vod_ppv_action').on('click',function(){
            wpstream_actual_create_ppv_vod_action();
        });

        jQuery('#wpstream_onboarding_ppv_vod_name, #wpstream_free_vod_file_name_for_ppv,#wpstream_onboarding_vod_price').keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                wpstream_actual_create_ppv_vod_action();      
            }
        });


    }

    function wpstream_actual_create_ppv_vod_action(){
        jQuery('#wpstream_onboard_vod_ppv_notice').removeClass('onboarding_error').text('Creating your VOD. Please Stand by...').show();

        var channel_name    =   jQuery('#wpstream_onboarding_ppv_vod_name').val();
        var file_name       =   jQuery('#wpstream_free_vod_file_name_for_ppv').val();
        var vod_price       =   jQuery('#wpstream_onboarding_vod_price').val()
        var ajaxurl         =   wpstream_admin_control_vars.admin_url + 'admin-ajax.php';
        var nonce           =   jQuery('#wpstream_onboarding_nonce').val();
        

        if(file_name===''){
           jQuery('#wpstream_onboard_vod_ppv_notice').empty().addClass('onboarding_error').show().text('Please select a recording from the list')
           return;
        }

  
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action'                    :   'wpstream_on_board_create_ppv_vod',
                'channel_name'              :   channel_name,
                'file_name'                 :   file_name,
                'vod_price'                 :   vod_price,
                'security'                  :   nonce
            },
            success: function (data) {     
                if(data.success){
                    var new_link = data.link;
                    var decoded = new_link.replace(/&amp;/g, '&');
                    window.location.href=decodeURI(decoded);
                }
            
            },
            error: function (errorThrown) { 
                jQuery('#wpstream_onboard_vod_ppv_notice').empty().addClass('onboarding_error').show().text('Something did not work .Please try again.')
            }
        });
    }

    /*
    * Start Modal on Particular Pages
    *
    */

    function wpstream_local_modal_onboarding(){
        let params = (new URL(document.location)).searchParams;
        let onboard = params.get('onboard');
    
        if(onboard!=='yes'){
            return;
        } 

        let branch= params.get('branch');
        branch=parseInt(branch);
        
        if(isNaN(branch)){
            return;
        }
        
        branch=branch-1;
       

        var help_bubble_to_insert='<div id="wpstream_onboard_bubble" data-bubble-step="0"><div class="wpstream_close_onboarding"></div>'+
        '<h2 id="wpstream_onboard_bubble_tile"></h2>'+ 
        '<p id="wpstream_onboard_bubble_content"></p>'+
        '<span class="wpstream_onboard_bubble_prev">Prev</span>'+
        '<span class="wpstream_onboard_bubble_next">Next</span>'+
        '<span class="wpstream_onboard_bubble_finish">Finish</span>'+
        // '<ul class="wpstream_round_bubble">';
        // for (let step = 0; step < BubbleFreeVod[branch].length ; step++) {
        //   help_bubble_to_insert=help_bubble_to_insert+'<li></li>';    
        // }

        //help_bubble_to_insert=help_bubble_to_insert+'</ul>'+
        '</div><div class="wpstream_modal_background_onboard2"></div>';

        jQuery('#wpcontent').append(help_bubble_to_insert);

        wpstream_show_bubble(0,branch);
        jQuery('.woocommerce-layout__header').hide();
        jQuery('.wpstream_modal_background_onboard2,#wpstream_onboard_bubble').show();

        wpstream_on_boarding_close();
        wpstream_bubble_controls(branch);

    }


    /*
    * Close initial on boarding
    *
    */

    function wpstream_on_boarding_initial_close(){
      
        jQuery('.wpstream_close_initial_onboarding').on('click',function(){
            var parent_modal=jQuery(this).parent();
            parent_modal.find('.wpstream_close_initial_onboarding,.wpstream_step_wrapper').hide();
            parent_modal.find('.wpstream_close_onboarding_warning').show().html('You can run the wizard again at any time.</br> In the left menu navigate to WpStream -> WpStream Quick Start <div id="wpstrean_close_modal_ack" class="wpstrean_close_modal_ack_action">Okay, Close it now!</div>');
            wpstrean_close_modal_ack_function();
        });
    }


    /*
    * Close ACK
    *
    */
    function wpstrean_close_modal_ack_function(){
        jQuery('.wpstrean_close_modal_ack_action').on('click',function(){

            var parent_modal=jQuery(this).parent();
            parent_modal.hide();
            jQuery('.wpstream_modal_background_onboard2,.wpstream_modal_background_onboard').hide();
            jQuery('.wpstream_on_boarding_wrapper').hide();
            jQuery('#wpstream_onboard_bubble').hide();
        });
    }




    /*
    * Iniial next prev actions
    *
    */

    function wpstream_onboard_initial_bubble_prev_action(){
        jQuery('.wpstream_onboard_initial_bubble_prev').on('click',function(){
            var prev_step = jQuery(this).attr('data-step');
            jQuery('.wpstream_step_wrapper').hide();
            jQuery("#"+prev_step).show();
        });


        jQuery('.wpstream_onboarding_tryagain').on('click',function(){
            location.reload();
        });

        jQuery('.wpstream_install_plugin').on('click',function(){
            var url = wpstreamonboarding_js_vars.plugin_url;
            window.open(url, '_blank'); 
        });

        jQuery('.wpstream_upload_video').on('click',function(){
            var url = wpstreamonboarding_js_vars.upload_url;
            window.open(url, '_blank'); 
        });

    }



    /*
    * Show MOdal/Bubble for local pages
    *
    */

    function wpstream_show_bubble(current_bubble_step,branch){
      
        var current_bubble      =   jQuery('#wpstream_onboard_bubble');
        current_bubble.detach();
        
        var new_info        =   BubbleFreeVod[branch][current_bubble_step];
        var new_title       =   new_info.title;
        var new_selector    =   new_info.selector;
        var new_content     =   new_info.content;
        var new_css         =   new_info.css;
        var new_left        =   new_info.left;
        var new_top         =   new_info.top;
        var new_right       =   new_info.right;
        var new_arrow       =   new_info.arrow;

        current_bubble.find('#wpstream_onboard_bubble_tile').html(new_title);
        current_bubble.find('#wpstream_onboard_bubble_content').html(new_content);
        current_bubble.attr('data-bubble-step',current_bubble_step);
        current_bubble.removeClass('boarding-left-arrow boarding-right-arrow');
        current_bubble.addClass(new_arrow);
        current_bubble.css({
            'left':new_left,
            'right':new_right,
            'top':new_top
        });


        if( BubbleFreeVod[branch].hasOwnProperty(parseInt(current_bubble_step+1)) ){
            current_bubble.find('.wpstream_onboard_bubble_next').show();
            current_bubble.find('.wpstream_onboard_bubble_finish').hide();
        }else{
            current_bubble.find('.wpstream_onboard_bubble_next').hide();
            current_bubble.find('.wpstream_onboard_bubble_finish').show().css('display','inline-block');
        }



        jQuery(new_selector).append(current_bubble);

        jQuery('html, body').animate({
            scrollTop: jQuery(new_selector).offset().top-200
        }, 1000);

        wpstream_on_boarding_trigger_event_start_channel();
        wpstream_on_boarding_trigger_event_open_channel();
        wpstream_on_boarding_open_vod_page();
        wpstream_start_camera();
            
    }


    /*
    * Trigger Start Event
    *
    */

    function wpstream_on_boarding_trigger_event_start_channel(){
        if(jQuery('.start_event').hasClass('wpstream_turning_on')){
          
            jQuery('#wpstream_onboarding_start_chanel').hide();
            var bubble_Step =jQuery('#wpstream_onboard_bubble').attr('data-bubble-step');


            var check_against='3';
            if(jQuery('#woocommerce-product-data').length>0){
                check_against='5';
            }
  

            if(bubble_Step===check_against ){
                jQuery('#wpstream_onboard_bubble_tile').text('Turning ON ');
                jQuery('#wpstream_onboard_bubble_content').text('Good, the channel is now turning on. This may take a minute or so. You can wait or move on to the next step.');
            }

        }else if( jQuery('.event_list_unit .wpstream_button').hasClass('wpstream_stop_event') ){    
            jQuery('#wpstream_onboarding_start_chanel').hide();

            var bubble_Step =jQuery('#wpstream_onboard_bubble').attr('data-bubble-step');
            var check_against='3';
            check_against_camera_icon='5';
            if(jQuery('#woocommerce-product-data').length>0){
                check_against='5';
                check_against_camera_icon='7';
            }
          

            if(bubble_Step===check_against){
                jQuery('#wpstream_onboard_bubble_tile').text('Channel is now ON');
                jQuery('#wpstream_onboard_bubble_content').text('You are ready to GO LIVE. Click Next to see how.');
            }
            

            if(bubble_Step===check_against_camera_icon){
                jQuery('#wpstream_onboard_bubble_tile').html('Go LIVE');
                jQuery('#wpstream_onboard_bubble_content').html('Go Live now, click the <div class=\"wpstream_sample_icon_settings wpstream_sample_icon_camera\"></div> icon. The broadcast app will open in a new window.');
                wpstream_start_camera();
            }
        



        }else {
            jQuery('#wpstream_onboarding_start_chanel').show();
      
            jQuery('#wpstream_onboarding_start_chanel').on('click',function(){
                start_onboarding = 'yes';
                jQuery('.start_event.wpstream_button').trigger('click');
                jQuery('#wpstream_onboarding_start_chanel').unbind('click');
                jQuery('#wpstream_onboard_bubble_tile').text('Turning ON');
                jQuery('#wpstream_onboard_bubble_content').text('Good, the channel is now turning on. This may take a minute or so. You can wait or move on to the next step.');
            })
            
        }
        
    }


    /*
    * Trigger open page
    *
    */

    function wpstream_on_boarding_trigger_event_open_channel(){
        jQuery('#wpstream_onboarding_open_chanel').on('click',function(){
            var link = jQuery('.wpstream_view_channel').attr('href');
            window.open(link, '_blank');
        })
    }


    /*
    * Trigger VOD Page
    *
    */

    function wpstream_on_boarding_open_vod_page(){
        jQuery('#wpstream_onboarding_view_vod').on('click',function(event){
            event.stopPropagation();
            var link = jQuery('#sample-permalink a').attr('href');
            window.open(link, '_blank');

        });
    }



    /*
    * Close Modal
    *
    */
    function wpstream_on_boarding_close(){
        jQuery('.wpstream_close_onboarding,.wpstream_onboard_bubble_finish').on('click',function(){

            jQuery('.wpstream_onboard_bubble_finish').hide();
            var parent_modal=jQuery(this).parent();
            
            parent_modal.find('.wpstream_close_onboarding').hide();
            parent_modal.find('#wpstream_onboard_bubble_tile').html('You did it!');
            parent_modal.find('.wpstream_onboard_bubble_prev,.wpstream_onboard_bubble_next').hide();
    
            parent_modal.find('#wpstream_onboard_bubble_content').html('You can run the wizard again at any time. </br>In the left menu navigate to WpStream -> WpStream Quick Start </br> <div id="wpstrean_close_modal_ack" class="wpstrean_close_modal_ack_action">Okay, Close it now!</div>');
        // parent_modal.find('#wpstream_onboard_bubble_content').after('<div id="wpstrean_close_modal_ack" class="wpstrean_close_modal_ack_action">Okay, Close it now!</div>');
            
            wpstrean_close_modal_ack_function();
        });
    }


    /*
    * Bubble/Modal Controls
    * 
    */


    function wpstream_bubble_controls(branch){

        jQuery('.wpstream_onboard_bubble_next,.wpstream_onboard_bubble_prev').on('click',function(){
    
            var current_bubble_step =   jQuery('#wpstream_onboard_bubble').attr('data-bubble-step');
            
            current_bubble_step     =   parseInt(current_bubble_step,10);
            if( jQuery(this).hasClass('wpstream_onboard_bubble_next')){
                current_bubble_step++;
            }else{
                current_bubble_step--;
            }
        
            if( current_bubble_step<0){ 
                current_bubble_step=0;
                var url      = window.location.href;  
                jQuery('#wpstream_onboard_bubble,.wpstream_modal_background_onboard2').hide();
                
                jQuery('.wpstream_on_boarding_wrapper').show();
                jQuery('.wpstream_modal_background_onboard').show();
            
            }

            var max_length = BubbleFreeVod[branch].length-1;

            if( current_bubble_step>max_length){ 
                current_bubble_step=max_length;
            }
            
            wpstream_show_bubble(current_bubble_step,branch)

        

        })
    }


    return {

    }
})();