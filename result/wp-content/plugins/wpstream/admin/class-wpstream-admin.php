<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wpstream.net
 * @since      3.0.1
 *
 * @package    Wpstream
 * @subpackage Wpstream/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpstream
 * @subpackage Wpstream/admin
 * @author     wpstream <office@wpstream.net>
 */
class Wpstream_Admin {
        
    
        /**
         * Store plugin main class to allow public access.
         *
         * @since    20180622
         * @var object      The main class.
         */
        public $main;


    /**
     * The ID of this plugin.
     *
     * @since    3.0.1
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    3.0.1
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    3.0.1
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
        
         public   $global_event_options ;
         
         
         
         
            
    public function __construct( $plugin_name, $version,$plugin_main ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
                $this->main = $plugin_main;

           
                
                $this->global_event_options = array(
               
                'record'    =>array(
                                'name'      =>  esc_html__('Record Live Stream','wpstream'),
                                'details'   =>  esc_html__('If enabled, live streams will be recorded and saved to your library.','wpstream'),
                                'defaults'  =>  'no',
                    
                            ),
                'view_count' =>array(
                                'name'      =>  esc_html__('Display Viewer Count','wpstream'),
                                'details'   =>  esc_html__('If enabled, the live viewer count will show up in the player.','wpstream'),
                                'defaults'  =>  'yes',
                            ),
                'domain_lock'=>array(
                                'name'      =>  esc_html__('Lock To Website','wpstream'),
                                'details'   =>  sprintf ( esc_html__('If enabled, live video will only display on %1$s, otherwise it can show up on any website.','wpstream'),get_bloginfo('wpurl') ),
                                'defaults'  =>  'no',
                            ),
                'autoplay'    =>array(
                                'name'      =>  esc_html__('Autoplay','wpstream'),
                                'details'   =>  esc_html__('If enabled, live video will attempt to start playing automatically. This is only achievable in some browsers.','wpstream'),
                                'defaults'  =>  'yes',
                            ),
                'mute'    =>array(
                                'name'      =>  esc_html__('Start Muted','wpstream'),
                                'details'   =>  esc_html__('If enabled, live video will start muted. This may increase the rate of autoplay in some browsers. ','wpstream'),
                                'defaults'  =>  'no',

                            ),
                'low_latency'   =>array(
                                'name'      =>  esc_html__('Low Latency (beta)','wpstream'),
                                'details'   =>  esc_html__('Shortens the live delay between streamer and viewers. Useful for interactive applications like gaming, auctions, trading etc. Low latency may worsen the viewer experience on some devices.','wpstream'),
                                'defaults'  =>  'no',
                            ),
                'adaptive_bitrate'   =>array(
                                'name'      =>  esc_html__('Adaptive Bitrate (beta)','wpstream'),
                                'details'   =>  esc_html__('Ensures a smooth and uninterrupted viewing experience by adjusting video quality for viewers with reduced network speed or device capabilities.','wpstream'),
                                'defaults'  =>  'no',
                            ),
                'encrypt'   =>array(
                                'name'      =>  esc_html__('Encrypt Live Stream','wpstream'),
                                'details'   =>  esc_html__('If enabled, video data will be encrypted. Enabling encryption may lead to reduced website performance under certain configurations. Encrypted video may not display in all browsers.','wpstream'),
                                'defaults'  =>  'no',
                            ),
                'ses_encrypt'=>array(
                                'name'      =>  esc_html__('Use Sessions with Encryption','wpstream'),
                                'details'   =>  esc_html__('If enabled, encryption key distribution will be checked against valid user sessions. Setting may malfunction or lead to reduced website performance under certain configurations. ','wpstream'),
                                'defaults'  =>  'no',
                            ),
                'autostart'    =>array(
                    'name'      =>  esc_html__('Auto TURN ON','wpstream'),
                    'details'   =>  esc_html__('If enabled, channel will TURN ON automatically when broadcasting with an External Streaming App (RTMP Encoder/Broadcaster)','wpstream'),
                    'defaults'  =>  'no',
                        ),

              
            );

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    3.0.1
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wpstream_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wpstream_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( 'wpstream-roboto', "https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700,900&display=swap&subset=latin-ext" );  
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpstream-admin.css', array(), WPSTREAM_PLUGIN_VERSION, 'all' );
    
        wp_enqueue_style( 'wpstream-on-boarding-css', plugin_dir_url( __FILE__ ) . 'css/wpstream-admin-onboarding.css', array(), rand(1,999999999999), 'all' );
    
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    3.0.1
     */
    public function enqueue_scripts() {

                wp_enqueue_script("jquery-ui-slider");
                wp_enqueue_script("jquery-ui-datepicker");  
                wp_enqueue_script('jquery.fileupload',   plugin_dir_url( __FILE__ ) .'js/jquery.fileupload.js?v='.time(),array(), WPSTREAM_PLUGIN_VERSION, true);  
                wp_enqueue_script('wpstream-admin-control',   plugin_dir_url( __FILE__ ) .'js/admin_control.js?v='.time(),array(),  WPSTREAM_PLUGIN_VERSION, true); 
                wp_localize_script('wpstream-admin-control', 'wpstream_admin_control_vars', 
                    array( 
                        'admin_url'             =>  get_admin_url(),
                        'loading_url'           =>  WPSTREAM_PLUGIN_DIR_URL.'/img/loading.gif',
                        'download_mess'         =>  esc_html__('Click to download!','wpstream'),
                        'uploading'             =>  esc_html('We are uploading your file.Do not close this window!','wpstream'),
                        'upload_complete2'      =>  esc_html('Upload Complete! You can upload another file!','wpstream'),
                        'not_accepted'          =>  esc_html('The file is not an accepted video format','wpstream'),
                        'upload_complete'       =>  esc_html('Upload Complete!','wpstream'),
                        'no_band'               =>  esc_html('Not enough streaming data.','wpsteam'),
                        'no_band_no_store'      =>  esc_html('Not enough streaming data or storage.','wpsteam')

                    ));
                
                
                
                wp_enqueue_script('wpstream-start-streaming_admin',   plugin_dir_url( __DIR__  ) .'/public/js/start_streaming.js?v='.time(),array(),  WPSTREAM_PLUGIN_VERSION, true); 
                wp_localize_script('wpstream-start-streaming_admin', 'wpstream_start_streaming_vars', 
                    array( 
                        'admin_url'             =>  get_admin_url(),
                        'loading_url'           =>  WPSTREAM_PLUGIN_DIR_URL.'/img/loading.gif',
                        'download_mess'         =>  esc_html__('Click to download!','wpstream'),
                        'uploading'             =>  esc_html('We are uploading your file.Do not close this window!','wpstream'),
                        'upload_complete2'      =>  esc_html('Upload Complete! You can upload another file!','wpstream'),
                        'not_accepted'          =>  esc_html('The file is not an accepted video format','wpstream'),
                        'upload_complete'       =>  esc_html('Upload Complete!','wpstream'),
                        'no_band'               =>  esc_html('Not enough streaming data.','wpsteam'),
                        'no_band_no_store'      =>  esc_html('Not enough streaming data or storage.','wpsteam'),

                        'start_streaming_action'=>  esc_html__('TURNING ON','wpstream'),
                        'stop_streaming_action' =>  esc_html__('TURNING OFF','wpstream'),
                        'start_streaming'       =>  esc_html__('TURN ON','wpstream'),
                        'stop_streaming'        =>  esc_html__('TURN OFF','wpstream'),
                        'turned_on_tooltip'     =>  esc_html__('Channel is now OFF. Click to turn ON.','wpstream'),
                        'turned_off_tooltip'    =>  esc_html__('Click to turn channel off. This will interrupt any ongoing broadcast.','wpstream'),                     
                        'turning_on_tooltip'    =>  esc_html__('Turning a channel on may take 1-2 minutes or more. Please be patient.','wpstream'),
                        'turning_off_tooltip'   =>  esc_html__('This may take a few minutes.','wpstream'),
                        'error1'                =>  esc_html__('You don\'t have enough data to start a new event!','wpstream'),
                        'failed_event_creation' =>  esc_html__('Failed to start the channel. Please try again in a few minutes.','wpstream'),
                        'channel_turning_on'    =>  esc_html__('Channel is turning on','wpstream'),
                        'channel_turning_off'   =>  esc_html__('Channel is turning off','wpstream'),
                        'channel_on'            =>  esc_html__('Channel is ON','wpstream'),
                        'channel_off'           =>  esc_html__('Channel is OFF','wpstream'),
                        'turn_off_confirm'      =>  esc_html__('ARE YOU SURE you\'d like to TURN OFF the channel now? '.PHP_EOL.PHP_EOL.'Channels TURN OFF automatically after 1 hour of inactivity (no active broadcast).'.PHP_EOL.PHP_EOL.'Manual TURN OFF is only useful if you require to change the channel settings immediately.'.PHP_EOL.PHP_EOL.'Statistics may be unavailable or incomplete for up to an hour.'.PHP_EOL.PHP_EOL.'If your channel is configured with Auto TURN ON, it will turn back on as soon as there is a broadcast.','wpstream')
                    ));


                    wp_enqueue_script('wpstream-on-boarding-js',   plugin_dir_url( __DIR__  ) .'/admin/js/wpstream-onboarding2.js?v='.time(),array(),  WPSTREAM_PLUGIN_VERSION, true); 
                    wp_localize_script('wpstream-on-boarding-js', 'wpstreamonboarding_js_vars', 
                        array( 
                            'admin_url'             =>  get_admin_url(),
                            'plugin_url'            =>  get_dashboard_url().'/plugins.php',
                            'upload_url'            =>  get_dashboard_url().'admin.php?page=wpstream_recordings'
                   
                    ));     
                
        }
         
        
        /**
     * Add Plugin Administation menu
     *
     * @since    3.0.1 
     */
        
        public function wpstream_manage_admin_menu() {

            add_menu_page( __('WpStream','wpestream'), __('WpStream ','wpstream'), 'administrator', 'wpstream_credentials', array($this,'wpstream_set_wpstream_credentials'), WPSTREAM_PLUGIN_DIR_URL.'img/wpstream-icon-menu_2.png',20 );
            add_submenu_page( 'wpstream_credentials', __('WpStream Credentials','wpestream'),          __('Credentials','wpestream'),          'administrator', 'wpstream_credentials',      array($this,'wpstream_set_wpstream_credentials') );
            add_submenu_page( 'wpstream_credentials', __('WpStream Live Channels','wpestream'),         __('All Channels','wpestream'),   'administrator', 'wpstream_live_channels',    array( $this,'wpstream_new_general_set'));
            add_submenu_page( 'wpstream_credentials', __('WpStream Recordings','wpestream'), __('Recordings','wpestream'),  'administrator', 'wpstream_recordings',   array($this,'wpstream_media_management'));
            add_submenu_page( 'wpstream_credentials', __('WpStream Settings','wpestream'),         __('Settings','wpestream'),  'administrator', 'wpstream_settings',   array($this,'wpstream_settings'));
      
            add_submenu_page( 'wpstream_credentials', __('WpStream Quick Start','wpestream'),         __('WpStream Quick Start','wpestream'),  'administrator', 'wpstream_onboard',   array($this,'wpstream_pre_onboard_display'));
      

      
      
        }

           
     
        
        /**
        * Shows events wpstream
        *
        * @since    3.0.1 
        */
        
        public function wpstream_new_general_set() {  

            $no_channel=1;

            if(class_exists ('WC_Subscription')){

            }

            //event_passed
            $args = array(
                'posts_per_page'    => -1,
                'post_type'         => 'product',
                'post_status'       => 'publish',
                'meta_query'        =>      array(
                                                array(
                                                        'key'     => 'event_passed',
                                                        'value'   => 1,
                                                        'compare' => '!=',
                                                )
                                            ),

                'tax_query'         => array(
                                        'relation'  => 'AND',
                                        array(
                                            'taxonomy'  =>  'product_type',
                                            'field'     =>  'slug',
                                            'terms'     => array('live_stream','subscription')
                                        )
                                    ),
            );



            $event_list = new WP_Query($args);
            global $live_event_for_user;
            $live_event_for_user    =    $this->main->wpstream_live_connection->wpstream_get_live_event_for_user();
            $pack_details           =    $this->main->wpstream_live_connection->wpstream_request_pack_data_per_user();

            $this->main->show_user_data($pack_details);
            if( $event_list->have_posts()){
               
                
                print '<div class="pack_details_wrapper_transparent">
                <h3>'.__('Your Pay-Per-View Channel List','wpstream').'</h3>';

            
                $link_new   =   admin_url('post-new.php?post_type=product').'&new_stream='. rawurlencode('new');
                  
                print '<a href="'.esc_url($link_new).'"  class="wpstream_create_new_product_link">'.esc_html__('Create new Pay-Per-View channel.','wpstream').'</a>';
                print '</div>';

                print '<div style="clear: both;"></div><div class="event_list_wrapper">';

                    while ($event_list->have_posts()): $event_list->the_post();

                        $the_id                     =   get_the_ID();
                        $is_subscription_live_event =   esc_html(get_post_meta($the_id,'_subscript_live_event',true));
                        $term_list                  =   wp_get_post_terms($the_id, 'product_type');

                        if( $term_list[0]->name=='subscription' && $is_subscription_live_event=='no'){
                            continue;
                        }

                        $this->wpstream_live_stream_unit($the_id);

                    endwhile;

                print'</div>'; 
                $no_channel=1;
            }else{
                $no_channel=0;
            }


            $ajax_nonce = wp_create_nonce( "wpstream_start_event_nonce" );
            print '<input type="hidden" id="wpstream_start_event_nonce" value="'.$ajax_nonce.'">';
            $current_user       =   wp_get_current_user();
            $allowded_html      =   array();
            $userID             =   $current_user->ID;
            $user_live_streams  =   get_user_meta($userID,'live_shows');


            wp_reset_postdata();




            // free 

            $args_free = array(
                'posts_per_page'    => -1,
                'post_type'         => 'wpstream_product',
                'post_status'       => 'publish',
                

            );
            $event_list_free = new WP_Query($args_free);


            if( $event_list_free->have_posts()){
                print '<div class="pack_details_wrapper_transparent">
                <h3>'.__('Free-To-View Channels','wpstream').'</h3>';

                $link_new = admin_url('post-new.php?post_type=wpstream_product');
                print '<a href="'.esc_url($link_new).'" class="wpstream_create_new_product_link">'.esc_html__('Create new Free-To-View channel.','wpstream').'</a>';
                print '</div>';
                print '<div style="clear: both;"></div><div class="event_list_wrapper">';

                    while ($event_list_free->have_posts()): $event_list_free->the_post();


                        $the_id =   get_the_ID();

                        if( get_post_meta ($the_id,'event_passed',true)!=1){
                            $this->wpstream_live_stream_unit($the_id);
                        }

                    endwhile;

                print'</div><div class="wpstream_modal_background"></div>';    
                print '<div class="wpstream_error_modal_notification"><div class="wpstream_error_content">er2</div>
                <div class="wpstream_error_ok wpstream_button" type="button">'.esc_html__('Close','wpstream').'</div>
                </div>';
                $no_channel=1;
            }else{
                $no_channel=0;
            }



     
                $link_new_paid = admin_url('post-new.php?post_type=product').'&new_stream='. rawurlencode('new');
                $link_new_free = admin_url('post-new.php?post_type=wpstream_product');
                print '<div class="no_events_warning"> ';
                if($event_list->found_posts==0){ 
                    print '<div class="no_events_warning_mes">'.__('* You do not have any Pay-Per-View channels!','wpstream').'</div>';
                }
                if($event_list_free->found_posts==0){ 
                    print '<div class="no_events_warning_mes">'. __('* You do not have any free channels!','wpstream').'</div>';
                }
               
                print '<a href="'.esc_url($link_new_free).'" class="wpstream_no_chanel_add_channel">'.esc_html__('Add new Free-To-View channel ','wpstream').'</a>';
                print '<a href="'.esc_url($link_new_paid).'" class="wpstream_no_chanel_add_channel">'.esc_html__('Add new Pay-Per-View channel ','wpstream').'</a>';        

                print '</div>';
            



        }
        // end   wpstream_new_general_set  


        /**
        * Social share
        *
        * @since    3.0.1 
        */
        
        public function wpstream_social_share($the_id){
                $protocol       =   is_ssl() ? 'https' : 'http';
                $pinterest      =   wp_get_attachment_image_src(get_post_thumbnail_id($the_id), 'full');
                $link           =   esc_url ( get_permalink($the_id) );
                $title          =   get_the_title($the_id);
                $twiter_status  =   urlencode( $title.' '.$link);
                $email_link     =   'subject='.urlencode ( $title ) .'&body='. urlencode( esc_url($link));

                $facebook_link = esc_html($protocol).'://www.facebook.com/sharer.php?u='. esc_url($link) .'&amp;t='. urlencode(get_the_title());

                ?>
                <div class="wpstream_social_share_wrapper">
       
                    <a href="<?php print esc_url( $facebook_link); ?>" target="_blank" class="social_facebook wpstream_sharing_social">
                        <span class="dashicons dashicons-facebook-alt"></span>
                    </a>
                
                    <a href="<?php print esc_html($protocol);?>://twitter.com/intent/tweet?text=<?php echo esc_html($twiter_status); ?>" class="social_tweet wpstream_sharing_social" target="_blank">
                        <span class="dashicons dashicons-twitter"></span>
                    </a>

                    <a href="<?php print esc_html($protocol);?>://pinterest.com/pin/create/button/?url=<?php echo esc_url($link); ?>&amp;media=<?php if (isset( $pinterest[0])){ echo esc_url($pinterest[0]); }?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="social_pinterest wpstream_sharing_social">
                        <span class="dashicons dashicons-pinterest"></span>
                    </a>
              
                    <a href="<?php print esc_html($protocol);?>://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title().' '. esc_url( $link )); ?>" class="social_whatsup wpstream_sharing_social" target="_blank">
                        <span class="dashicons dashicons-whatsapp"></span>
                    </a>

                    <a href="<?php print esc_html($protocol);?>://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(esc_url($link)); ?>" class="social_linkedin wpstream_sharing_social" target="_blank">
                        <span class="dashicons dashicons-linkedin"></span>
                    </a>

                    <a href="<?php print esc_html($protocol);?>:///www.reddit.com/submit?url==<?php echo urlencode(esc_url($link)); ?>" class="social_linkedin wpstream_sharing_social" target="_blank">
                        <span class="dashicons dashicons-reddit"></span>
                    </a>

                    <a href="mailto:email@email.com?<?php echo trim(esc_html($email_link));?>" data-action="share email"  class="social_email wpstream_sharing_social">
                      <span class="dashicons dashicons-email-alt"></span>
                    </a>

                    <div class="wpstream_modal_explanations"> <?php print esc_html__('Spread the word! To let people know about your channel, click on the corresponding icon and share on the social platforms of your choice. The more the merrier!','wpstream'); ?> </div>
                    <?php                  
                print '</div>';
        }
        
        
        
        
        /**
        * Shows event unit card in admin
        *
        * @since    3.0.1 
        */

        public function wpstream_live_stream_unit($the_id,$is_front=''){
            global $live_event_for_user;
            global $wpstream_plugin;
            $current_user       =   wp_get_current_user();
          
            if( !current_user_can('administrator')){
                if($is_front=='' ){
                    print '<div class="event_list_unit">';
                    esc_html_e('You are not allowed to broadcast.','wpstream');
                    print '</div>';
                    return;
                }
        
            }
            
            if( !$this->main->wpstream_check_user_can_stream()  ){
                print '<div class="event_list_unit">';
                esc_html_e('You are not allowed to broadcast','wpstream');
                print '</div>';
                return;
            }
       

            $live_class='';
            if(isset($live_event_for_user[$the_id])) {
                $live_class=" wpstream_show_started";
            }


            if(has_post_thumbnail($the_id)){
                $thumb  =   get_the_post_thumbnail_url($the_id,'thumbnail');
            }else{
                $thumb= plugin_dir_url( dirname( __FILE__ ) ). 'img/plugin-logo.png';
            }

            $pending_streaming_class            =   'hide_stream_data';
            $external_software_streaming_class  =   '';
            $obs_uri                            =   '';
            $obs_stream                         =   '';
            $live_data_url                      =   '';

            if( $live_event_for_user=='' && $is_front=='front' ){
                $live_event_for_user    =    $this->main->wpstream_live_connection->wpstream_get_live_event_for_user();
            }
            $channel_status                 =   esc_html__('Channel is OFF','wpstream');
            if(is_array($live_event_for_user) && isset($live_event_for_user[$the_id])) {
                $pending_streaming_class        =   'pending_trigger';
                $live_data_url                  =   get_post_meta($the_id,'qos_url',true);
              //  $channel_status                 =   esc_html__('Channel is on','wpstream');
            }

            $server_id      =   get_post_meta($the_id,'server_id',true);
            $obs_uri        =   get_post_meta($the_id,'obs_uri',true);
            $obs_stream     =   get_post_meta($the_id,'obs_stream',true);
            $webcaster_url  =   get_post_meta($the_id,'webcaster_url',true);
            $rtmp_ip_uri    =   '';

            print '<div class="event_list_unit '.$live_class.' '.$pending_streaming_class.' event_unit_style_'.esc_attr($is_front).'"  data-show-id="'.intval($the_id).'" data-server-id="'.$server_id.'" data-server-url="'.$rtmp_ip_uri.'"">';

                print '<div class="wpstream_channel_status">'.$channel_status.'</div>';

                print '<div class="server_notification"></div>';

                print '<div class="event_thumb_wrapper" style="background-image:url('.$thumb.')"></div>';
               
                print '<div class="event_title" data-prodid="'.$the_id.'">'.wp_trim_words(get_the_title($the_id),10); 

            
                    /*    print '***</br>'.get_post_meta($the_id,'obs_uri',true) .'***</br>'.
                        get_post_meta($the_id,'obs_stream',true).'***</br>'.   
                        get_post_meta($the_id,'broadcast_url',true);
                    */
                    print '<div class="wpstream_channel_item_id">'.esc_html('#ID','wpstream').' '.$the_id.'</div>';
                print '</div>';
            

                print '<div class="start_event wpstream_button wpstream_tooltip_wrapper"  data-show-id="'.$the_id.'" > '.esc_html__('TURN ON','wpstream');
                    print '<div class="wpstream_tooltip">'.esc_html__('Channel is now OFF. Click to turn ON.','wpestream').'</div>'; 
                print '</div>';
                          
                print '<div class="wpstream_options_col1 wpstream_stream_browser_wrapper">';
                   
                    print '<div class="wpstream_inactive_icon start_webcaster wpstream_stream_browser wpstream-button-icon wpstream_tooltip_wrapper"  data-webcaster-url="'.$webcaster_url.'" data-show-id="'.$the_id.'"">';
                       
                        print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn ON the channel to go live.','wpestream').'</div>'; 
                        print '<div class="wpstream_tooltip">'.esc_html__('Go live with your webcam','wpestream').'</div>'; 

                        print '<svg width="41" height="51" viewBox="0 0 41 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M40.7646 44.2989C39.8782 41.4216 38.3361 38.7814 36.181 36.4522C35.7274 35.9619 35.1629 35.3518 34.4858 34.8356C34.0448 34.4996 33.4262 34.5281 33.0182 34.9035C29.358 38.2714 25.2628 39.9088 20.4979 39.9088H20.4853C15.7103 39.9059 11.6117 38.264 7.95648 34.889C7.52404 34.4896 6.85874 34.484 6.41953 34.8764C3.30565 37.6569 1.20501 40.9206 0.175434 44.5778C-0.276291 46.1833 0.15652 47.8497 1.33328 49.0353C2.22709 49.9362 3.39869 50.4173 4.61958 50.4173C5.0357 50.4173 5.45813 50.3613 5.87645 50.247C10.147 49.081 14.0772 48.4108 17.8916 48.1983C21.1954 48.014 24.4465 48.1323 27.5541 48.5495C29.9559 48.8718 32.4237 49.3888 35.0996 50.1299C36.823 50.6076 38.459 50.1562 39.7059 48.86C40.9268 47.5901 41.2933 46.0132 40.7651 44.2994L40.7646 44.2989ZM38.0572 47.274C37.3825 47.9754 36.6367 48.1823 35.7092 47.9257C32.9321 47.1565 30.3638 46.6187 27.8578 46.2827C24.6079 45.8465 21.2117 45.723 17.7643 45.9151C13.7874 46.1365 9.70183 46.8319 5.27439 48.041C4.43435 48.2706 3.5673 48.0403 2.9568 47.425C2.35932 46.8227 2.14793 46.0108 2.37712 45.1975C3.21567 42.2171 4.7982 39.6162 7.2018 37.2734C11.0641 40.5378 15.5276 42.193 20.4845 42.196H20.4978C25.4557 42.196 29.9251 40.5386 33.7967 37.2667C34.024 37.4904 34.254 37.7359 34.5032 38.0059C36.4236 40.0816 37.7951 42.4256 38.5799 44.9728C38.8603 45.8811 38.6941 46.6125 38.0581 47.274L38.0572 47.274ZM20.4954 30.7538C13.8088 30.7523 8.54664 25.4128 8.54379 18.6256C8.5412 12.2624 13.9961 6.83353 20.3776 6.84872C27.1094 6.86467 32.4526 12.1652 32.4488 18.8231C32.4447 25.4243 27.1038 30.7547 20.4954 30.7538V30.7538ZM29.4401 18.8383C29.5559 13.9512 25.4358 9.8445 20.5347 9.83671C15.5301 9.82892 11.6488 13.9279 11.5328 18.5667C11.4086 23.5202 15.344 27.7103 20.3381 27.7926C25.5542 27.8783 29.554 23.5758 29.4401 18.8383V18.8383ZM20.4917 25.1342C17.2131 25.2436 14.1541 22.4349 14.1567 18.8924C14.1593 15.2627 16.9642 12.4191 20.5858 12.4662C24.0109 12.5111 26.8269 15.2466 26.8362 18.8471C26.8451 22.4293 23.782 25.2436 20.4917 25.1342V25.1342ZM18.5119 13.9982C17.3006 13.9908 15.6202 15.656 15.6295 16.8543C15.6328 17.3209 15.961 17.661 16.3983 17.6513C17.5973 17.6246 19.2281 16.0169 19.2696 14.8205C19.2881 14.2742 19.0371 14.0016 18.5119 13.9982L18.5119 13.9982ZM20.4964 0.568359C10.4428 0.568359 2.26333 8.74761 2.26333 18.8014C2.26333 28.8551 10.4426 37.0345 20.4964 37.0345C30.5502 37.0345 38.7295 28.8553 38.7295 18.8014C38.7291 8.7478 30.5502 0.568359 20.4964 0.568359V0.568359ZM20.4964 33.3886C12.4528 33.3886 5.90919 26.8449 5.90919 18.8014C5.90919 11.3105 11.585 5.12093 18.8624 4.30631C18.8561 4.36009 18.8528 4.41423 18.8528 4.46912C18.8468 5.37368 19.6112 6.16064 20.4927 6.15846C21.3636 6.15586 22.1283 5.39706 22.142 4.52143C22.1431 4.44874 22.1372 4.3768 22.1279 4.30596C29.4067 5.11929 35.0849 11.3092 35.0849 18.802C35.0842 26.8456 28.5404 33.3892 20.4968 33.3892L20.4964 33.3886Z" fill="black"/>
                        </svg>';
                    print '</div>';


                    print '<div class="wpstream_inactive_icon wpstream_stream_pro wpstream-button-icon wpstream-trigger-modal wpstream_tooltip_wrapper"  data-modal="wpestate_broadcast_modal"   data-show-id="'.$the_id.'"">';
                      
                        print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn ON the channel to go live.','wpestream').'</div>'; 
                        print '<div class="wpstream_tooltip">'.esc_html__('Go Live with external streaming app','wpestream').'</div>'; 

                        print '<svg width="51" height="38" viewBox="0 0 51 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M48.1266 13.9634L41.891 17.5343V13.6438C41.891 10.8723 39.6525 8.63384 36.8811 8.63384H33.1504L30.1124 3.62393C28.7268 1.3324 26.2751 0 23.6103 0H10.2863C8.84722 0 7.67471 1.17251 7.67471 2.6116C7.67471 4.05068 8.84722 5.22319 10.2863 5.22319H23.6103C24.4631 5.22319 25.2093 5.64961 25.6888 6.3957L27.0744 8.63413L5.00991 8.63376C2.23843 8.63376 0 10.8722 0 13.6437V32.9901C0 35.7616 2.23843 38 5.00991 38H36.9342C39.7057 38 41.9441 35.7616 41.9441 32.9901V29.0461L48.1797 32.6169C49.2991 33.2564 50.6846 32.4571 50.6846 31.1778L50.6842 15.4022C50.6311 14.123 49.2455 13.3237 48.1261 13.9632L48.1266 13.9634ZM38.1603 32.9368C38.1603 33.6297 37.574 34.1625 36.9345 34.1625L5.01029 34.1629C4.31733 34.1629 3.78458 33.5766 3.78458 32.9372V13.5907C3.78458 12.8978 4.37086 12.365 5.01029 12.365H36.9345C37.6275 12.365 38.1603 12.9513 38.1603 13.5907V32.9368Z" fill="black"/>
                            <path d="M22.4917 21.585H9.70066C8.84784 21.585 8.15527 22.2779 8.15527 23.1304V28.247C8.15527 29.0998 8.84823 29.7923 9.70066 29.7923H22.4917C23.3445 29.7923 24.0371 29.0994 24.0371 28.247V23.1304C24.0371 22.2775 23.3445 21.585 22.4917 21.585Z" fill="black"/>
                        </svg>';
                    print '</div>';

                   
    
                    
                    print '<a href="'.esc_url($live_data_url).'" target="_blank" class="wpstream_inactive_icon wpstream_live_data wpstream_statistics_channel wpstream-button-icon wpstream_tooltip_wrapper"   data-show-id="'.$the_id.'" >';
                        
                        print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn ON the channel to see live stats.','wpestream').'</div>'; 
                        print '<div class="wpstream_tooltip">'.esc_html__('Live Statistics','wpestream').'</div>'; 

                        print'<svg width="50" height="42" viewBox="0 0 50 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.9001 9.02344H40.3342C39.7288 9.02344 39.2344 9.51791 39.2344 10.1233V40.4275C39.2344 41.033 39.7288 41.5274 40.3342 41.5274H48.9001C49.5055 41.5274 50 41.0329 50 40.4275V10.1233C50 9.51791 49.5055 9.02344 48.9001 9.02344V9.02344Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M35.821 22.4067H27.2551C26.6497 22.4067 26.1553 22.9012 26.1553 23.5066V40.4277C26.1553 41.0332 26.6497 41.5276 27.2551 41.5276H35.821C36.4264 41.5276 36.9209 41.0332 36.9209 40.4277V23.5066C36.9209 22.9012 36.4264 22.4067 35.821 22.4067V22.4067Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.7439 0H14.178C13.5726 0 13.0781 0.494478 13.0781 1.09989V40.4275C13.0781 41.0329 13.5726 41.5274 14.178 41.5274H22.7439C23.3493 41.5274 23.8438 41.0329 23.8438 40.4275V1.09989C23.8438 0.49447 23.3493 0 22.7439 0V0Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.66573 15.2559H1.09987C0.49445 15.2559 -2.24584e-05 15.7512 -2.24584e-05 16.3558V40.4285C-2.24584e-05 41.0331 0.495325 41.5284 1.09987 41.5284H9.66573C10.2703 41.5284 10.7656 41.034 10.7656 40.4285V16.3558C10.7656 15.7503 10.2711 15.2559 9.66573 15.2559V15.2559Z" fill="black"/>
                        </svg>';
                    print '</a>';
                print '</div>';

                print '<div class="wpstream_options_col2 wpstream_show_settings_wrapper">';
                    if($is_front==''){
                        print '<div class="wpstream_show_settings wpstream-button-icon wpstream-trigger-modal wpstream_tooltip_wrapper"   data-modal="wpestate_settings_modal" data-show-id="'.$the_id.'" value="'.esc_html__('Settings','wpstream').'">';
                            
                            print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn OFF the channel to change its settings.','wpestream').'</div>'; 
                            print '<div class="wpstream_tooltip">'.esc_html__('Channel Settings','wpestream').'</div>'; 

                            print '<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M49.22 21.8648C48.82 21.6099 45.665 19.7247 44.415 19.15L42.8651 15.4C43.3251 14.1601 44.2099 10.6849 44.3652 10.0798H44.3649C44.4911 9.52035 44.3213 8.93514 43.9149 8.52987L41.47 6.09995C41.0658 5.69189 40.4795 5.52168 39.9201 5.65003C39.4601 5.75501 35.92 6.64994 34.5999 7.15012L30.8499 5.60021C30.3048 4.40012 28.4699 1.32529 28.1351 0.795199V0.79485C27.8337 0.301688 27.298 0.00069643 26.7201 0H23.2798C22.7057 0.000348769 22.1718 0.294715 21.8648 0.779859C21.6098 1.1799 19.7247 4.33487 19.15 5.58487L15.4 7.13478C14.1601 6.67476 10.6849 5.78996 10.0798 5.63469V5.63504C9.52035 5.50878 8.93513 5.67864 8.52987 6.08496L6.09995 8.52988C5.69189 8.9341 5.52168 9.52042 5.65003 10.0798C5.75501 10.5398 6.64994 14.0799 7.15012 15.4L5.60021 19.15C4.40012 19.6951 1.32529 21.53 0.795198 21.8648H0.79485C0.301688 22.1662 0.00069643 22.7019 0 23.2798V26.7149C0.000348751 27.2893 0.294715 27.8233 0.779858 28.1299C1.1799 28.3849 4.33486 30.27 5.58487 30.8448L7.13478 34.5948C6.67475 35.8347 5.78996 39.3099 5.63469 39.915H5.63504C5.50878 40.4747 5.67863 41.06 6.08496 41.4649L8.51487 43.8948V43.8951C8.9191 44.3032 9.50541 44.4731 10.0648 44.3451C10.5248 44.2401 14.0649 43.3451 15.385 42.845L19.135 44.3949C19.6801 45.595 21.515 48.6698 21.8498 49.1999C22.1525 49.6997 22.6956 50.0035 23.2798 50H26.7149C27.2893 49.9996 27.8233 49.7053 28.1299 49.2201C28.3849 48.8201 30.27 45.6651 30.8447 44.4151L34.5947 42.8652C35.8347 43.3252 39.3098 44.21 39.9149 44.3653V44.3649C40.4747 44.4912 41.0599 44.3213 41.4649 43.915L43.8948 41.4851H43.8951C44.3032 41.0809 44.473 40.4945 44.345 39.9352C44.2401 39.4751 43.3451 35.9351 42.8449 34.615L44.3949 30.865C45.5949 30.3198 48.6698 28.485 49.1999 28.1501C49.6997 27.8474 50.0034 27.3044 49.9999 26.7201V23.2799C50.0045 22.7047 49.7087 22.1683 49.2201 21.8649L49.22 21.8648ZM24.9995 35.8845C22.1099 35.882 19.3399 34.7314 17.2985 32.6866C15.2572 30.6414 14.1118 27.8694 14.1146 24.9798C14.1171 22.0903 15.2676 19.3199 17.3125 17.2785C19.3577 15.2372 22.1297 14.0921 25.0193 14.0946C27.9088 14.0974 30.6792 15.2477 32.7205 17.2928C34.7619 19.338 35.907 22.1101 35.9045 24.9996C35.8978 27.8879 34.7462 30.6557 32.7021 32.6964C30.6576 34.7367 27.8876 35.8834 24.9994 35.8845H24.9995Z" fill="black"/>
                                </svg>
                            </div>';
                  

                        print '<a href="'.get_edit_post_link($the_id).'" class="wpstream_edit_channel wpstream-button-icon wpstream_tooltip_wrapper" target="_blank"  data-show-id="'.$the_id.'"">';
                            print '<div class="wpstream_tooltip">'.esc_html__('Edit Channel','wpestream').'</div>';    
                            print '<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M44.8203 1.79549C42.4263 -0.598498 38.5591 -0.598498 36.1651 1.79549L4.67494 33.2246C4.55203 33.3475 4.49079 33.4088 4.42954 33.5317C4.42954 33.5317 4.42954 33.5929 4.36829 33.5929C4.30705 33.7159 4.24539 33.7771 4.24539 33.9V33.9613L0.0711091 47.5886C-0.113044 48.264 0.0711092 48.9389 0.500659 49.4301C0.868966 49.7984 1.29852 49.9826 1.78973 49.9826C1.97388 49.9826 2.15804 49.9826 2.34219 49.9213L15.9085 45.747H15.9697C16.0926 45.6858 16.2151 45.6858 16.2768 45.6241C16.2768 45.6241 16.338 45.6241 16.338 45.5629C16.4609 45.5016 16.5834 45.44 16.6451 45.3175L48.0742 13.8884C50.4682 11.4944 50.4682 7.62715 48.0742 5.23316L44.8203 1.79549Z" fill="black"/>
                        </svg>';
                        print '</a>';
                    }

                    print '<a href="'.get_permalink($the_id).'" target="_blank" class="wpstream_view_channel wpstream-button-icon wpstream_tooltip_wrapper"   data-show-id="'.$the_id.'"">';
                        print '<div class="wpstream_tooltip">'.esc_html__('View Channel','wpestream').'</div>';  
                        print '<svg width="50" height="32" viewBox="0 0 50 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.6201 15.2828H21.8752C21.8752 14.069 22.8681 13.0207 24.1373 13.0207V9.76562C21.1032 9.76562 18.6201 12.2487 18.6201 15.2828Z" fill="black"/>
                            <path d="M24.855 0C12.9378 0 3.28272 10.9792 0.579292 14.3447C-0.193097 15.2826 -0.193097 16.662 0.579292 17.6553C3.28282 21.0208 12.9378 32 24.855 32C36.7722 32 46.4273 21.0208 49.1307 17.6553C49.9031 16.7174 49.9031 15.338 49.1307 14.3447C46.4275 10.9792 36.7722 0 24.855 0V0ZM24.855 25.8205C19.4482 25.8205 15.0344 21.4067 15.0344 15.9999C15.0344 10.5931 19.4482 6.17927 24.855 6.17927C30.2618 6.17927 34.6756 10.5931 34.6756 15.9999C34.6756 21.4067 30.2618 25.8205 24.855 25.8205Z" fill="black"/>
                        </svg>';
                    print '</a>';


                    print '<div class="wpstream_share_channel wpstream-button-icon wpstream-trigger-modal wpstream_tooltip_wrapper"   data-modal="wpestate_share_modal"   data-show-id="'.$the_id.'"">';
                        print '<div class="wpstream_tooltip">'.esc_html__('Share Channel','wpestream').'</div>';  
                        print '<svg width="44" height="50" viewBox="0 0 44 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.0777 20.5087L24.3481 17.9427C26.6432 16.5645 29.4554 16.2443 31.96 17.198C33.443 17.7642 35.1166 17.9363 36.852 17.6105C40.3163 16.9521 43.1162 14.1831 43.8115 10.7248C45.0606 4.51597 39.7751 -0.910703 33.5907 0.12835C30.2924 0.682061 27.5604 3.16211 26.5942 6.36168C26.4405 6.86646 26.3418 7.35835 26.2804 7.84466C25.9482 10.5028 24.2498 12.8044 21.9547 14.1827L19.3458 15.7456C17.1061 17.0869 14.3986 17.1792 11.9494 16.2748C10.9956 15.9241 9.96159 15.7271 8.88507 15.7271C3.39004 15.7271 -0.960663 20.7607 0.184097 26.4527C0.879447 29.911 3.67936 32.68 7.14364 33.3384C8.87902 33.6706 10.5525 33.492 12.0356 32.9259C14.5402 31.9721 17.3461 32.2919 19.6475 33.6706L23.906 36.2241C25.346 37.0855 26.2195 38.6424 26.2195 40.3159V40.3403C26.2195 45.8229 31.2287 50.1611 36.9018 49.0472C40.3602 48.3703 43.1415 45.583 43.8244 42.1246C44.9383 36.451 40.6 31.4423 35.1175 31.4423C34.0406 31.4423 33.007 31.6394 32.0532 31.9901C29.604 32.8944 26.9029 32.8022 24.6627 31.4608L20.0721 28.7038C18.6445 27.8484 17.7706 26.304 17.7706 24.6364V24.5875C17.7642 22.9208 18.6441 21.37 20.0777 20.5085L20.0777 20.5087Z" fill="black"/>
                        </svg>';
                    print '</div>';
                print '</div>';

         

             


                $this->wpstream_display_modal_seetings($the_id);
                $this->wpstream_display_modal_share($the_id);
                $this->wpstream_display_modal_broadcast($the_id,$external_software_streaming_class,$obs_uri,$obs_stream);

            print '</div>';

        }


        

        /**
        * Shows event unit card in admin - theme version
        *
        * @since    3.0.1 
        */
        public function wpstream_live_stream_unit_for_theme($the_id,$is_front=''){
            global $live_event_for_user;
            global $wpstream_plugin;
            $current_user       =   wp_get_current_user();
          
            if( !current_user_can('administrator')){
                if($is_front=='' ){
                    print '<div class="event_list_unit">';
                    esc_html_e('You are not allowed to broadcast.','wpstream');
                    print '</div>';
                    return;
                }
        
            }
            
            if( !$this->main->wpstream_check_user_can_stream()  ){
                print '<div class="event_list_unit">';
                esc_html_e('You are not allowed to broadcast','wpstream');
                print '</div>';
                return;
            }
       

            $live_class='';
            if(isset($live_event_for_user[$the_id])) {
                $live_class=" wpstream_show_started";
            }


            if(has_post_thumbnail($the_id)){
                $thumb  =   get_the_post_thumbnail_url($the_id,'thumbnail');
            }else{
                $thumb= plugin_dir_url( dirname( __FILE__ ) ). 'img/plugin-logo.png';
            }

            $pending_streaming_class            =   'hide_stream_data';
            $external_software_streaming_class  =   '';
            $obs_uri                            =   '';
            $obs_stream                         =   '';
            $live_data_url                      =   '';

            if( $live_event_for_user=='' && $is_front=='front' ){
                $live_event_for_user    =    $this->main->wpstream_live_connection->wpstream_get_live_event_for_user();
            }
            $channel_status                 =   esc_html__('Channel is OFF','wpstream');
            if(is_array($live_event_for_user) && isset($live_event_for_user[$the_id])) {
                $pending_streaming_class        =   'pending_trigger';
                $live_data_url                  =   get_post_meta($the_id,'qos_url',true);
              //  $channel_status                 =   esc_html__('Channel is on','wpstream');
            }

            $server_id      =   get_post_meta($the_id,'server_id',true);
            $obs_uri        =   get_post_meta($the_id,'obs_uri',true);
            $obs_stream     =   get_post_meta($the_id,'obs_stream',true);
            $webcaster_url  =   get_post_meta($the_id,'webcaster_url',true);
            $rtmp_ip_uri    =   '';

            print '<div class="wpstream_theme_event_list_unit event_list_unit '.$live_class.' '.$pending_streaming_class.' event_unit_style_'.esc_attr($is_front).'"  data-show-id="'.intval($the_id).'" data-server-id="'.$server_id.'" data-server-url="'.$rtmp_ip_uri.'"">';

                print '<div class="wpstream_channel_status">'.$channel_status.'</div>';

                print '<div class="server_notification"></div>';

                print '<div class="wpstream_theme_event_thumb_wrapper">';
                    print '<div class="event_thumb_wrapper" style="background-image:url('.$thumb.')"></div>';
                    print '<div class="event_title" data-prodid="'.$the_id.'">'.wp_trim_words(get_the_title($the_id),10); 
                        print '<div class="wpstream_channel_item_id">'.esc_html('#ID','wpstream').' '.$the_id.'</div>';
                    print '</div>';
                print '</div>';

                print '<div class="wpstream_theme_control_bar_wrapper">';

                          
                print '<div class="wpstream_options_col1 wpstream_stream_browser_wrapper">';
                   
                    print '<div class="wpstream_inactive_icon start_webcaster wpstream_stream_browser wpstream-button-icon wpstream_tooltip_wrapper"  data-webcaster-url="'.$webcaster_url.'" data-show-id="'.$the_id.'"">';
                       
                        print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn ON the channel to go live.','wpestream').'</div>'; 
                        print '<div class="wpstream_tooltip">'.esc_html__('Go live with your webcam','wpestream').'</div>'; 

                        print '<svg width="41" height="51" viewBox="0 0 41 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M40.7646 44.2989C39.8782 41.4216 38.3361 38.7814 36.181 36.4522C35.7274 35.9619 35.1629 35.3518 34.4858 34.8356C34.0448 34.4996 33.4262 34.5281 33.0182 34.9035C29.358 38.2714 25.2628 39.9088 20.4979 39.9088H20.4853C15.7103 39.9059 11.6117 38.264 7.95648 34.889C7.52404 34.4896 6.85874 34.484 6.41953 34.8764C3.30565 37.6569 1.20501 40.9206 0.175434 44.5778C-0.276291 46.1833 0.15652 47.8497 1.33328 49.0353C2.22709 49.9362 3.39869 50.4173 4.61958 50.4173C5.0357 50.4173 5.45813 50.3613 5.87645 50.247C10.147 49.081 14.0772 48.4108 17.8916 48.1983C21.1954 48.014 24.4465 48.1323 27.5541 48.5495C29.9559 48.8718 32.4237 49.3888 35.0996 50.1299C36.823 50.6076 38.459 50.1562 39.7059 48.86C40.9268 47.5901 41.2933 46.0132 40.7651 44.2994L40.7646 44.2989ZM38.0572 47.274C37.3825 47.9754 36.6367 48.1823 35.7092 47.9257C32.9321 47.1565 30.3638 46.6187 27.8578 46.2827C24.6079 45.8465 21.2117 45.723 17.7643 45.9151C13.7874 46.1365 9.70183 46.8319 5.27439 48.041C4.43435 48.2706 3.5673 48.0403 2.9568 47.425C2.35932 46.8227 2.14793 46.0108 2.37712 45.1975C3.21567 42.2171 4.7982 39.6162 7.2018 37.2734C11.0641 40.5378 15.5276 42.193 20.4845 42.196H20.4978C25.4557 42.196 29.9251 40.5386 33.7967 37.2667C34.024 37.4904 34.254 37.7359 34.5032 38.0059C36.4236 40.0816 37.7951 42.4256 38.5799 44.9728C38.8603 45.8811 38.6941 46.6125 38.0581 47.274L38.0572 47.274ZM20.4954 30.7538C13.8088 30.7523 8.54664 25.4128 8.54379 18.6256C8.5412 12.2624 13.9961 6.83353 20.3776 6.84872C27.1094 6.86467 32.4526 12.1652 32.4488 18.8231C32.4447 25.4243 27.1038 30.7547 20.4954 30.7538V30.7538ZM29.4401 18.8383C29.5559 13.9512 25.4358 9.8445 20.5347 9.83671C15.5301 9.82892 11.6488 13.9279 11.5328 18.5667C11.4086 23.5202 15.344 27.7103 20.3381 27.7926C25.5542 27.8783 29.554 23.5758 29.4401 18.8383V18.8383ZM20.4917 25.1342C17.2131 25.2436 14.1541 22.4349 14.1567 18.8924C14.1593 15.2627 16.9642 12.4191 20.5858 12.4662C24.0109 12.5111 26.8269 15.2466 26.8362 18.8471C26.8451 22.4293 23.782 25.2436 20.4917 25.1342V25.1342ZM18.5119 13.9982C17.3006 13.9908 15.6202 15.656 15.6295 16.8543C15.6328 17.3209 15.961 17.661 16.3983 17.6513C17.5973 17.6246 19.2281 16.0169 19.2696 14.8205C19.2881 14.2742 19.0371 14.0016 18.5119 13.9982L18.5119 13.9982ZM20.4964 0.568359C10.4428 0.568359 2.26333 8.74761 2.26333 18.8014C2.26333 28.8551 10.4426 37.0345 20.4964 37.0345C30.5502 37.0345 38.7295 28.8553 38.7295 18.8014C38.7291 8.7478 30.5502 0.568359 20.4964 0.568359V0.568359ZM20.4964 33.3886C12.4528 33.3886 5.90919 26.8449 5.90919 18.8014C5.90919 11.3105 11.585 5.12093 18.8624 4.30631C18.8561 4.36009 18.8528 4.41423 18.8528 4.46912C18.8468 5.37368 19.6112 6.16064 20.4927 6.15846C21.3636 6.15586 22.1283 5.39706 22.142 4.52143C22.1431 4.44874 22.1372 4.3768 22.1279 4.30596C29.4067 5.11929 35.0849 11.3092 35.0849 18.802C35.0842 26.8456 28.5404 33.3892 20.4968 33.3892L20.4964 33.3886Z" fill="black"/>
                        </svg>';
                        esc_html_e('Webcam','wpstream');
                    print '</div>';


                    print '<div class="wpstream_inactive_icon wpstream_stream_pro wpstream-button-icon wpstream-trigger-modal wpstream_tooltip_wrapper"  data-modal="wpestate_broadcast_modal"   data-show-id="'.$the_id.'"">';
                      
                        print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn ON the channel to go live.','wpestream').'</div>'; 
                        print '<div class="wpstream_tooltip">'.esc_html__('Go Live with external streaming app','wpestream').'</div>'; 

                        print '<svg width="51" height="38" viewBox="0 0 51 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M48.1266 13.9634L41.891 17.5343V13.6438C41.891 10.8723 39.6525 8.63384 36.8811 8.63384H33.1504L30.1124 3.62393C28.7268 1.3324 26.2751 0 23.6103 0H10.2863C8.84722 0 7.67471 1.17251 7.67471 2.6116C7.67471 4.05068 8.84722 5.22319 10.2863 5.22319H23.6103C24.4631 5.22319 25.2093 5.64961 25.6888 6.3957L27.0744 8.63413L5.00991 8.63376C2.23843 8.63376 0 10.8722 0 13.6437V32.9901C0 35.7616 2.23843 38 5.00991 38H36.9342C39.7057 38 41.9441 35.7616 41.9441 32.9901V29.0461L48.1797 32.6169C49.2991 33.2564 50.6846 32.4571 50.6846 31.1778L50.6842 15.4022C50.6311 14.123 49.2455 13.3237 48.1261 13.9632L48.1266 13.9634ZM38.1603 32.9368C38.1603 33.6297 37.574 34.1625 36.9345 34.1625L5.01029 34.1629C4.31733 34.1629 3.78458 33.5766 3.78458 32.9372V13.5907C3.78458 12.8978 4.37086 12.365 5.01029 12.365H36.9345C37.6275 12.365 38.1603 12.9513 38.1603 13.5907V32.9368Z" fill="black"/>
                            <path d="M22.4917 21.585H9.70066C8.84784 21.585 8.15527 22.2779 8.15527 23.1304V28.247C8.15527 29.0998 8.84823 29.7923 9.70066 29.7923H22.4917C23.3445 29.7923 24.0371 29.0994 24.0371 28.247V23.1304C24.0371 22.2775 23.3445 21.585 22.4917 21.585Z" fill="black"/>
                        </svg>';
                        esc_html_e('External App','wpstream');
                    print '</div>';

                   
    
                    
                    print '<a href="'.esc_url($live_data_url).'" target="_blank" class="wpstream_inactive_icon wpstream_live_data wpstream_statistics_channel wpstream-button-icon wpstream_tooltip_wrapper"   data-show-id="'.$the_id.'" >';
                        
                        print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn ON the channel to see live stats.','wpestream').'</div>'; 
                        print '<div class="wpstream_tooltip">'.esc_html__('Live Statistics','wpestream').'</div>'; 

                        print'<svg width="50" height="42" viewBox="0 0 50 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.9001 9.02344H40.3342C39.7288 9.02344 39.2344 9.51791 39.2344 10.1233V40.4275C39.2344 41.033 39.7288 41.5274 40.3342 41.5274H48.9001C49.5055 41.5274 50 41.0329 50 40.4275V10.1233C50 9.51791 49.5055 9.02344 48.9001 9.02344V9.02344Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M35.821 22.4067H27.2551C26.6497 22.4067 26.1553 22.9012 26.1553 23.5066V40.4277C26.1553 41.0332 26.6497 41.5276 27.2551 41.5276H35.821C36.4264 41.5276 36.9209 41.0332 36.9209 40.4277V23.5066C36.9209 22.9012 36.4264 22.4067 35.821 22.4067V22.4067Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.7439 0H14.178C13.5726 0 13.0781 0.494478 13.0781 1.09989V40.4275C13.0781 41.0329 13.5726 41.5274 14.178 41.5274H22.7439C23.3493 41.5274 23.8438 41.0329 23.8438 40.4275V1.09989C23.8438 0.49447 23.3493 0 22.7439 0V0Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.66573 15.2559H1.09987C0.49445 15.2559 -2.24584e-05 15.7512 -2.24584e-05 16.3558V40.4285C-2.24584e-05 41.0331 0.495325 41.5284 1.09987 41.5284H9.66573C10.2703 41.5284 10.7656 41.034 10.7656 40.4285V16.3558C10.7656 15.7503 10.2711 15.2559 9.66573 15.2559V15.2559Z" fill="black"/>
                        </svg>';
                        esc_html_e('Statistics','wpstream');
                    print '</a>';
                print '</div>';

                print '<div class="wpstream_options_col2 wpstream_show_settings_wrapper">';
                    if($is_front==''){
                        print '<div class="wpstream_show_settings wpstream-button-icon wpstream-trigger-modal wpstream_tooltip_wrapper"   data-modal="wpestate_settings_modal" data-show-id="'.$the_id.'" value="'.esc_html__('Settings','wpstream').'">';
                            
                            print '<div class="wpstream_tooltip_disabled">'.esc_html__('Turn OFF the channel to change its settings.','wpestream').'</div>'; 
                            print '<div class="wpstream_tooltip">'.esc_html__('Channel Settings','wpestream').'</div>'; 

                            print '<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M49.22 21.8648C48.82 21.6099 45.665 19.7247 44.415 19.15L42.8651 15.4C43.3251 14.1601 44.2099 10.6849 44.3652 10.0798H44.3649C44.4911 9.52035 44.3213 8.93514 43.9149 8.52987L41.47 6.09995C41.0658 5.69189 40.4795 5.52168 39.9201 5.65003C39.4601 5.75501 35.92 6.64994 34.5999 7.15012L30.8499 5.60021C30.3048 4.40012 28.4699 1.32529 28.1351 0.795199V0.79485C27.8337 0.301688 27.298 0.00069643 26.7201 0H23.2798C22.7057 0.000348769 22.1718 0.294715 21.8648 0.779859C21.6098 1.1799 19.7247 4.33487 19.15 5.58487L15.4 7.13478C14.1601 6.67476 10.6849 5.78996 10.0798 5.63469V5.63504C9.52035 5.50878 8.93513 5.67864 8.52987 6.08496L6.09995 8.52988C5.69189 8.9341 5.52168 9.52042 5.65003 10.0798C5.75501 10.5398 6.64994 14.0799 7.15012 15.4L5.60021 19.15C4.40012 19.6951 1.32529 21.53 0.795198 21.8648H0.79485C0.301688 22.1662 0.00069643 22.7019 0 23.2798V26.7149C0.000348751 27.2893 0.294715 27.8233 0.779858 28.1299C1.1799 28.3849 4.33486 30.27 5.58487 30.8448L7.13478 34.5948C6.67475 35.8347 5.78996 39.3099 5.63469 39.915H5.63504C5.50878 40.4747 5.67863 41.06 6.08496 41.4649L8.51487 43.8948V43.8951C8.9191 44.3032 9.50541 44.4731 10.0648 44.3451C10.5248 44.2401 14.0649 43.3451 15.385 42.845L19.135 44.3949C19.6801 45.595 21.515 48.6698 21.8498 49.1999C22.1525 49.6997 22.6956 50.0035 23.2798 50H26.7149C27.2893 49.9996 27.8233 49.7053 28.1299 49.2201C28.3849 48.8201 30.27 45.6651 30.8447 44.4151L34.5947 42.8652C35.8347 43.3252 39.3098 44.21 39.9149 44.3653V44.3649C40.4747 44.4912 41.0599 44.3213 41.4649 43.915L43.8948 41.4851H43.8951C44.3032 41.0809 44.473 40.4945 44.345 39.9352C44.2401 39.4751 43.3451 35.9351 42.8449 34.615L44.3949 30.865C45.5949 30.3198 48.6698 28.485 49.1999 28.1501C49.6997 27.8474 50.0034 27.3044 49.9999 26.7201V23.2799C50.0045 22.7047 49.7087 22.1683 49.2201 21.8649L49.22 21.8648ZM24.9995 35.8845C22.1099 35.882 19.3399 34.7314 17.2985 32.6866C15.2572 30.6414 14.1118 27.8694 14.1146 24.9798C14.1171 22.0903 15.2676 19.3199 17.3125 17.2785C19.3577 15.2372 22.1297 14.0921 25.0193 14.0946C27.9088 14.0974 30.6792 15.2477 32.7205 17.2928C34.7619 19.338 35.907 22.1101 35.9045 24.9996C35.8978 27.8879 34.7462 30.6557 32.7021 32.6964C30.6576 34.7367 27.8876 35.8834 24.9994 35.8845H24.9995Z" fill="black"/>
                                </svg>
                            </div>';
                  

                        print '<a href="'.get_edit_post_link($the_id).'" class="wpstream_edit_channel wpstream-button-icon wpstream_tooltip_wrapper" target="_blank"  data-show-id="'.$the_id.'"">';
                            print '<div class="wpstream_tooltip">'.esc_html__('Edit Channel','wpestream').'</div>';    
                            print '<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M44.8203 1.79549C42.4263 -0.598498 38.5591 -0.598498 36.1651 1.79549L4.67494 33.2246C4.55203 33.3475 4.49079 33.4088 4.42954 33.5317C4.42954 33.5317 4.42954 33.5929 4.36829 33.5929C4.30705 33.7159 4.24539 33.7771 4.24539 33.9V33.9613L0.0711091 47.5886C-0.113044 48.264 0.0711092 48.9389 0.500659 49.4301C0.868966 49.7984 1.29852 49.9826 1.78973 49.9826C1.97388 49.9826 2.15804 49.9826 2.34219 49.9213L15.9085 45.747H15.9697C16.0926 45.6858 16.2151 45.6858 16.2768 45.6241C16.2768 45.6241 16.338 45.6241 16.338 45.5629C16.4609 45.5016 16.5834 45.44 16.6451 45.3175L48.0742 13.8884C50.4682 11.4944 50.4682 7.62715 48.0742 5.23316L44.8203 1.79549Z" fill="black"/>
                        </svg>';
                        print '</a>';
                    }

                    print '<a href="'.get_permalink($the_id).'" target="_blank" class="wpstream_view_channel wpstream-button-icon wpstream_tooltip_wrapper"   data-show-id="'.$the_id.'"">';
                        print '<div class="wpstream_tooltip">'.esc_html__('View Channel','wpestream').'</div>';  
                        print '<svg width="50" height="32" viewBox="0 0 50 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.6201 15.2828H21.8752C21.8752 14.069 22.8681 13.0207 24.1373 13.0207V9.76562C21.1032 9.76562 18.6201 12.2487 18.6201 15.2828Z" fill="black"/>
                            <path d="M24.855 0C12.9378 0 3.28272 10.9792 0.579292 14.3447C-0.193097 15.2826 -0.193097 16.662 0.579292 17.6553C3.28282 21.0208 12.9378 32 24.855 32C36.7722 32 46.4273 21.0208 49.1307 17.6553C49.9031 16.7174 49.9031 15.338 49.1307 14.3447C46.4275 10.9792 36.7722 0 24.855 0V0ZM24.855 25.8205C19.4482 25.8205 15.0344 21.4067 15.0344 15.9999C15.0344 10.5931 19.4482 6.17927 24.855 6.17927C30.2618 6.17927 34.6756 10.5931 34.6756 15.9999C34.6756 21.4067 30.2618 25.8205 24.855 25.8205Z" fill="black"/>
                        </svg>';
                        esc_html_e('View','wpstream');
                    print '</a>';


                    print '<div class="wpstream_share_channel wpstream-button-icon wpstream-trigger-modal wpstream_tooltip_wrapper"   data-modal="wpestate_share_modal"   data-show-id="'.$the_id.'"">';
                        print '<div class="wpstream_tooltip">'.esc_html__('Share Channel','wpestream').'</div>';  
                        print '<svg width="44" height="50" viewBox="0 0 44 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.0777 20.5087L24.3481 17.9427C26.6432 16.5645 29.4554 16.2443 31.96 17.198C33.443 17.7642 35.1166 17.9363 36.852 17.6105C40.3163 16.9521 43.1162 14.1831 43.8115 10.7248C45.0606 4.51597 39.7751 -0.910703 33.5907 0.12835C30.2924 0.682061 27.5604 3.16211 26.5942 6.36168C26.4405 6.86646 26.3418 7.35835 26.2804 7.84466C25.9482 10.5028 24.2498 12.8044 21.9547 14.1827L19.3458 15.7456C17.1061 17.0869 14.3986 17.1792 11.9494 16.2748C10.9956 15.9241 9.96159 15.7271 8.88507 15.7271C3.39004 15.7271 -0.960663 20.7607 0.184097 26.4527C0.879447 29.911 3.67936 32.68 7.14364 33.3384C8.87902 33.6706 10.5525 33.492 12.0356 32.9259C14.5402 31.9721 17.3461 32.2919 19.6475 33.6706L23.906 36.2241C25.346 37.0855 26.2195 38.6424 26.2195 40.3159V40.3403C26.2195 45.8229 31.2287 50.1611 36.9018 49.0472C40.3602 48.3703 43.1415 45.583 43.8244 42.1246C44.9383 36.451 40.6 31.4423 35.1175 31.4423C34.0406 31.4423 33.007 31.6394 32.0532 31.9901C29.604 32.8944 26.9029 32.8022 24.6627 31.4608L20.0721 28.7038C18.6445 27.8484 17.7706 26.304 17.7706 24.6364V24.5875C17.7642 22.9208 18.6441 21.37 20.0777 20.5085L20.0777 20.5087Z" fill="black"/>
                        </svg>';
                        esc_html_e('Share','wpstream');
                    print '</div>';
                print '</div>';

                  
                    print '<div class="start_event wpstream_button wpstream_tooltip_wrapper"  data-show-id="'.$the_id.'" > '.esc_html__('TURN ON','wpstream');
                        print '<div class="wpstream_tooltip">'.esc_html__('Channel is now OFF. Click to turn ON.','wpestream').'</div>'; 
                    print '</div>';
                
                print '</div>';

             


                $this->wpstream_display_modal_seetings($the_id);
                $this->wpstream_display_modal_share($the_id);
                $this->wpstream_display_modal_broadcast($the_id,$external_software_streaming_class,$obs_uri,$obs_stream);

            print '</div>';

        }


        /*
        *
        * Close modal button
        *
        */

        public function wpstream_close_modal_button(){
            print '<div class="wpstream_close_modal"></div>';
        }

        /*
        *
        * Display modal settings
        *
        */
        
        public function wpstream_display_modal_seetings($the_id){
            print '<div class="wpstream_modal_form wpestate_settings_modal">';   
                $this->wpstream_close_modal_button();    
                print '<h3>'; 
                printf( esc_html__('Channel Settings (#ID %s)','wpstream'),$the_id);
                print '</h3>';                     
                $local_event_options =   get_post_meta($the_id,'local_event_options',true);
                if(!is_array($local_event_options)){
                    $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;
                }

                $local_array_exclude=array('ses_encrypt','vod_domain_lock','vod_encrypt');
                
                print '<div class="wpstream_event_streaming_local">';
                    $this->user_streaming_global_channel_options('',$local_event_options,  $local_array_exclude);
                print '</div>';            
            print '</div>';
        }

        
        /*
        *
        * Display share settings
        *
        */
        public function wpstream_display_modal_share($the_id){
            print '<div class="wpstream_modal_form wpestate_share_modal">';   
                $this->wpstream_close_modal_button();    
                print '<h3>'.esc_html__('Share your Channel','wpstream').'</h3>';    
                $this->wpstream_social_share($the_id);                
            print '</div>';
        }


        /* 
        *
        * Display broadcast modal 
        *
        */

        public function wpstream_display_modal_broadcast($the_id,$external_software_streaming_class,$obs_uri,$obs_stream){
            print '<div class="wpstream_modal_form wpestate_broadcast_modal">';    
                $this->wpstream_close_modal_button();    
                print '<h3>'.esc_html__('Go Live with External Streaming App','wpstream').'</h3>';    

                print '<div class="wpstream_modal_explanations">'.esc_html__('Please choose your RTMP encoder/broadcaster','wpstream').'</div>'; 

                print '<select class="wpstream_external_broadcast_options">';
                    print '<option value="wpstream_obs_settings">'.esc_html('OBS','wpstream').'</option>';  
                    print '<option value="wpstream_streamyard_settings">'.esc_html('StreamYard','wpstream').'</option>';    
                    print '<option value="wpstream_restream_settings">'.esc_html('Restream','wpstream').'</option>';   
                    print '<option value="wpstream_wimx_settings">'.esc_html('vMix','wpstream').'</option>';  
                    print '<option value="wpstream_wirecast_settings">'.esc_html('Wirecast','wpstream').'</option>';    
                    print '<option value="wpstream_xplit_settings">'.esc_html('XSplit','wpstream').'</option>';              
                    print '<option value="wpstream_larix_settings">'.esc_html('Larix Broadcaster','wpstream').'</option>';                    
        
                print '</select>';

                $this->wpstream_obs_settings($obs_uri,$obs_stream);
                $this->wpstream_streamyard_settings($obs_uri,$obs_stream);
                $this->wpstream_restream_settings($obs_uri,$obs_stream);
                $this->wpstream_wmix_settings($obs_uri,$obs_stream);
                $this->wpstream_wirecast_settings($obs_uri,$obs_stream);
                $this->wpstream_xplit_settings($obs_uri,$obs_stream);
                $this->wpstream_larix_settings($obs_uri,$obs_stream);

            print '</div>';
        }

        


        /*
        *
        * Display OBS settings 
        *
        */

        public function wpstream_obs_settings($obs_uri,$obs_stream){

            print '<div class="external_software_streaming wpstream_obs_settings" style="display:block;">';

                print '<div class="external_software_streaming_details">';
                    print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('Server:').' </strong>';
                    print '<div class="wpstream_live_uri_text">' . $obs_uri.'</div>
                    <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                    print '<div class="event_list_stream_key_wrap"><strong>'.__('Stream Key:').' </strong>
                    <div class="wpstream_live_key_text">'. $obs_stream.'</div><div class="copy_live_key">'.__('copy','wpstream').'</div></div>';
                    print '</div>';
                
                print'</div>';
                
                print ' <div class="wpstream_modal_explanations">';
                  print '<ul>
                  <li>1. Click Settings in the OBS Window and then Select Stream.</li>
                  <li>2. Choose Custom Streaming Server in the Stream Type dropdown menu.</li>
                  <li>3. In the URL box, type/paste your Server.</li>
                  <li>4. In the Stream key, type/paste your Stream key.</li>
                  <li>5. Save changes.Close the Settings window and click on the “Start Streaming” button in the main window of OBS.</li></ul>';
                  
                print '</div>';           
            
                
            print'</div>';  
        }

        /*
        *
        * Display StreamYard settings 
        *
        */

        public function wpstream_streamyard_settings($obs_uri,$obs_stream){

            print '<div class="external_software_streaming wpstream_streamyard_settings">';

                print '<div class="external_software_streaming_details">';
                    print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('RTMP server URL:').' </strong>';
                    print '<div class="wpstream_live_uri_text">' . $obs_uri.'</div>
                    <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                    print '<div class="event_list_stream_key_wrap"><strong>'.__('Stream key:').' </strong>
                    <div class="wpstream_live_key_text">'. $obs_stream.'</div><div class="copy_live_key">'.__('copy','wpstream').'</div></div>';
                    print '</div>';
                
                print'</div>';
                
                print ' <div class="wpstream_modal_explanations">';
                  print '<ul>
                  <li>1. Set up your destination by going to your StreamYard account.</li>
                  <li>2. Choose "Custom RTMP" and add the RTMP server URL and Stream key from WpStream.</li>
                  <li>3. Go to "Broadcasts" then "create a broadcast".</li>
                  <li>4. Enter Live Studio.</li>
                  <li>5. Adjust all your preferred settings and Click on "Go Live".</li></ul>';

                print '</div>';           
            
                
            print'</div>';  
        }



        /*
        *
        * Display StreamYard settings 
        *
        */

        public function wpstream_restream_settings($obs_uri,$obs_stream){

            print '<div class="external_software_streaming wpstream_restream_settings">';

                print '<div class="external_software_streaming_details">';
                    print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('RTMP URL:').' </strong>';
                    print '<div class="wpstream_live_uri_text">' . $obs_uri.'</div>
                    <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                    print '<div class="event_list_stream_key_wrap"><strong>'.__('Stream key:').' </strong>
                    <div class="wpstream_live_key_text">'. $obs_stream.'</div><div class="copy_live_key">'.__('copy','wpstream').'</div></div>';
                    print '</div>';
                
                print'</div>';
                
                print ' <div class="wpstream_modal_explanations">';
                  print '<ul>
                  <li>1. Go to your Restream account and set up a destination or channel.</li>
                  <li>2. Choose "Custom RTMP" and add the RTMP URL and Stream Key from WpStream.</li>
                  <li>3. Click on "Add Channel".</li>
                  <li>4. Enter Live Studio.</li>
                  <li>5. Adjust your preferred settings and Go Live.</li></ul>';
                
                print '</div>';           
            
                
            print'</div>';  
        }



        /*
        *
        * Display Xplit settings 
        *
        */


        public function wpstream_xplit_settings($obs_uri,$obs_stream){
            print '<div class="external_software_streaming wpstream_xplit_settings" style="display:none;">';
                print '<div class="external_software_streaming_details">';
                    print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('RTMP Url:').' </strong>';
                    print '<div class="wpstream_live_uri_text">' . $obs_uri.'</div>
                    <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                    print '<div class="event_list_stream_key_wrap"><strong>'.__('Stream Key:').' </strong>
                    <div class="wpstream_live_key_text">'. $obs_stream.'</div><div class="copy_live_key">'.__('copy','wpstream').'</div></div>';
                    print '</div>';
                
                print'</div>';
                
                print ' <div class="wpstream_modal_explanations">';
                    print '<ul>
                    <li>1. Click Broadcast in the XSplit Window and then click "Set up a new output".</li>
                    <li>2. Choose Custom RTMP in the Set up a new output dropdown menu.</li>
                    <li>3. In the URL box, type/paste your RTMP Url.</li>
                    <li>4. In the Stream key, type/paste your Stream key.</li>
                    <li>5. Save changes and click on the “Stream” button in the main window.</li></ul>';
                print '</div>';  

            print '</div>';
        }



        /*
        *
        * Display WireCast settings 
        *
        */


        public function wpstream_wirecast_settings($obs_uri,$obs_stream){
            print '<div class="external_software_streaming wpstream_wirecast_settings" style="display:none;">';
           
            print '<div class="external_software_streaming_details">';
                print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('Address:').' </strong>';
                print '<div class="wpstream_live_uri_text">' . $obs_uri.'</div>
                <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                print '<div class="event_list_stream_key_wrap"><strong>'.__('Stream:').' </strong>
                <div class="wpstream_live_key_text">'. $obs_stream.'</div><div class="copy_live_key">'.__('copy','wpstream').'</div></div>';
                print '</div>';
            
            print'</div>';
            
            print ' <div class="wpstream_modal_explanations">';
                print '<ul>
                <li>1. Click Output on the top of the screen and then Select Output Settings.</li>
                <li>2. In the destination, choose RTMP Server and click OK.</li>
                <li>3. In the Address box, type/paste your Address.</li>
                <li>4. In the Stream box, type/paste your Stream key.</li>
                <li>5. Click on OK to save changes just click the Output on the top of the screen and then Start/Stop Broadcasting.</li></ul>';
            print '</div>';  

            print '</div>';
        }



        /*
        *
        * Display Larix settings 
        *
        */


        public function wpstream_larix_settings($obs_uri,$obs_stream){
            print '<div class="external_software_streaming wpstream_larix_settings" style="display:none;">';
                $larix_rtmp=$obs_uri.$obs_stream;
                print '<div class="external_software_streaming_details">';
                    print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('RTMP:').'</strong>';
                    print '<div class="wpstream_live_uri_text wpstream_larix_rtmp"></div>
                    <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                    print '</div>';
                
                print'</div>';
                
                print ' <div class="wpstream_modal_explanations">';
                    print '<ul>
                    <li>A. Scan the QR code.</li>
                    <li>or</li>
                    <li>B. Manually configure Larix with the above RTMP</li>';
                if(wp_is_mobile() ){
                    print '
                    <li>or</li>
                    <li>C. Click on the button below </li></ul>';
                }
                 

                print '</div>';  

              
                print '<img class="print_qrcode" src="" />';
                if(wp_is_mobile() ){
                    print '<a href="" class="wpstream_start_with_larix_mobile" >Start Streaming with Larix</a>';
                }
               

                
            print '</div>';
        }




        /*
        *
        * Display Wmix settings 
        *
        */


        public function wpstream_wmix_settings($obs_uri,$obs_stream){
            print '<div class="external_software_streaming wpstream_wimx_settings" style="display:none;">';
            
            print '<div class="external_software_streaming_details">';
                print '<div class="event_list_unit_notificationx"><strong>'.esc_html__('URL:').' </strong>';
                print '<div class="wpstream_live_uri_text">' . $obs_uri.'</div>
                <div class="copy_live_uri">'.__('copy','wpstream').'</div>';

                print '<div class="event_list_stream_key_wrap"><strong>'.__('Stream Key:').' </strong>
                <div class="wpstream_live_key_text">'. $obs_stream.'</div><div class="copy_live_key">'.__('copy','wpstream').'</div></div>';
                print '</div>';
            
            print'</div>';
            
            print ' <div class="wpstream_modal_explanations">';
                print '<ul>
                <li>1. Click on the gear icon near the stream button on the bottom.</li>
                <li>2. Choose a custom RTMP Server in destination.</li>
                <li>3. In the URL box, type/paste your URL.</li>
                <li>4. In the Stream key, type/paste your Stream key.</li>
                <li>5. Save changes. You can start streaming by clicking on the stream button at the bottom of the dashboard.</li></ul>';
            print '</div>';  
           
            print '</div>';
        }


       
        /*
        * Set Settings
        *
        * 
        */  

        public function wpstream_settings(){

            if( !current_user_can('administrator') ){
                die('Only for administrators');
            }
            
    
            if($_SERVER['REQUEST_METHOD'] === 'POST'){  
                if(  !wp_verify_nonce($_POST['wpstream-settings-nonce'],'wpstream-settings-nonce') ){
                    die('Security check');
                }

                $allowed_html   =   array();
                $exclude_array  =   array();
                $allowed_html   =   array();



                if( isset($_POST['user_streaming_channel_type_hidden']) && intval($_POST['user_streaming_channel_type_hidden'])==1  && !isset($_POST['stream_role'])    ){
                     update_option( sanitize_key('wpstream_stream_role'), '' );
                }
                
                foreach($_POST as $variable=>$value){   
                    if ($variable!='submit'){
                        if (!in_array($variable, $exclude_array) ){
                            update_option( sanitize_key('wpstream_'.$variable), sanitize_text_field ($value) );
                        }
                        
                        if($variable=='stream_role'){
                           update_option( sanitize_key('wpstream_stream_role'), $value );
                        }
                        
                    }   
                }
                
                
                if( isset($_GET['tab']) && $_GET['tab']=='default_options' ){
                    $event_settings=array();
                    foreach($this->global_event_options as $key=>$option){
                        $event_settings[$key]='';            
                        if(isset($_POST['wpstream_event_set_'.$key]) && $_POST['wpstream_event_set_'.$key]=='on'){
                            $event_settings[$key]=1;
                        }else{
                            $event_settings[$key]=0;
                        }
                    }       
                    update_option('wpstream_user_streaming_global_channel_options',$event_settings);
                }
                


                
                
                
                // reset permalinkgs
                global $wp_rewrite; 
            
                update_option( "rewrite_rules", FALSE ); 
                $wp_rewrite->flush_rules( true );
                
            }
              
            $wpstream_settings_array =array(
                1   =>  array(
                            'tab'       =>  'general_options',
                            'label'     =>  esc_html__('Slug for free video/channel pages ','wpstream'),
                            'name'      =>  'free_media_slug',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('This will replace the default "wpstream" of all your free video/channel urls. Special characters like "&" are not permitted. To have your new slug show up you need to re-save the "Permalinks Settings" under Settings -> Permalinks, even if not making any changes.','wpstream'),
                        ),
                
                2 => array(
                            'tab'       =>  'general_options',
                            'label'     =>  esc_html__('Non-Admin User Roles Allowed to Broadcast','wpstream'),
                            'name'      =>  'stream_role',
                            'type'      =>  'user_roles',
                            'details'   =>  esc_html__('These types of users can stream via frontend shortcodes / blocks. Single individual channels are automaticlally created for streaming by non-admins.','wpstream'),
                       
                        ),
                3  =>  array(
                            'tab'       =>  'general_options',
                            'label'     =>  esc_html__('Non Admin Streamers Channel Type.','wpstream'),
                            'name'      =>  'user_streaming_channel_type',
                            'type'      =>  'select',
                            'select_values'=>array(
                                'free'  =>  esc_html__('Free Live Channel','wpstream'),
                                'paid'  =>  esc_html__('Pay-Per-View','wpstream')
                            ),
                            'details'   =>  esc_html__('Choose whether the channels assigned to non-admins are free-for-all or pay-per-view (WooCommerce product).','wpstream'),
                        ),
                
                4  =>  array(
                            'tab'       =>  'general_options',
                            'label'     =>  esc_html__('Default Pay-Per-View Price','wpstream'),
                            'name'      =>  'user_streaming_default_price',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('Default price of pay-per-view channels assigned to non-admins.','wpstream'),
                        ),
                
               
                
                6  =>  array(
                            'tab'       =>  'subscription_options',
                            'label'     =>  esc_html__('Use Global Subscription Mode','wpstream'),
                            'name'      =>  'global_sub',
                            'type'      =>  'slidertoogle',
                            'details'   =>  esc_html__('If enabled, a client can access all the media products (live and VOD) by purchasing a single subscription. The "WooCommerce Subscriptions" plugin is required.','wpstream'),
                        ),
                
                7  =>  array(
                            'tab'       =>  'subscription_options',
                            'label'     =>  esc_html__('Subscription ID for Global Subscription Mode','wpstream'),
                            'name'      =>  'global_sub_id',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('ID of the subscription product to be purchased for global access to media. All non-subscription video products that are not already attached to a subscription will be accessible to users that have purchased it.','wpstream'),
                        ),
                8  =>  array(
                            'tab'       =>  'messages_options',
                            'label'     =>  esc_html__('PPV not logged in message','wpstream'),
                            'name'      =>  'product_not_login',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('This message will be displayed on top of the media player for pay-per-view items when user is not logged in.','wpstream'),
                            'default'   =>  esc_html__('You must be logged in to watch this video.','wpstream'),
                        ),
                9  =>  array(
                            'tab'       =>  'messages_options',
                            'label'     =>  esc_html__('PPV not purchased message','wpstream'),
                            'name'      =>  'product_not_bought',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('This message will be displayed on top of the media player for common pay-per-view items that have not been purchased.','wpstream'),
                            'default'   =>  esc_html__('You did not yet purchase this item.','wpstream'),
                        ),
                 10  =>  array(
                            'tab'       =>  'messages_options',
                            'label'     =>  esc_html__('Subscription not purchased message','wpstream'),
                            'name'      =>  'product_not_subscribe',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('This message will be displayed on top of the media player for subscription-type pay-per-view items that have not been purchased.','wpstream'),
                            'default'   =>  esc_html__(' You did not yet subscribe to this item.','wpstream'),
                        ),
                11  =>  array(
                            'tab'       =>  'messages_options',
                            'label'     =>  esc_html__('Thank you message','wpstream'),
                            'name'      =>  'product_thankyou',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('This message will be displayed on the thank you page (after purchase) and the confirmation email.','wpstream'),
                            'default'   =>  esc_html__('Thanks for your purchase. You can access your item at any time by visiting the following page: {item_link}','wpstream'),
                        ),
                
                12  =>  array(
                            'tab'       =>  'messages_options',
                            'label'     =>  esc_html__('Subscription Active message','wpstream'),
                            'name'      =>  'subscription_active',
                            'type'      =>  'text',
                            'details'   =>  esc_html__('This message will be displayed on subscription product page.','wpstream'),
                            'default'   =>  esc_html__('Your Subscription is Active','wpstream'),
                        ),
                13  =>  array(
                    'tab'       =>  'messages_options',
                    'label'     =>  esc_html__('You are not live message','wpstream'),
                    'name'      =>  'you_are_not_live',
                    'type'      =>  'text',
                    'details'   =>  esc_html__('This message will be displayed in player.','wpstream'),
                    'default'   =>  esc_html__('We are not live at this moment','wpstream'),
                ),

              

                
                99  =>  array(
                            'tab'       =>  'default_options',
                            'label'     =>  esc_html__('Events Options  ','wpstream'),
                            'name'      =>  'user_streaming_global_channel_options',
                            'type'      =>  'user_streaming_global_channel_options',
                            'details'   =>  esc_html__('Global Options for live events.','wpstream'),
                        ),

                100  =>  array(
                            'tab'       =>  'default_options_vod',
                            'label'     =>  esc_html__('Autoplay','wpstream'),
                            'name'      =>  'vod_autoplay',
                            'type'      =>  'slidertoogle',
                            'details'   =>  esc_html__('If enabled, video will attempt to start playing automatically. This is only achievable in some browsers.','wpstream'),
                        ),

               
                101  =>  array(
                            'tab'       =>  'default_options_vod',
                            'label'     =>  esc_html__('Start Muted','wpstream'),
                            'name'      =>  'vod_start_muted',
                            'type'      =>  'slidertoogle',
                            'details'   =>  esc_html__('If enabled, video will start muted. This may increase the rate of autoplay in some browsers.','wpstream'),
                        ),
                  
                102  =>  array(
                            'tab'       =>  'default_options_vod',
                            'label'     =>  esc_html__('Lock To Website','wpstream'),
                            'name'      =>  'vod_domain_lock',
                            'type'      =>  'slidertoogle',
                            'details'   =>sprintf ( esc_html__('If enabled, video will only display on %1$s, otherwise it can show up on any website.','wpstream'),get_bloginfo('wpurl') ),
                        ),
                        
                103  =>  array(
                    'tab'       =>  'default_options_vod',
                    'label'     =>  esc_html__('Encrypt Video','wpstream'),
                    'name'      =>  'vod_encrypt',
                    'type'      =>  'slidertoogle',
                    'details'   => esc_html__('If enabled, video data will be encrypted. Enabling encryption may lead to reduced website performance under certain configurations. Encrypted video may not display in all browsers.','wpstream'),
                ),
                      
                /*  'vod_domain_lock'    =>array(
                            'name'      =>  esc_html__('Video On Demand - Lock To Website','wpstream'),
                            'details'   =>  sprintf ( esc_html__('If enabled, VODS will only display on %1$s,  otherwise they can show up on any website.','wpstream'),get_bloginfo('wpurl') ),
                            'defaults'  =>  'no',
                        ),
                'vod_encrypt'   =>array(
                    'name'      =>  esc_html__('Encrypt Video on Demand','wpstream'),
                    'details'   =>  esc_html__('If enabled, video data will be encrypted. Enabling encryption may lead to reduced website performance under certain configurations. Encrypted video may not display in all browsers.','wpstream'),
                    'defaults'  =>  'no',
                ),*/
                        
                        
            );
              
                $active_tab = 'general_options';
                if( isset( $_GET[ 'tab' ] ) ) {
                    $active_tab = $_GET[ 'tab' ];
                } 
                
        
                print '<div class="theme_options_tab_wpstream" style="display:block;" >
                    <h1>'.__('WpStream Settings','wpstream').'</h1>
                    <form method="post" action="" >';

                print '<h2 class="nav-tab-wrapper">
                    <a href="?page=wpstream_settings&tab=general_options"       class="nav-tab '; echo $active_tab == 'general_options' ? 'nav-tab-active' : '';     echo '">'.esc_html__('General Options','wpstream').'</a>
                    <a href="?page=wpstream_settings&tab=default_options"       class="nav-tab '; echo $active_tab == 'default_options' ? 'nav-tab-active' : '';     echo '">'.esc_html__('Default Channel Settings','wpstream').'</a>
                    <a href="?page=wpstream_settings&tab=default_options_vod"   class="nav-tab '; echo $active_tab == 'default_options_vod' ? 'nav-tab-active' : ''; echo '">'.esc_html__('VOD Settings','wpstream').'</a>
                    <a href="?page=wpstream_settings&tab=subscription_options"  class="nav-tab '; echo $active_tab == 'subscription_options' ? 'nav-tab-active' : '';echo '">'.esc_html__('Subscription Options','wpstream').'</a>
                    <a href="?page=wpstream_settings&tab=messages_options"      class="nav-tab '; echo $active_tab == 'messages_options' ? 'nav-tab-active' : '';    echo '">'.esc_html__('Customize Messages','wpstream').'</a>
           
                </h2>';
                $help_link='';
                print '<div class="wpstream_option_wrapper">';

                                switch ($active_tab) {
                                    case 'general_options':
                                        $help_link='https://docs.wpstream.net/docs/general-settings/';
                                        break;
                                    case 'default_options':
                                        $help_link='https://docs.wpstream.net/docs/default-settings/';
                                        break;
                                    case 'default_options':
                                        $help_link='';
                                        break;
                                    case 'subscription_options':
                                        $help_link='https://docs.wpstream.net/docs/subscription-options/';
                                        break;
                                    case 'messages_options':
                                        $help_link='https://docs.wpstream.net/docs/customize-messages/';
                                        break;
                                }

                                if($help_link!==''){
                                    print '<div class="wpstream_options_help"><a href="'.esc_url($help_link).'" target="_blank" >'.esc_html__('Video Help','wpstream').'</a></div>';
                                }

                                foreach ($wpstream_settings_array as $key=>$option){
                                   if($option['tab']!=$active_tab){
                                       continue;
                                   }
                                   
                                   print '<div class="wpstream_option">';
                                            $options_value =   get_option('wpstream_'.$option['name']) ;
                                      
                                        
                                            if($option['type']=='user_roles'){
                                           
                                                print '<label for="'.$option['name'].'">'.$option['label'].'</label>';
                                                print $this->wpstream_select_user_roles($option['name'],$options_value);
                                                print '<div class="settings_details">'.$option['details'].'</div>';

                                            }else if($option['type']=='user_streaming_global_channel_options'){
                                             
                                                $exclude_array=array();
                                                $this->user_streaming_global_channel_options($option['name'],$options_value,$exclude_array);
                                             
                                                
                                            }else if($option['type']=='text'){

                                                if($options_value==''){
                                                    $options_value='';
                                                    if(isset($option['default'])){
                                                        $options_value=$option['default'];
                                                    }
                                                }
                                                
                                                print '<label for="'.$option['name'].'">'.$option['label'].'</label>';
                                                print '<input id="'.$option['name'].'" type="'.$option['type'].'" size="36"  name="'.$option['name'].'" value="'.esc_attr($options_value).'" />';
                                                print '<div class="settings_details">'.$option['details'].'</div>';
                                                
                                            }  else if($option['type']=='select'){
                                                
                                                print '<label for="'.$option['name'].'">'.$option['label'].'</label>';
                                                print '<select id="'.$option['name'].'"  name="'.$option['name'].'"  >';
                                                    foreach($option['select_values'] as $key=>$value){
                                                        print '<option value="'.$key.'" ';
                                                        if( $key == esc_html($options_value) ){
                                                            print ' selected ';
                                                        }
                                                        print '>'.$value.'</option>';
                                                    }
                                                print '</select>';
                                                print '<input type="hidden" name="'.$option['name'].'_hidden" value="1" >';
                                                print '<div class="settings_details">'.$option['details'].'</div>';
                                                
                                            } else if($option['type']=='slidertoogle'){
                                                print '<label for="'.$option['name'].'">'.$option['label'].'</label>';
                                                print '<label class="wpstream_switch">
                                                      <input type="hidden" class="wpstream_event_option_itemc" value="0" name="'.$option['name'].'" >
                                                      <input type="checkbox" class="wpstream_event_option_itemc" value="1" name="'.$option['name'].'" ';
                                                      
                                                            if( intval($options_value) !==0 ){
                                                                print ' checked ';
                                                            }
                                                      


                                                    print '> <span class="wpstream_slider round"></span>';
                                                    print '</label>';
                                                    print '<div class="settings_details">'.$option['details'].'</div>';
                                            }
                                   print '</div>';
                               }
                           print '</div>';


                       print '<input type="submit" name="submit"  class="wpstream_button wpstream_button_action" value="'.__('Save Changes','wpstream').'" />';

                    print  '<input name="wpstream-settings-nonce" type="hidden" value="'.wp_create_nonce('wpstream-settings-nonce').'" /> ';     
            print   '</form>';
        print '</div>';
         
         }
        
         
         
        /**
         * Set user roles
         *
         * @since    3.0.1
         */  

        public function user_streaming_global_channel_options($name,$value,$local_array=''){
        
            foreach($this->global_event_options as $key=>$option){
               
                if(  is_array($local_array) && !in_array($key,$local_array)){
                    print '<div class="wpstream_setting_event_unit_wrapper wpstream-setting-'.esc_attr($key).' ">';

                    print '<label for="'.$option['name'].'">'.$option['name'].'</label>';

                    print '
                    <label class="wpstream_switch">
                      <input type="checkbox" class="wpstream_event_option_item" data-attr-ajaxname="'.esc_attr($key).'" name="wpstream_event_set_'.esc_attr($key).'" ';
                        if( isset($value[$key]) ){
                            if( intval($value[$key]) !==0 ){
                                print ' checked ';
                            }
                        }else{
                            if($option['defaults']=='yes') {
                                print ' checked ';
                            }
                        }


                    print '> <span class="wpstream_slider round"></span>';
                    print '</label>';
                    print '<div class="settings_details">'.$option['details'].'</div>';


                print '</div>';
                }
            }
            
            
         }
         

       
      



         
         
        /*
         * Set user roles
         *
         * @since    3.0.1
         */   
        
        public function wpstream_select_user_roles($name,$value){
            if($value==''){
                $value=array();
            }
            
            $roles  =   get_editable_roles();
            $return =   '<select id="wpstream_user_roles" name="'.esc_html($name).'[]"  multiple>';
            unset( $roles['administrator'] );
            
            foreach ($roles as $key=>$role){
                $return .= '<option value="'.$key.'" ';
                if( in_array($key, $value) ){
                    $return .= ' selected ';
                }
                $return .= '>'.$role['name'].'</option>';
            }
            $return .=  '</select>';
             
            
            return $return;
        }
         
            
        /*
         * Set credential admin function
         *
         * @since    3.0.1
        */       
        public function wpstream_set_wpstream_credentials(){
    
            if($_SERVER['REQUEST_METHOD'] === 'POST'){  
                $allowed_html   =   array();
                $exclude_array  =   array();
                $allowed_html   =   array();

                foreach($_POST as $variable=>$value){   
                    if ($variable!='submit'){
                        if (!in_array($variable, $exclude_array) ){
                            update_option( sanitize_key('wpstream_'.$variable), wp_kses ($value,$allowed_html) );
                        }   
                    }   
                }
                
        


                update_option('wp_estate_token_expire',0);
                update_option('wp_estate_curent_token',' ');
                delete_transient( 'wpstream_token_api');
                delete_transient('wpstream_token_request_30');
                delete_transient('wpstream_request_pack_data_per_user_transient');
            }
       
    
            $allowed_html   =   array();
         


            $wpstream_options_array =array(
                2   =>  array(
                            'label' =>  'WpStream.net Username or Email',
                            'name'  =>  'api_username',
                            'type'  =>  'text',
                        ),
                3   =>  array(
                            'label' =>  'WpStream.net Password',
                            'name'  =>  'api_password',
                            'type'  =>  'password',
                        ),

            );


            $token          =   $this->main->wpstream_live_connection->wpstream_get_token();
            $pack_details   =   $this->main->wpstream_live_connection->wpstream_request_pack_data_per_user();
            
            $this->main->show_user_data($pack_details);

            print   '<form method="post" action="" >';
                        print '<div  class="theme_options_tab_wpstream" style="display:block;" >
                                <h1>'.__('WpStream Credentials','wpstream').'</h1>';

                        
                                if( get_option('wpstream_api_username')=='' ||  get_option('wpstream_api_password')== '' ){
                                    echo '<div class="api_not_conected wpstream_orange">';
                                        $admin_url_onboard=get_admin_url().'admin.php?page=wpstream_onboard';
                                        printf ( __('To connect your plugin, enter your WpStream credentials below or go <a href="%s" target="_blank">here</a> to create an account.','wpstream'),$admin_url_onboard);
                                    echo '</div>';

                                }else if($token==''){
                                    $text = get_option('wpstream_curl_failed') == false ?
                                        ' Incorrect username or password. Please check your credentials or go <a href="https://wpstream.net/my-account/edit-account/" target="_blank">here</a> to reset your password.' :
                                        'Not connected to WpStream. Please note the errors above and contact support.';
                                    echo '<div class="api_not_conected">'.__($text,'wpstream').'</div>';
                                }else if( $this->main->wpstream_live_connection->wpstream_client_check_api_status() ){
                                    echo '<div class="api_conected">'.__('Connected to WpStream.net!','wpstream').'</div>';
                                }else{
                                    echo '<div class="api_not_conected wpstream_brown">'.__('Failed to connect to WpStream.net. Please address CURL connectivity with your hosting provider.','wpstream').'</div>';
                                }
                                print '<div class="wpstream_option_wrapper">';
                                    foreach ($wpstream_options_array as $key=>$option){
                                        print '<div class="wpstream_option">';

                                            $options_value =  esc_html( get_option('wpstream_'.$option['name'],'') );
                                            print '<label for="'.$option['name'].'">'.$option['label'].'</label>';
                                            print '<input id="'.$option['name'].'" type="'.$option['type'].'" size="36"  name="'.$option['name'].'" value="'.esc_html($options_value).'" />';

                                        print '</div>';
                                    }
                                print '</div>';


                            print '<input type="submit" name="submit"  class="wpstream_button wpstream_button_action" value="'.__('Save Changes','wpstream').'" />';

                            print '<h3>Video Tutorials</h3>';
                 
                            print '<a class="how_to_videos" target="_blank" href="https://youtu.be/9DQrxsKcpmQ">How to Live Stream to WordPress with OBS</a>';
                            print '<a class="how_to_videos" target="_blank" href="https://youtu.be/qMSjJCskAfM">How to Live Stream to WordPress in less than 3 Minutes</a>';                            
                            print '<a class="how_to_videos" target="_blank" href="https://youtu.be/h6myD_vhKcg">How to Live-Stream to WordPress using your iPhone</a>';
                            
                            print '<a style="margin-top:10px;" href="https://www.youtube.com/channel/UCIjItiJc4Z7aJApj3W6ArJA" target="_blank" class="how_to_videos">More Tutorials On Our YouTube Channel</a>';
                            

                        print '</div>';
            print   '</form>';

            print '<div  class="theme_options_tab_wpstream" style="display:block;" >';
                $link_new = admin_url('admin.php?page=wpstream_live_channels');
                $link_new_paid = admin_url('post-new.php?post_type=product').'&new_stream='. rawurlencode('new');
                $link_new_free = admin_url('post-new.php?post_type=wpstream_product');
 

                print '<a href="'.esc_url($link_new_free).'" class="wpstream_no_chanel_add_channel">'.esc_html__('Create new Free-To-View channel','wpstream').'</a>';
                print '<a href="'.esc_url($link_new_paid).'" class="wpstream_no_chanel_add_channel">'.esc_html__('Create Pay-Per-View channel','wpstream').'</a>';
                print '<a href="'.esc_url($link_new).'"      class="wpstream_no_chanel_add_channel">'.esc_html('My Channels','wpstream').'</a>';        
            print '</div>';
   

    }


  
        /**
        * Media Management
        *
        * @since    3.0.1
        */     

        public function wpstream_media_management(){
            $pack_details           =    $this->main->wpstream_live_connection->wpstream_request_pack_data_per_user();

            $this->main->show_user_data($pack_details);


            print '<div id="wpstream_media_upload"><h3>'.__('Upload New Recording','wpstream').'</h3>'.$this->wpstream_present_media_upload().'</div>';

            print '<div id="wpstream_file_management"><h3 id="video_management_title">'.__('Your Recordings','wpstream').'</h3>'.$this->wpstream_present_file_management().'</div>';

     
        }


        
        
        /**
         * 
         * 
        * WpStream Pagination
        *
        * @since    3.0.1
            * 
            * 
        */ 
        
        public function wpstream_pagination($pages , $range = 2) {
            $return='';
            $showitems = ($range * 2) + 1;
            $paged        =   ( isset( $_GET['paged'] ) ) ? intval($_GET['paged']) : 1;


            if (1 != $pages && $pages != 0) {
                $return.= '<ul class="pagination wpstream_pagination">';
                $return.= "<li class=\"roundleft\"><a href='" . get_pagenum_link($paged - 1) . "'><</a></li>";

                $last_page = get_pagenum_link($pages);
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                        if ($paged == $i) {
                            $return.=  '<li class="active"><a href="' . esc_url(get_pagenum_link($i)) . '" >' . $i . '</a><li>';
                        } else {
                            $return.=  '<li><a href="' . esc_url(get_pagenum_link($i)) . '" >' . $i . '</a><li>';
                        }
                    }
                }

                $prev_page = get_pagenum_link($paged + 1);
                if (($paged + 1) > $pages) {
                    $prev_page = get_pagenum_link($paged);
                } else {
                    $prev_page = get_pagenum_link($paged + 1);
                }


                $return.=  "<li class=\"roundright\"><a href='" . $prev_page . "'>></a><li>";
                $return.=  "<li class=\"roundright\"><a href='" . $last_page . "'>>><li>";
                $return.=  "</ul>";
            }
            return $return;
        }
        
        
        
        
        
  
        /**
         * Media upload
         *
         * @since    3.0.1
         */  
        public function wpstream_present_media_upload(){
            $to_return='';
            $formInputs=$this->main->wpstream_live_connection->wpstream_get_signed_form_upload_data();
           
            if( !$formInputs['success'] ){
                if ($formInputs['error'] == 'not_connected'){
                    $to_return.='<div class="wpstream_upload_container">'.esc_html__('Not connected. Please connect to WpStream to upload videos.','wpstream').'</div>';
                }
                else {
                    $to_return.='<div class="wpstream_upload_alert">'.esc_html__('There isn\'t enough storage space under your account to upload a new item. Please delete some videos or upgrade your subscription.','wpstream').'</div>';
                }
                return $to_return;
            }

            if($formInputs['success'] ===true){

                  

                    $to_return.='<div class="wpstream_upload_container">';
                    $to_return.='<div id="wpstream_uploaded_mes">'.esc_html__('Please select or drop a video file. Do not close this window during the upload!','wpstream').'</div>';
                    $to_return.='<form action="https://wpstream-video.s3.amazonaws.com/"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  data-singleFileUploads="true"
                                  data-limitMultiFileUploads="1"
                                  data-limitConcurrentUploads="1"
                                  class="direct-upload">';

                    $to_return.='<input id="wpstream_upload" type="file" class="inputfile inputfile-1" value="Pick a video file" name="file" multiple>';
                    $to_return.='<label for="wpstream_upload"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span id="wpstream_label_action">Choose a file&hellip;</span></label>';


                    $to_return.='<div class="wpstream_file_drop_color">';
                    $to_return.='<div class="wpstream_form_ex">'.esc_html__('Drop a video file here!','wpstream').'</div>';      
                    $to_return.='<div class="wpstream_form_ex_details">'.__('The Video File must be encoded with the following settings:

                    Container: <strong>MP4</strong>,
                    Video codec: <strong>H264</strong>,
                    Audio codec: <strong>AAC</strong>. Media will fail to play if it does not follow the above settings. 
                    You may use a tool like MediaInfo to verify your file. Also you may convert it with specialized software like HandBrake.','wpstream').'<strong> '.esc_html__('Accepted file extensions: .mp4, .mov','wpstream').'</strong></div>';    
                    if(is_array($formInputs)){      
                        foreach ($formInputs['ref'] as $name => $value) { 
                                $to_return.='<input type="hidden" name="'. $name.'" value="'.$value.'">';
                        }                     
                    }                  

                    $to_return.='
                    <div class="progress-bar-area"></div></div>
                    </form>';

                    $to_return.='</div>';
            }     
            
            return $to_return;

        }

   



        /**
         * Display movie list
         *
         * @since    3.0.1
         */  
        public function wpstream_present_file_management(){
                $video_list_raw = $this->main->wpstream_live_connection->wpstream_get_videos_from_api();

                $video_list_raw_array=array();
                if(isset($video_list_raw['items'])){
                    $video_list_raw_array=$video_list_raw['items'];
                }
            
                $keys = array_column($video_list_raw_array, 'time');
                array_multisort($keys, SORT_DESC , $video_list_raw_array);
                
                $to_return='';
              
                if(is_array($video_list_raw['items'])){
                    foreach ($video_list_raw_array as $key =>$video){
                        
                    
                        $video_size = intval($video['size']/1048576);
                        $video_name = esc_html($video['name']);
                        if($video_name!=''):
                            $to_return.='<div class="wpstream_video_wrapper">';

                                $to_return.='<div class="wpstream_video_title">';
                                $to_return.='<div class="wpstream_video_notice"></div></div>';
                                $to_return.='<div class="wpstream_video_title"><strong class="storage_file_name">'.esc_html__('File Name :','wpstream').'</strong>'.'<span class="storage_file_name_real">'.$video_name.'</span><span class="storage_file_size">'.$video_size.' MB </span></div>';
                                $to_return.=' <div class="wpstream_delete_media" ';
                                $to_return.=' onclick="return confirm(\' Are you sure you wish to delete '.$video_name.'?\')" data-filename="'.$video_name.'">'.esc_html__('delete file','wpstream').'</div>';
                                $to_return.='<div class="wpstream_get_download_link" data-filename="'.$video_name.'">'.esc_html__('download','wpstream').'</div>';
                                $to_return.='<a href="" class="wpstream_download_link">'.esc_html__('Click to download! The url will work for the next 20 minutes!','wpstream').'</a>';

                                $add_free_video_url=admin_url('post-new.php?post_type=wpstream_product_vod').'&new_video_name='. rawurlencode($video_name);
                                $add_paid_video_url=admin_url('post-new.php?post_type=product').'&new_video_name='. rawurlencode($video_name);



                                $to_return .='<a class="create_new_free_video" href="'.esc_url($add_free_video_url).'">'.esc_html__('Create new Free-To-View VOD from this recording').'</a>'; 
                                $to_return .='<a class="create_new_ppv_video" href="'.esc_url($add_paid_video_url).'">'.esc_html__('Create new Pay-Per-View VOD from this recording').'</a>'; 

                            $to_return.='</div>';
                        endif;
                        
                    }
                    $current_page= get_current_screen();
        
                    
               } else {
                   $to_return.= '<div class="wpstream_video_wrapper">'.esc_html__('You don\'t have any videos.','wpstream').'</div>';
               }
               return $to_return;
        }


         /**
         * Set defualt channels values
         *
         * @since    3.0.1
         */  

    
    

        public function wpstream_publish_wpstream_product($post_id,$post){
            if( $post->post_type == 'wpstream_product' ){
                update_post_meta ($post_id,'local_event_options_test','working_on_'.$post_id);
                $to_save_option=array();

                $global_options= get_option('wpstream_user_streaming_global_channel_options');
              
                $local_events =  get_post_meta ($post_id ,'local_event_options',true) ;
              
                if( $local_events =='' ){
                    if( is_array($global_options) ){
                        foreach($global_options as $key=>$value){
                            $to_save_option[sanitize_key($key)]=sanitize_text_field($value);
                        }
                        
                        update_post_meta ($post_id,'local_event_options',$to_save_option);
                    
                    }
                }


            }
        }

        /**
         * save meta options
         *
         * @since    3.0.1
         */  
        public function  wpstream_free_product_update_post($post_id,$post){
            
            if(!is_object($post) || !isset($post->post_type)) {
                return;
            }


      




            if( $post->post_type == 'wpstream_product' ||
                $post->post_type == 'wpstream_product_vod' ):
            
                $allowed_keys=array(
                    'wpstream_product_type',
                    'wpstream_free_video',
                    'wpstream_free_video_external'
                );


                foreach ($_POST as $key => $value) {
                    if( !is_array ($value) ){
                        if (in_array ($key, $allowed_keys)) {
                            $postmeta = sanitize_text_field ( $value ); 
                            update_post_meta($post_id, sanitize_key($key), $postmeta );
                        }
                    }       
                }

            endif;

        }
        
        
         /**
         * save meta options
         *
         * @since    3.0.1
         */ 
        public function add_wpstream_product_metaboxes() {
            global $post;
            $post_id = $post->ID;
         
              
      
                                  
            add_meta_box(  'add_wpstream_product_metaboxes-sectionid',  esc_html__( 'Video On Demand Settings', 'wpstream' ),array($this,'display_meta_options'),'wpstream_product_vod' ,'normal','default');
            add_meta_box( 'custom_metabox_video_collection',            esc_html__( 'Video Collection', 'wpstream' ), 'wpstream_bundle_custom_metabox_callback', 'wpstream_bundles', 'normal', 'high' );
            add_meta_box( 'woocommerce-product-data',                   esc_html__( 'Product Data', 'wpstream' ), 'woocommerce_product_data_box', 'product', 'normal', 'default' );
            
            if(function_exists('wc_get_product')):
                $product = wc_get_product( $post_id );
                if ( $product ) {

                    if ( $product->get_type() === 'wpstream_bundle' ) {
                        add_meta_box(
                            'wpstream_woo_custom_metabox',
                            esc_html__( 'Video Collection Options', 'wpstream' ),
                            'wpstream_bundle_custom_metabox_callback',
                            'product', 
                            'normal',
                            'default'
                        );
                    }
                }
            endif;

        }
        
        
         /**
         * make woocomerce virtual products
         *
         * @since    3.0.1
         */
        
        
        public function wpstream_make_product_virtual($post_id,$post){
            global $post;
            if(isset($post->ID)){
                if ( $post->post_type !== 'product' ) return;
                $term_list      =   wp_get_post_terms($post->ID, 'product_type');
                if( $term_list[0]->name=='video_on_demand' ||  
                    $term_list[0]->name=='live_stream' ||  
                    $term_list[0]->name=='wpstream_bundle'){
                        update_post_meta( $post->ID, '_virtual', 'yes' );
                }
            }
        }
        
        
        
        /**
         * render meta options
         *
         * @since    3.0.1
         */ 
        public function display_meta_options( $post ) {
                wp_nonce_field( plugin_basename( __FILE__ ), 'estate_agent_noncename' );
                global $post;

                $is_live               =    '';
                $is_video              =    '';
                $is_video_external     =    '';
                if( isset( $_GET['new_video_name']) && $_GET['new_video_name']!=''  ){
                    $is_video               =   ' selected ';
                    $wpstream_free_video    =   esc_html( $_GET['new_video_name']);
                }else{
                    $wpstream_product_type  =    esc_html(get_post_meta($post->ID, 'wpstream_product_type', true));
                    $wpstream_free_video    =    esc_html(get_post_meta($post->ID, 'wpstream_free_video', true));
                    
                    if($wpstream_product_type==1){
                        $is_live = ' selected ';
                    }
                   
                    if($wpstream_product_type==2){
                        $is_video = ' selected ';
                    }

                    if($wpstream_product_type==3){
                        $is_video_external = ' selected ';
                    }
                }

                print'
                <p class="meta-options">
                    <label for="wpstream_product_type">'.__('Media Type:','wpstream').' </label><br />
                    <select id="wpstream_product_type" name="wpstream_product_type">
                        <option value="2" '.$is_video.'>'.__('Recording','wpstream').'</option>
                        <option value="3" '.$is_video_external.'>'.__('Self Hosted or External Video','wpstream').'</option>
                    </select>
                </p>        
                ';           


                $video_list =  $this->main->wpstream_live_connection->wpstream_get_videos();
                

                print '
                <p class="meta-options video_free">
                    <label for="wpstream_free_video">'.__('Choose video:','wpstream').' </label><br />
                    <select id="wpstream_free_video" name="wpstream_free_video">';
                        
                        if(is_array($video_list)){
                            foreach ($video_list as $key=>$value){
                                print '<option value="'.$key.'"'; 
                                if($wpstream_free_video === $key){
                                   print ' selected ';
                                }
                                print '>'.$value.'</option>';
                            }
                        }
                        
                 print'
                    </select>
                </p>        
                ';  

                $wpstream_free_video_external=    esc_html(get_post_meta($post->ID, 'wpstream_free_video_external', true));
                print '<p class="meta-options1 video_free_external">
                        <label for="wpstream_free_video_external">'.__('Video:','wpstream').' </label><br />

                        <input id="wpstream_free_video_external" type="text" size="36" name="wpstream_free_video_external" value="'.$wpstream_free_video_external.'" />
                        <input id="wpstream_free_video_external_button" type="button"   size="40" class="upload_button button" value="'.esc_html__('Select Video','wpstream').'" />';
                        if($wpstream_product_type==2){
                            $show_recording='';
                            $show_external='style="display:none"';
                        }else{
                            $show_recording='style="display:none"';
                            $show_external='';
                        }
                        print '<p '.$show_recording.' class="wpstream_option_vod_source wpstream_show_recording">'.esc_html__('Choose one of your existing recordings.','wpstream').'</p>';
                        print '<p '. $show_external.' class="wpstream_option_vod_source wpstream_show_external">'.esc_html__('Upload a video from your computer or paste the URL of a YouTube/external video.','wpstream').'</p>';
                     
                print '</p> ';
        }
        
        
        
        
        
       
        
         /**
        * Add new product types to Woocommerce select product type
        *
        * @since    3.0.1
        */ 
        public function wpstream_add_products( $types ){
            $types[ 'live_stream' ]             = __( 'Live Channel','wpestream' );
            $types[ 'video_on_demand' ]         = __( 'Video On Demand','wpestream' );
            
            return $types;
        }
        
        
        
         /**
        * Js action to do when user pick live stream or video on demand
        *
        * @since    3.0.1
        */ 
        
        public function wpstream_products_custom_js() {
            if ( 'product' != get_post_type() ) :
                return;
            endif;

            ?>
            <script type='text/javascript'>
                jQuery( document ).ready( function() {
                    jQuery('.options_group.pricing' ).addClass ( 'show_if_live_stream' ).show();
                    jQuery('.options_group.pricing' ).addClass ( 'show_if_video_on_demand' ).show();
                    jQuery('.options_group.pricing' ).addClass ( 'show_if_wpstream_bundle' ).show();
                    
                    jQuery('._sold_individually_field').parent().addClass('show_if_live_stream').show();
                    jQuery('._sold_individually_field').parent().addClass('show_if_video_on_demand').show();
                    jQuery('._sold_individually_field').parent().addClass('show_if_wpstream_bundle').show();
                                
                    jQuery('._sold_individually_field').show();
             
                    var selected = jQuery('#product-type').val();
                });
            </script>
            <?php

        }
         
        
        /**
        * Add custom classes to the product types
        *
        * @since    3.0.1
        */ 
        
        public function wpstream_hide_attributes_data_panel( $tabs) {
        
            $tabs['shipping']['class'][] = 'hide_if_live_stream  hide_if_video_on_demand hide_if_wpstream_bundle';
            $tabs['inventory']['class'][] = 'show_if_live_stream  show_if_video_on_demand show_if_wpstream_bundle';
       
            return $tabs;
        }
        
        
           
        /**
        * Hide buy now on products if Netflix mode
        *
        * @since    3.12
        */ 
        
        
        public function  wpstream_hide_buy_now_subscription_mode( $purchaseable_product_wpblog,$product){
            $product_id=$product->get_id();
           
            $term_list              =       wp_get_post_terms($product_id, 'product_type');
            
            $subscription_model     =       intval( get_option('wpstream_global_sub','')) ;
       
            if($subscription_model==1){ // if we have Neflix mode               
                if( $term_list[0]->name=='live_stream' || $term_list[0]->name=='video_on_demand' || $term_list[0]->name=='wpstream_bundle' ){ 
                    return false;
                }
            }
            
            return  $purchaseable_product_wpblog;
        }
        
        
        
        
        
        
         /**
        * Add custom fields to custom product types
        *
        * @since    3.0.1
        */ 
        
        public function wpstream_add_custom_general_fields() {

            global $woocommerce, $post;
            if(function_exists('wcs_user_has_subscription')){
                echo '<div class="options_group   show_if_subscription">';  
                    woocommerce_wp_select( 
                        array( 
                            'id'      =>    '_subscript_live_event', 
                            'label'   =>    __( 'Is a subscription based live channel ?', 'woocommerce' ), 
                            'options' =>    array("yes"=>"yes","no"=>"no")
                            )
                        );
                echo '</div>';
            }

            echo '<div class="options_group show_if_live_stream" style="border:none;"></div>';  
            echo '<div class="options_group show_if_video_on_demand">';  
                $selected='';
                if( isset( $_GET['new_video_name']) && $_GET['new_video_name']!=''  ){
                    $selected=esc_html($_GET['new_video_name']);
                }
                if($selected==''){
                   $selected= get_post_meta($post->ID,'_movie_url',true);
                }

                woocommerce_wp_select( 
                    array( 
                        'id'      =>    '_movie_url', 
                        'label'   =>    __( 'Choose video', 'woocommerce' ), 
                        'options' =>     $this->main->wpstream_live_connection->wpstream_get_videos(),
                        'selected'=>    true,
                        'value'    =>   $selected
                        )
                );
                
              

            echo '</div>';
            
            if(function_exists('wcs_user_has_subscription')){
                $selected_sub='';
                echo '<div class="options_group show_if_video_on_demand show_if_live_stream">';  
                    if( isset( $_GET['wpstream_parent_sub']) && $_GET['wpstream_parent_sub']!=''  ){
                        $selected_sub=esc_html($_GET['wpstream_parent_sub']);
                    }
                    if($selected_sub==''){
                       $selected_sub= get_post_meta($post->ID,'_wpstream_parent_sub',true);
                    }
                    woocommerce_wp_select( 
                    array( 
                        'id'      =>    '_wpstream_parent_sub', 
                        'name'    =>    '_wpstream_parent_sub[]',
                        'label'   =>    __( 'Attach to subscription', 'woocommerce' ), 
                        'options' =>     $this->wpstream_return_subscriptions_created(),
                        'selected'=>    true,
                        'value'   =>   $selected_sub,
                        'custom_attributes' => array('multiple' => 'multiple')
                        )
                );
                
                echo '</div>';
            
            }
        }
        
        
        public function wpstream_return_subscriptions_created(){
            $return=array('0'=>'none');
           
            $args  = array(
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                                'taxonomy' => 'product_type',
                                'field'    => 'slug',
                                'terms'    => array( 'subscription'),
                        )
                    )
                );
              
            $subscriptions = new WP_Query($args);
            if($subscriptions->have_posts()):
                while ($subscriptions->have_posts()): $subscriptions->the_post();
                    $return[ get_the_ID() ] = get_the_title();
                endwhile;
            endif;
            
            wp_reset_postdata();
            return $return;
            
        }
        
        
        
        
        

        /**
        * Save custom fields
        *
        * @since    3.0.1
        */ 
        
        public function wpstream_add_custom_general_fields_save( $post_id ){

            $permited_values = array(
                '_movie_url',
                '_subscript_live_event',
                '_wpstream_parent_sub',
              
            );
         
        
           
            foreach($_POST as $key=>$value){
                update_post_meta( $post_id, 'event_passed', 0 );
                if( in_array($key, $permited_values) ){
                    if( !empty( $_POST[$key] ) ){
                        $key    =   sanitize_key($key);
                        $value  =   sanitize_text_field($_POST[$key]);
                        
                        if($key=='_wpstream_parent_sub'){
                            $value= $_POST[$key];
                            $value = array_map("sanitize_text_field", $value);
                            
                        }
                        update_post_meta( $post_id, $key, $value );
                    }
                }
            }
            //die();

        }
        
         /**
        * Add to cart redirect
        *
        * @since    3.0.1
        */ 
        
        public function wpstream_add_to_cart() {
            wc_get_template( 'single-product/add-to-cart/simple.php' );
        }

        
        /**
        * Replace add to cart button
        *
        * @since    3.0.1
        */ 
        public function replacing_add_to_cart_button( $button, $product  ) {
            global $product;
            $product_type = $product->get_type();

            if($product_type==='live_stream' || $product_type=='video_on_demand'){
                return $button = '<a class="button" href="'.get_site_url().'/shop/?add-to-cart=' .$product->get_id(). '&quantity=1">' . __( 'Add to Cart', 'woocommerce' ) . '</a>';
            }else{
                return $button;
            }
        }
       

         /**
        * Admin notices
        *
        * @since    3.0.1
        */ 
        public function wpstream_admin_notice() {
            global $pagenow;
            global $typenow;

            $wpstream_notices =  get_option('wpstream_notices');
          
            /*
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                if( !is_array($wpstream_notices) ||
                !isset($wpstream_notices['wpstream_woo_notice']) ||
                ( isset($wpestate_notices['wpstream_woo_notice']) && $wpestate_notices['wpstream_woo_notice']!='yes')  ){

     
                print '<div class="notice wpstream_notices notice-error is-dismissible" data-notice-type="wpstream_woo_notice" >
                    <p>'.__( 'WpStream Pay-Per-View Live Streaming and VOD only works with WooCommerce - Please enable and activate the WooCommerce plugin if you want to monetize your Live Events or Recorded Videos', 'wpstream' ).'</p>
                </div>';
                }
            }
            */
            
            if( !in_array  ('curl', get_loaded_extensions())) {
                print '<div class="notice  notice-error is-dismissible">
                    <p>'.__( 'The php CURL library is not enabled on your server. WpStream plugin needs this library in order to work. Please address this issue with your hosting provider.', 'wpstream' ).'</p>
                </div>';
            }


            $ajax_nonce = wp_create_nonce( "wpstream_notice_nonce" );
            print '<input type="hidden" id="wpstream_notice_nonce" value="'.esc_html($ajax_nonce).'"/>';

        }
        
          /**
        * Admin notices
        *
        * @since    3.0.1
        */ 
        public function wpstream_update_cache_notice(){

            //check_ajax_referer( 'wpstream_notice_nonce', 'security'  );

            $notice_type    =   esc_html($_POST['notice_type']);
            $notices        =   get_option('wp_stream_notices');

            if(! is_array($notices) ){
                $notices=array();
            }

            $notices[$notice_type]='yes';

            update_option('wpstream_notices',$notices);
            die();
        }
        
       
        
        
        /**
        * Activate metaboxes for Streaming controls on sidebar
        *
        * @since    3.0.1
        */ 
         public function wpstream_startstreaming_sidebar_meta() {
                global $post;
                $term_list                          =   wp_get_post_terms($post->ID, 'product_type');
               
                add_meta_box('wpstream-sidebar-meta',       esc_html__('Live Streaming',  'wpstream'), array($this,'wpstream_start_stream_meta'), 'wpstream_product', 'side', 'high');
                
                $is_subscription_live_event =   esc_html(get_post_meta($post->ID,'_subscript_live_event',true));
                if(!is_wp_error( $term_list )){     
                    if( isset($term_list[0]->name) ){        
                        if( $term_list[0]->name=='live_stream' ||  ($term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' )  ){    
                            add_meta_box('wpstream-sidebar-meta',       esc_html__('Live Streaming',  'wpstream'), array($this,'wpstream_start_stream_meta'), 'product', 'side', 'high');
                        }
                    }
                }

        }
        
        /**
        * edited 4.0
        * 
        * Show Streaming controls on sidebar
        *
        * @since    3.0.1
        */ 
        public function wpstream_start_stream_meta(){
            global $live_event_for_user;
            $live_event_for_user    =    $this->main->wpstream_live_connection->wpstream_get_live_event_for_user();
         
            global $post;
            $local_event_options = get_post_meta ($post->ID,'local_event_options','');
           


            if( get_post_status($post->ID)!='auto-draft' ){
                $ajax_nonce = wp_create_nonce( "wpstream_start_event_nonce" );
                print '<input type="hidden" id="wpstream_start_event_nonce" value="'.$ajax_nonce.'">';
    
                $this->wpstream_live_stream_unit($post->ID);
                print '<div class="wpstream_modal_background"></div>';
                print '<div class="wpstream_error_modal_notification"><div class="wpstream_error_content">er1</div>
                <div class="wpstream_error_ok wpstream_button" type="button">'.esc_html__('Close','wpstream').'</div>
                </div>';
            }else{
                esc_html_e('To Go Live, please publish your channel first !','wpstream');
            }
        }






   /**
        * 
        *
        *
        */
        public function add_dashboard_page() {
            add_dashboard_page( '', '', 'administrator', 'wpstream-onboarding', '' );
        }
      




        public function wpstream_load_onboarding_wizard() {

            // Check for wizard-specific parameter
            // Allow plugins to disable the onboarding wizard
            // Check if current user is allowed to save settings.
       
    
            // Don't load the interface if doing an ajax call.
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                return;
            }
    
            set_current_screen();
    
            // Remove an action in the Gutenberg plugin ( not core Gutenberg ) which throws an error.
            remove_action( 'admin_print_styles', 'gutenberg_block_editor_admin_print_styles' );
    
            $this->actual_load_onboarding_wizard();
    
        }


        /*
        * Add on boarding to footer
        */



        public function wpstream_admin_footer_onboarding(){
            if( isset($_GET['page']) &&  $_GET['page']==='wpstream_onboard') {    
                $this->wpstream_onboard_display();
            }
        }
        

        /*
        * On Board Display
        * 
        */
        
        public function wpstream_pre_onboard_display(){
            $pack_details           =    $this->main->wpstream_live_connection->wpstream_request_pack_data_per_user();

            $this->main->show_user_data($pack_details);


            $thumb= plugin_dir_url( dirname( __FILE__ ) ). 'img/logo_onboarding.svg'; 
            ?>

                <div class="wpstream_quick_start_wrapper">
                    <img class="wpstream_onboarding_logo" src="<?php echo esc_url($thumb); ?>" />

                    <h1><?php print esc_html__('WpStream','wpstream').' <span class="header_special">'.esc_html__('Quick Start','wpstream').'</span>';?></h1>

                 
                        <p>
                            <?php esc_html_e('The quick start guide will help you set up Live Streaming, Video On Demand, and Monetization in a fun and interactive way. Give it a shot! ','wpstream');?>
                        </p>

                        <div id="wpstream_trigger_quick_start" class="wpstream_button wpstream_button_action"><?php esc_html_e('Start the Guide','wpstream');?></div>

                 

                </div>

                    
               
                    <script type="text/javascript">
                        //<![CDATA[
                            jQuery(document).ready(function(){
                                jQuery(".wpstream_on_boarding_wrapper").show();
                                jQuery(".wpstream_modal_background_onboard").show();
                            });
                        
                        //]]>
                    </script>

            <?php
               
        }        

        






        /*
        *
        * On Boarding Content
        *
        */


        public function wpstream_onboard_display() {
                $this->onboarding_wizard_header();
                $this->wpstream_onboarding_step1();
                $this->wpstream_onboarding_step2();
                $this->wpstream_onboarding_step3_live_streaming();
                $this->wpstream_onboarding_step_3_A_live_streaming_free_view();
                $this->wpstream_onboarding_step_3_B_live_streaming_pay_per_view();
                $this->wpstream_onboarding_step4_vod();
                $this->wpstream_onboarding_step_4_free_vod();
                $this->wpstream_onboarding_step_4_ppv_vod();
                $this->onboarding_wizard_footer();
        }


        /*
        *
        * On Boarding Step 1 - the login/register
        *
        */


        public function wpstream_onboarding_step1(){

            $wpstream_options_array =array(
                2   =>  array(
                            'label' =>  'WpStream.net Username or Email',
                            'name'  =>  'api_username',
                            'type'  =>  'text',
                        ),
                3   =>  array(
                            'label' =>  'WpStream.net Password',
                            'name'  =>  'api_password',
                            'type'  =>  'password',
                        ),

            );
            $token          =   $this->main->wpstream_live_connection->wpstream_get_token();

            ?>
            <div class="wpstream_step_wrapper wpstream_step_1" id="wpstream_step_1">           
                <div class="wpstream_has_credential">
                    <h1><?php esc_html_e('WpStream Account','wpstream');?></h1>

                    <div class="wpstream_on_board_login_wrapper_explanations">
                        <?php esc_html_e('A WpStream account is required to make use of the plugin.','wpstream');?>
                    </div>


                        <div class="wpstream_check_account_status">
                            Checking if you are already logged.....
                        </div>

                        <div class="wpstream_onboarding_notification"></div>
               
                        <div class="wpstream_option_wrapper wpstream_on_board_login_wrapper">
                            <h2><?php esc_html_e('Login with your WpStream Account','wpstream');?></h2>
                            <?php 
                            
                                foreach ($wpstream_options_array as $key=>$option){
                                    print '<div class="wpstream_option">';

                                        $options_value =  esc_html( get_option('wpstream_'.$option['name'],'') );
                                        print '<label for="'.$option['name'].'">'.$option['label'].'</label>';
                                        print '<input id="'.$option['name'].'" type="'.$option['type'].'" size="36"  name="'.$option['name'].'" value="'.esc_html($options_value).'" />';

                                    print '</div>';
                                }
                            ?>
                            <input type="submit" name="submit"  class="wpstream_button wpstream_button_action wpstream_onboard_login" value="<?php esc_html_e('Login','wpstream');?>" />
                        </div>




                        <div class="wpstream_on_board_register_wrapper">
                            <h2><?php esc_html_e('Register for  a WpStream Account','wpstream');?></h2>
                            <div class="wpstream_option">
                                <label for="wpstream_register_email"><?php esc_html_e('Your Email','wpstream');?></label>
                                <input id="wpstream_register_email" type="text" size="36"  name="wpstream_register_email" value="<?php echo get_option('admin_email'); ?>" />

                            </div>

                            <div class="wpstream_option">
                                <label for="wpstream_register_password"><?php esc_html_e('Your Password','wpstream');?></label>
                                <input id="wpstream_register_password" type="text" size="36"  name="wpstream_register_password" value="<?php echo $this->randomPassword();?>" />
                            </div>

                        
                            <?php 
                          
                            $curl_response_decoded['capthca']='';
                            $curl_response_decoded['capthca_id']='';


                            if( isset($_GET['page']) &&  $_GET['page']==='wpstream_onboard') {                              
                                $url                    =   'user/getcapthca';
                                $curl_post_fields       =   array();
                                $curl_response          =   $this->main->wpstream_live_connection->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
                                $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
                            
                            }

                            ?>
                            <div class="wpstream_option">
                                <?php print '<div id="wpstream_capthca">'.$curl_response_decoded['capthca'].'</div>';?>
                                <label for="wpstream_register_captcha"><?php esc_html_e('Type the characters above','wpstream');?></label>
                     
                                <input id="wpstream_register_captcha" type="text" size="36"  name="wpstream_register_captcha" />
                                <input id="wpstream_register_captcha_id" type="hidden" size="36"  name="wpstream_register_captcha_id" value="<?php echo esc_html($curl_response_decoded['capthca_id']); ?>" />
                            </div>



                            <input type="submit" name="submit"  class="wpstream_button wpstream_button_action wpstream_onboard_register" value="<?php esc_html_e('register','wpstream');?>" />
                        </div>


                        <div id="wpstream_onboarding_action_login"><?php esc_html_e('I already have a WpStream Account','wpstream');?></div>

                        <div id="wpstream_onboarding_action_register"><?php esc_html_e('Back to Registration','wpstream');?></div>
    
                </div>


                <?php 
                $ajax_nonce = wp_create_nonce( "wpstream_onboarding_nonce");
                print'<input type="hidden" id="wpstream_onboarding_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                
                ?>
            
        
                <div id="wpstream_on_board" class="wpstream_action_next_step" data-nextthing="wpstream_step_2" style="display:none;"  >Move to step 2</div>
            </div>
            <?php
      
            if(trim($token)!==''){
                print '<div id="wpstream_have_token"></div>';
            }
        }
  









        /*
        * 
        * Generate random pass
        *
        */

        public function randomPassword() {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass); //turn the array into a string
        }


        /*
        * 
        *
        *
        */

        public function wpstream_onboarding_step2(){
            ?>
          
            <div class="wpstream_step_wrapper wpstream_step_2" id="wpstream_step_2"> 
                <h1>Welcome to <span class="header_special">WpStream</span>! How would you like to start?</h1>

                <div class="wpstream_accordion_header wpstream_action_next_step" data-nextthing="wpstream_step_3" ><?php esc_html_e('Go LIVE!','wpstream');?></div>
                <div class="wpstram_or">or</div>
                <div class="wpstream_accordion_header wpstream_action_next_step" data-nextthing="wpstream_step_4"  ><?php esc_html_e('Create a Video-On-Demand (VOD)','wpstream');?></div>
        
            </div>


            <?php
        }

        /*
        * 
        *
        *
        */

        public function wpstream_onboarding_step3_live_streaming(){
            ?>
            
            <div class="wpstream_step_wrapper wpstream_step_3 wpstream_onboarding_live" id="wpstream_step_3">
            <h1><?php esc_html_e('Do you want to charge a fee for watching?','wpstream'); ?></h1>

                <div class="wpstream_accordion_header wpstream_action_next_step" data-nextthing="wpstream_step_3a"><?php esc_html_e('No - Free-To-View (FTV)','wpstream');?></div>
                <div class="wpstram_or">or</div>
                <div class="wpstream_accordion_header wpstream_action_next_step" data-nextthing="wpstream_step_3b" ><?php esc_html_e('Yes - Pay-Per-View (PPV)','wpstream');?></div>
            
                <div class="wpstream_initial_onboarding_controls_wrapper">
                    <span class="wpstream_onboard_initial_bubble_prev" data-step="wpstream_step_2"><?php esc_html_e('Prev','wpstream');?></span>
                </div>

            </div>

         
            <?php
        }
        
        
        /*
        * 
        *
        *
        */
        public function wpstream_onboarding_step_3_A_live_streaming_free_view(){
            ?>
            
            <div class="wpstream_step_wrapper wpstream_step_3a wpstream_onboarding_live" id="wpstream_step_3a"> 
            <h1><?php esc_html_e('Let’s create your first FTV live channel','wpstream');?></h1>

                <div id="wpstream_onboard_live_notice" class="wpstream_onboarding_notification"></div>
                <div id="wpstream_onboard_live" class="wpstream_accordion_container">
                    <label>Name your first Free-To-View channel</label>
                    <input type="text" name="channel_name" id="wpstream_onboarding_channel_name" class="wpstream_onboarding_channel_name" value="<?php esc_html_e('My first FTV channel','wpstream');?>"  >
                    <input type="submit" name="submit" id="wpstream_on_board_create_channel" class="wpstream_button wpstream_button_action wpstream_onboard_live_action" value="<?php esc_html_e('Create Channel','wpstream');?>" />
              
                </div>
          
                <div class="wpstream_initial_onboarding_controls_wrapper">
                    <span class="wpstream_onboard_initial_bubble_prev" data-step="wpstream_step_3"><?php esc_html_e('Prev','wpstream');?></span>
                </div>

            </div>

            
            <?php
        }

           
        /*
        * 
        *
        *
        */

        public function wpstream_onboarding_step_3_B_live_streaming_pay_per_view(){
            ?>
            
            <div class="wpstream_step_wrapper wpstream_step_3a wpstream_onboarding_live" id="wpstream_step_3b"> 
            <h1><?php esc_html_e('Make your live stream Pay Per View','wpstream');?></h1>
                <?php
                if ( class_exists( 'WooCommerce' ) ) {
                ?>
                <div id="wpstream_onboard_live_ppv_notice" class="wpstream_onboarding_notification"></div>
                <div id="wpstream_onboard_live_ppv" class="wpstream_accordion_container">
                    <label><?php esc_html_e('Choose a name for your channel','wpstream');?></label>
                    <input type="text" name="channel_name" id="wpstream_onboarding_channel_name_ppv" class="wpstream_onboarding_channel_name" value="<?php esc_html_e('My First PPV Channel','wpstream');?>">
                    <label><?php esc_html_e('Pay-Per-View Price ($)','wpstream');?></label>
                    <input type="text" name="channel_name" id="wpstream_onboarding_event_price_ppv" class="wpstream_onboarding_event_price" value="10">
                    <input type="submit" name="submit" id="wpstream_onboard_live_ppv_action" class="wpstream_button wpstream_button_action wpstream_onboard_live_ppv_action" value="<?php esc_html_e('Create Channel','wpstream');?>" />
        
                </div>
                <?php 
                } else {
                    $this->wpstream_onboarding_woo_warning();
                } ?>   


                <div class="wpstream_initial_onboarding_controls_wrapper">
                    <span class="wpstream_onboard_initial_bubble_prev" data-step="wpstream_step_3"><?php esc_html_e('Prev','wpstream');?></span>
                </div>
            </div>
            <?php
        }





        
        /*
        *  WooCommerce not installed Warning
        *
        *
        */

        public function wpstream_onboarding_woo_warning(){
            print'<div class="wpstream_warning_onboarding">
                '.esc_html__('Pay-Per-View streaming requires WooCommerce. Please install the WooCommerce plugin and try again.','wpstream').'
                </br>
                <div class="wpstream_install_plugin">'. esc_html__('Install WooCommerce','wpstream').'</div>

                <div class="wpstream_onboarding_tryagain">'.esc_html__('Try Again','wpstream').'</div>
            </div>';
        }


        /*
        * 
        *
        *
        */

        public function wpstream_onboarding_step4_vod(){
            ?>
            <div class="wpstream_step_wrapper wpstream_step_4 wpstream_onboarding_vod" id="wpstream_step_4">
            <h1><?php esc_html_e('Do you want to charge a fee for watching?','wpstream');?></h1>

                <div class="wpstream_accordion_header wpstream_action_next_step" data-control="wpstream_onboard_vod_free"  data-nextthing="wpstream_step_4a" ><?php esc_html_e('No - Free-To-View (FTV)','wpstream'); ?></div>
                <div class="wpstram_or">or</div>
                <div class="wpstream_accordion_header wpstream_action_next_step" data-control="wpstream_onboard_vod_ppv"   data-nextthing="wpstream_step_4b" ><?php esc_html_e('Yes - Pay-Per-View (PPV)','wpstream'); ?></div>

               
                <div class="wpstream_initial_onboarding_controls_wrapper">
                    <span class="wpstream_onboard_initial_bubble_prev" data-step="wpstream_step_2"><?php esc_html_e('Prev','wpstream');?></span>
                </div>
      
            </div>
            <?php
        }


        /*
        * 
        *
        *
        */
        public function wpstream_onboarding_step_4_free_vod(){

            $current_screen=get_current_screen();
            if($current_screen->base !=='wpstream_page_wpstream_onboard'){
                return;
            }

            $token          =   $this->main->wpstream_live_connection->wpstream_get_token();
            $video_list     =   array();
            if($token!=''){
                $video_list =  $this->main->wpstream_live_connection->wpstream_get_videos();
            }
             
            ?>
            
            <div class="wpstream_step_wrapper wpstream_step_3a wpstream_onboarding_live" id="wpstream_step_4a"> 
            <h1><?php esc_html_e('Let’s create your first Free-To-View VOD','wpstream');?></h1>

              

                <?php 
                if( count($video_list) !== 0 ) { 
                ?>
                    <div id="wpstream_onboard_vod_free_notice" class="wpstream_onboarding_notification"></div>
                    <div id="wpstream_onboard_vod_free" class="wpstream_accordion_container">
                        <label><?php esc_html_e('Name your FTV Video-On-Demand','wpstream')?></label>
                        <input type="text" name="channel_name" class="wpstream_onboarding_vod_name" id="wpstream_onboarding_vod_name" value="<?php esc_html_e('My First FTV VOD','wpstream');?>" >
                        <?php $this->wpstream_show_vod_dropdown_onboarding($video_list); ?>
                        <input type="submit" name="submit"  class="wpstream_button wpstream_button_action wpstream_onboard_vod_free_action" id="wpstream_onboard_vod_free_action" value="<?php esc_html_e('Create FTV VOD','wpstream');?>" />
                    </div>

                <?php }else{ 
                    $this->wpstream_obboarding_file_warning();
                 } ?>

           
            
                <div class="wpstream_initial_onboarding_controls_wrapper">
                    <span class="wpstream_onboard_initial_bubble_prev" data-step="wpstream_step_4"><?php esc_html_e('Prev','wpstream');?></span>
                </div>

            </div>

            
            <?php
        }

        /*
        * 
        *
        *
        */

        public function wpstream_show_vod_dropdown_onboarding($video_list,$appendix=''){

            print '<label>'.esc_html__('Choose Recording','wpstream').'</label>
            <select name="wpstream_free_vod_file_name" id="wpstream_free_vod_file_name'.$appendix.'">
                <option value="">'.esc_html__('Please select a recording from the list','wpstream').'</option>';
                
                if(is_array($video_list)){
                    foreach ($video_list as $key=>$value){
                        print '<option value="'.$key.'"'; 
                        print '>'.$value.'</option>';
                    }
                }
            print'</select>';

        }



        /*
        * 
        *
        *
        */
        public function wpstream_obboarding_file_warning(){
            print   '<div class="wpstream_warning_onboarding">
                        '.esc_html__('A recording is needed to create a VOD from. There are no recordings under your account. You can create new recordings by recording a live channel or uploading video files directly.','wpstream').'
                        </br>
                        <div class="wpstream_upload_video">'.esc_html__('Upload Video','wpstream').'</div>

                        <div class="wpstream_onboarding_tryagain">'.esc_html__('Try Again','wpstream').'</div>
                    </div>';
        }




        /*
        * 
        *
        *
        */
        public function wpstream_onboarding_step_4_ppv_vod(){
            $current_screen=get_current_screen();
 
            if($current_screen->base !=='wpstream_page_wpstream_onboard'){
                return;
            }
    
            $token          =   $this->main->wpstream_live_connection->wpstream_get_token();
            $video_list     =   array();
            if($token!=''){
                $video_list =  $this->main->wpstream_live_connection->wpstream_get_videos();
            }
            ?>
            
            <div class="wpstream_step_wrapper wpstream_step_3a wpstream_onboarding_live" id="wpstream_step_4b"> 
            <h1><?php esc_html_e('Let\'s create your first Pay-Per-View VOD','wpstream');?></h1>

            <div id="wpstream_onboard_vod_ppv_notice" class="wpstream_onboarding_notification"></div>
                <?php 
                if( count($video_list) !== 0 ) { 
                    if ( class_exists( 'WooCommerce' ) ) {
                    ?>
                        <div id="wpstream_onboard_vod_ppv" class="wpstream_accordion_container">
                            <label><?php esc_html_e('Name your PPV Video-On-Demand','wpstream'); ?></label>
                            <input type="text" name="channel_name" class="wpstream_onboarding_ppv_vod_name" id="wpstream_onboarding_ppv_vod_name" value="<?php esc_html_e('My First PPV VOD','wpstream');?>">

                            <?php $this->wpstream_show_vod_dropdown_onboarding($video_list,'_for_ppv'); ?>
                            
                            <label><?php esc_html_e('Pay-Per-View Price','wpstream');?></label>
                            <input type="text" name="channel_name" class="wpstream_onboarding_vod_price" id="wpstream_onboarding_vod_price" value="10">
                            <input type="submit" name="submit"  class="wpstream_button wpstream_button_action wpstream_onboard_vod_ppv_action" id="wpstream_onboard_vod_ppv_action" value="<?php esc_html_e('Create PPV VOD','wpstream');?>" />
                
                        </div>
                <?php }else{
                        $this->wpstream_onboarding_woo_warning();
                    } 
               
            
                }else{  
                  $this->wpstream_obboarding_file_warning();
                }
                ?>

            
                <div class="wpstream_initial_onboarding_controls_wrapper">
                    <span class="wpstream_onboard_initial_bubble_prev" data-step="wpstream_step_4"><?php esc_html_e('Prev','wpstream');?></span>
                </div>

            </div>

            
            <?php
        }




        /*
        *
        * On boarding Header
        *
        */
        public function onboarding_wizard_header() {
            $thumb= plugin_dir_url( dirname( __FILE__ ) ). 'img/logo_onboarding.svg'; 
            ?>

            <div class="wpstream_on_boarding_wrapper">
                <div class="wpstream_close_onboarding wpstream_close_initial_onboarding"></div>
                <img class="wpstream_onboarding_logo" src="<?php echo esc_url($thumb); ?>" />
                <div class="wpstream_close_onboarding_warning"></div>

            <?php
        }





        /*
        *
        * On Boarding Footer
        *
        */
        public function onboarding_wizard_footer() {
            ?>
                <div class="wpstream_modal_back"></div>
                </div>
                <div class="wpstream_modal_background_onboard"></div>
            <?php
        }



        /*
        *
        * On Boarding create PPV channel
        *
        */


        public function wpstream_on_board_create_channel_ppv(){
            check_ajax_referer( 'wpstream_onboarding_nonce', 'security' );
            $current_user           =   wp_get_current_user();

            if(current_user_can('administrator')){
                $channel_name   =   sanitize_text_field($_POST['channel_name']);
                $channel_price  =   floatval($_POST['channel_price']);
                $my_post = array(
                    'post_title'    => $channel_name,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_type'     =>  'product',
                    'post_author'   => $current_user->ID
                );
                   
                // Insert the post into the database
                $post_id = wp_insert_post( $my_post );

                if(is_wp_error($post_id)){
                    echo json_encode( array('succes'=>false) );        
                }else{
                
                    $product    =   wc_get_product($post_id);
                    $price      =   wc_format_decimal($channel_price);
                    
                    $product = wc_get_product( $post_id );             
                    $product->set_price( $price );
                    $product->set_regular_price( $price ); // To be sure
                    $product->save();
                    update_post_meta ($post_id,'event_passed',0);
                    wp_set_object_terms( $post_id, 'live_stream', 'product_type' );




                    $permalink = get_edit_post_link($post_id);

                    $permalink= add_query_arg( 'onboard', 'yes', $permalink );
                    $permalink= add_query_arg( 'branch', '2', $permalink );
                  
                    
                    echo json_encode( array(
                        'success'=>  true, 
                        'link'  =>  ($permalink) 
                    ));
                }
               
            }
            die();
        }

        /*
        *
        * On Boarding create channel
        *
        */


        public function wpstream_on_board_create_channel(){
            check_ajax_referer( 'wpstream_onboarding_nonce', 'security' );
            $current_user           =   wp_get_current_user();

            if(current_user_can('administrator')){
                $channel_name=sanitize_text_field($_POST['channel_name']);
                $my_post = array(
                    'post_title'    => $channel_name,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_type'     =>  'wpstream_product',
                    'post_author'   => $current_user->ID
                  );
                   
                  // Insert the post into the database
                $post_id = wp_insert_post( $my_post );

                if(is_wp_error($post_id)){
                    echo json_encode( array('succes'=>false) );        
                }else{
                    $permalink = get_edit_post_link($post_id);

                    $permalink= add_query_arg( 'onboard', 'yes', $permalink );
                    $permalink= add_query_arg( 'branch', '1', $permalink );
                  
                    
                    echo json_encode( array(
                        'success'=>  true, 
                        'link'  =>  ($permalink) 
                    ));
                }
               
            }
            die();
        }

        

        /*
        *
        * On Boarding create free vod
        *
        */


        public function wpstream_on_board_create_free_vod(){
            $current_user           =   wp_get_current_user();
            check_ajax_referer( 'wpstream_onboarding_nonce', 'security' );
            if(current_user_can('administrator')){
                $channel_name   =sanitize_text_field($_POST['channel_name']);
                $file_name      =sanitize_text_field($_POST['file_name']);
                $my_post = array(
                    'post_title'    => $channel_name,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_type'     =>  'wpstream_product_vod',
                    'post_author'   => $current_user->ID
                  );
                   
                  // Insert the post into the database
                $post_id = wp_insert_post( $my_post );

                if(is_wp_error($post_id)){
                    echo json_encode( array('succes'=>false) );        
                }else{
                    update_post_meta($post_id, 'wpstream_product_type', 2);
                    update_post_meta($post_id, 'wpstream_free_video', $file_name);


                    $permalink = get_edit_post_link($post_id);

                    $permalink= add_query_arg( 'onboard', 'yes', $permalink );
                    $permalink= add_query_arg( 'branch', '3', $permalink );
                  
                    
                    echo json_encode( array(
                        'success'=>  true, 
                        'link'  =>  ($permalink) 
                    ));
                }
               
            }
            die();
        }


        /*
        *
        * On Boarding create ppv vod
        *
        */


        public function wpstream_on_board_create_ppv_vod(){
            $current_user           =   wp_get_current_user();
            check_ajax_referer( 'wpstream_onboarding_nonce', 'security' );

            if(current_user_can('administrator')){
                $channel_name   =   sanitize_text_field($_POST['channel_name']);
                $vod_price      =   floatval($_POST['vod_price']);
                $file_name      =   sanitize_text_field($_POST['file_name']);

                $my_post = array(
                    'post_title'    => $channel_name,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_type'     =>  'product',
                    'post_author'   => $current_user->ID
                );
                   
                // Insert the post into the database
                $post_id = wp_insert_post( $my_post );

                if(is_wp_error($post_id)){
                    echo json_encode( array('succes'=>false) );        
                }else{
                
                    $product    =   wc_get_product($post_id);
                    $price      =   wc_format_decimal($vod_price);
                    
                    $product = wc_get_product( $post_id );             
                    $product->set_price( $price );
                    $product->set_regular_price( $price ); // To be sure
                    $product->save();
                    update_post_meta ($post_id,'event_passed',0);
                    update_post_meta ($post_id,'_movie_url', $file_name);
                    wp_set_object_terms( $post_id, 'video_on_demand', 'product_type' );




                    $permalink = get_edit_post_link($post_id);

                    $permalink= add_query_arg( 'onboard', 'yes', $permalink );
                    $permalink= add_query_arg( 'branch', '4', $permalink );
                  
                    
                    echo json_encode( array(
                        'success'=>  true, 
                        'link'  =>  ($permalink) ,
                        ' $file_name'=> $file_name,
                    ));
                }
               
            }
            die();
        }


        /*
        *
        * On Boarding login
        *
        */
        public function wpstream_on_board_login(){
            check_ajax_referer( 'wpstream_onboarding_nonce', 'security' );

            if(current_user_can('administrator')){
                $username       = sanitize_text_field($_POST['api_username']);
                $password       = sanitize_text_field($_POST['api_password']);
                update_option('wpstream_api_username',$username);
                update_option('wpstream_api_password',$password);

                $token          =   $this->main->wpstream_live_connection->wpstream_get_token();
                // cleanup any previous echo before sending json
                ob_end_clean();
                // !DO NOT SEND TOKEN TO THE CLIENT!
                if ($token){
                    echo json_encode( array(
                        'success'=>  true, 
                    ));
                }
                else {
                    $text = get_option('wpstream_curl_failed') ? 
                        'Login failed with critical error: ' . get_option('wpstream_curl_failed') : 
                        'Wrong username or password!';
                    echo json_encode( array(
                        'success'=>  false, 
                        'error' => $text,
                    ));
                }
                
            }else{
                echo json_encode( array(
                    'success'=>  false, 
                    'token'  =>  esc_html('You are not an administrator','wpstream') 
                ));
            }
            die();
        }

        
        public function wpstream_register_refresh_capthca(){
                      
            if(current_user_can('administrator')){
            
            }
        }
       
        /*
        *
        * On Boarding login
        *
        */
        public function wpstream_on_board_register(){

            // cleanup any previous echo before sending json
            ob_end_clean();

            check_ajax_referer( 'wpstream_onboarding_nonce', 'security' );
            
            if(current_user_can('administrator')){
                $wpstream_register_email            = sanitize_text_field($_POST['wpstream_register_email']);
                $wpstream_register_password         = sanitize_text_field($_POST['wpstream_register_password']);
                $wpstream_register_captcha          = sanitize_text_field($_POST['wpstream_register_captcha']);
                $wpstream_register_captcha_id       = sanitize_text_field($_POST['wpstream_register_captcha_id']);
                
                $validate = $this->wpstream_validate_onboard_register($wpstream_register_email,$wpstream_register_password);
                if(!$validate['success']){
                    echo json_encode($validate);
                    die();
                }

                $url='user/create';
                $curl_post_fields=array(
                    'email'         =>     $wpstream_register_email,
                    'password'      =>     $wpstream_register_password,
                    'captcha'       =>     $wpstream_register_captcha, 
                    'captcha_id'    =>     $wpstream_register_captcha_id,         
                );
                
            
                
                $curl_response          =   $this->main->wpstream_live_connection->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
                $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);

             
              
                if($curl_response_decoded['success']){

                    if($curl_response_decoded['request']['registred']){
                        // we are registerd

                        update_option('wpstream_api_username',$wpstream_register_email);
                        update_option('wpstream_api_password',$wpstream_register_password);
        
                        $token          =   $this->main->wpstream_live_connection->wpstream_get_token();

                        echo json_encode( array(
                            'success'   =>  true, 
                            'token'     =>  $token ,
                            'message'   =>  esc_html__('Your Account was created. Please stand by...','wpstream'),
                            
                        ));
                        die();
                    }else{

                        echo json_encode( array(
                            'success'=>  false, 
                            'message'=> $curl_response_decoded['request']['message'],
                            'curl'=>$curl_response_decoded,
                            
                        ));
                        die();
                    }




                }else{
                    // cleanup any previous echo before sending json
                    ob_end_clean();
                    echo json_encode( array(
                        'success'=>  false, 
                        'message'=> esc_html__('Register action could not be completed. Please register on wpstream.net','wpstream'),
                        
                    ));
                    die();
                }







                
            }else{
                echo json_encode( array(
                    'success'=>  false, 
                    'message'  =>  esc_html('You are not an administrator','wpstream') 
                ));
                die();
            }

            die();
        }


        /*
        *
        * Validate for register
        *
        */


        public function wpstream_validate_onboard_register($wpstream_register_email,$wpstream_register_password){
                 
            $return= array(
                'success'=>true
            );
            
            if ($wpstream_register_email=='' ){
                $return= array(
                    'success'=>  false, 
                    'message'  =>  esc_html('The email Field is Empty','wpstream') 
                );
                return $return;die();
            }

            if(filter_var($wpstream_register_email,FILTER_VALIDATE_EMAIL) === false) {
                $return= array(
                    'success'=>  false, 
                    'message'  =>  esc_html('The email doesn\'t look right !','wpstream') 
                );
                return $return;die();
            }
            

            $domain = mb_substr(strrchr($wpstream_register_email, "@"), 1);
            if( $domain!='' && !checkdnsrr ($domain) ){
                $return= array(
                    'success'=>  false, 
                    'message'  =>  esc_html('The email doesn\'t look right !','wpstream') 
                );
                return $return;die();
            }



            if(strlen($wpstream_register_password)<5){
                $return= array(
                    'success'=>  false, 
                    'message'  =>  esc_html('The password is too short. Please use at least 5 characters.','wpstream') 
                );
                return $return;die();
            } 

            return $return;die();
        }
}
