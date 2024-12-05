<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wpstream.net
 * @since      3.0.1
 *
 * @package    Wpstream
 * @subpackage Wpstream/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpstream
 * @subpackage Wpstream/public
 * @author     wpstream <office@wpstream.net>
 */
class Wpstream_Public {

    
        
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ,$plugin_main) {
                $this->main = $plugin_main;
        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    3.0.1
     */
    public function enqueue_styles() {

            wp_enqueue_style('wpstream-style',          plugin_dir_url( __FILE__ ) .'/css/wpstream_style.css',array(), WPSTREAM_PLUGIN_VERSION, 'all' );
            wp_enqueue_style('video-js.min',            plugin_dir_url( __FILE__ ).'/css/video-js.min.css', array(), WPSTREAM_PLUGIN_VERSION, 'all');
            wp_enqueue_style('videojs-wpstream-player', plugin_dir_url( __FILE__ ).'/css/videojs-wpstream.css', array(), WPSTREAM_PLUGIN_VERSION, 'all');
            wp_enqueue_style('wpstream-integrations',   plugin_dir_url( __DIR__ ) .'integrations/css/integrations.css',array(), WPSTREAM_PLUGIN_VERSION, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    3.0.1
     */
    public function enqueue_scripts() {

                wp_enqueue_script('jquery');
                wp_enqueue_script('video.min',              'https://vjs.zencdn.net/8.11.5/video.min.js', WPSTREAM_PLUGIN_VERSION, true);
                wp_enqueue_script('youtube.min',            plugin_dir_url( __FILE__ ).'js/youtube.min.js',array('video.min'), WPSTREAM_PLUGIN_VERSION, true);
                //wp_enqueue_script('videojs-vimeo.min',      plugin_dir_url( __FILE__ ).'js/videojs-vimeo.min.js',array('video.min'), WPSTREAM_PLUGIN_VERSION, true);    
                wp_enqueue_script('wpstream-player',        plugin_dir_url( __FILE__ ).'js/wpstream-player.js',array('video.min'), WPSTREAM_PLUGIN_VERSION, true);
                wp_enqueue_script('ovenplayer.min',         'https://cdn.jsdelivr.net/npm/ovenplayer/dist/ovenplayer.js', WPSTREAM_PLUGIN_VERSION, true);
                wp_enqueue_script('hls.min',                'https://cdn.jsdelivr.net/npm/hls.js@latest/dist/hls.min.js', WPSTREAM_PLUGIN_VERSION, true);

    
                wp_localize_script('wpstream-player', 'wpstream_player_vars', 
                    array( 
                        'admin_url'             =>  get_admin_url(),
                        'chat_not_connected'    =>  esc_html__('Inactive Channel - Chat is disabled.','wpstream'),
                        'server_up'             =>  esc_html__('The live stream is paused and may resume shortly.','wpstream'),
                        'wpstream_player_state_stopped_msg'             =>  esc_html__(get_option('wpstream_you_are_not_live','We are not live at this moment'),'wpstream'),
                        'wpstream_player_state_init_msg'             =>  esc_html__('The live stream has not yet started','wpstream'),
                        'wpstream_player_state_startup_msg'             =>  esc_html__('The live stream is starting...','wpstream'),
                        'wpstream_player_state_paused_msg'             =>  esc_html__('The live stream is paused','wpstream'),
                        'wpstream_player_state_ended_msg'             =>  esc_html__('The live stream has ended','wpstream'),
                    )
                );
                
                wp_enqueue_script( 'jquery-ui-autocomplete' );
                wp_enqueue_script( "jquery-effects-core");
                
                wp_register_script( 'sockjs-0.3.min', plugin_dir_url( __FILE__ ) . '/chat_lib/sockjs-0.3.min.js', array('jquery'), true );
                wp_register_script( 'emojione.min.js',plugin_dir_url( __FILE__ ). '/chat_lib/emojione.min.js', array('jquery'), true );
            
                wp_register_script( 'jquery.linkify.min.js', plugin_dir_url( __FILE__ ). '/chat_lib/jquery.linkify.min.js', array('jquery'), true );
                wp_register_script( 'ripples.min.js',plugin_dir_url( __FILE__ ). '/chat_lib/ripples.min.js', array('jquery'), true );
                wp_register_script( 'material.min.js"', plugin_dir_url( __FILE__ ). '/chat_lib/material.min.js', array('jquery'), true );
                wp_register_script( 'chat.js', plugin_dir_url( __FILE__ ). '/chat_lib/chat.js', array('jquery'), true );
              
                wp_localize_script('chat.js', 'chat-js-vars', 
                array( 
                    'we_are_not_live'             =>    esc_html( get_option('wpstream_you_are_not_live','We are not live at this moment')),
                ));

                wp_register_style( 'chat.css',plugin_dir_url( __FILE__ ).'/chat_lib/css/chat.css', array(), '1.0', 'all');
                wp_register_style( 'ripples.css',plugin_dir_url( __FILE__ ).'/chat_lib/css/ripples.css', array(), '1.0', 'all');
                wp_register_style( 'emojione.min.css',plugin_dir_url( __FILE__ ).'/chat_lib/css/emojione.min.css', array(), '1.0', 'all');

                
                
                wp_enqueue_script('wpstream-start-streaming',   plugin_dir_url( __FILE__ ) .'js/start_streaming.js',array(),  WPSTREAM_PLUGIN_VERSION, true); 
                wp_localize_script('wpstream-start-streaming', 'wpstream_start_streaming_vars', 
                    array( 
                        'admin_url'             =>  get_admin_url(),
                        'loading_url'           =>  WPSTREAM_PLUGIN_DIR_URL.'/img/loading.gif',
                        'download_mess'         =>  esc_html__('Click to download!','wpstream'),
                        'uploading'             =>  esc_html__('We are uploading your file.Do not close this window!','wpstream'),
                        'upload_complete2'      =>  esc_html__('Upload Complete! You can upload another file!','wpstream'),
                        'not_accepted'          =>  esc_html__('The file is not an accepted video format','wpstream'),
                        'upload_complete'       =>  esc_html__('Upload Complete!','wpstream'),
                        'upload_failed'         =>  esc_html__('Upload Failed!','wpstream'),
                        'upload_failed2'        =>  esc_html__('Upload Failed! Please Try again!','wpstream'),
                        'no_band'               =>  esc_html__('Not enough streaming data.','wpsteam'),
                        'no_band_no_store'      =>  esc_html__('Not enough streaming data or storage.','wpsteam'),
                        
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
                        'turn_off_confirm'      =>  esc_html__('Are you sure? '.PHP_EOL.' Channels turn OFF automatically after 1 hour of inactivity (no active broadcast). Manual TURN OFF is only useful if you require to change the channel settings.','wpstream'),
                        'turn_off_confirm'      =>  esc_html__('ARE YOU SURE you\'d like to TURN OFF the channel now? '.PHP_EOL.PHP_EOL.'Channels TURN OFF automatically after 1 hour of inactivity (no active broadcast).'.PHP_EOL.PHP_EOL.'Manual TURN OFF is only useful if you require to change the channel settings immediately.'.PHP_EOL.PHP_EOL.'Statistics may be unavailable or incomplete for up to an hour.'.PHP_EOL.PHP_EOL.'If your channel is configured with Auto TURN ON, it will turn back on as soon as there is a broadcast.','wpstream')
                   
                    ));
                

                    $integrations_array=array( 
                        'admin_url'             =>  get_admin_url(),
                    );
                    if (class_exists('BuddyPress')) {
                        $integrations_array['is_buddyboss']='yes';
                    }
                
                    wp_enqueue_script('wpstream-integrations',   plugin_dir_url( __DIR__  ) .'/integrations/js/integrations.js?v='.time(),array(),  WPSTREAM_PLUGIN_VERSION, true); 
                    wp_localize_script('wpstream-integrations', 'wpstream_integrations_vars', $integrations_array );


                wp_enqueue_style( 'wpstream_front_style', plugin_dir_url( __DIR__ ) . 'admin/css/wpstream-admin.css', array(), WPSTREAM_PLUGIN_VERSION, 'all' );
                       
    }
        
        
      
        /**
     * add custom end points for woocomerce
     *
     * @since     3.0.1
     * @return    nothing
        */
        public function wpstream_my_custom_endpoints() {
            add_rewrite_endpoint( 'video-list', EP_ROOT | EP_PAGES );
            add_rewrite_endpoint( 'event-list', EP_ROOT | EP_PAGES );
        }

        /**
     * add custom query vars
     *
     * @since     3.0.1
     * @return    nothing
        */
        public function wpstream_my_custom_query_vars( $vars ) {
            $vars[] = 'video-list';
            $vars[] = 'event-list';
            return $vars;
        }


        /**
     * Hust flush rewrite rules
     *
     * @since     3.0.1
     * 
     */
        public function wpstream_custom_flush_rewrite_rules() {
            flush_rewrite_rules();
        }


        /**
     * Add new sections in woocomerce account
     *
     * @since     3.0.1
    */
    public function wpstream_custom_my_account_menu_items( $items ) {
        if(function_exists('wpstream_is_global_subscription') && wpstream_is_global_subscription()){
            if(function_exists('wpstream_theme_my_custom_endpoints')){
                $items = array(
                    'dashboard'         => __( 'Dashboard', 'wpstream' ),
                    'orders'            => __( 'Orders', 'wpstream' ),
                    'edit-address'      => __( 'Addresses', 'wpstream' ),
                    'edit-account'      => __( 'Edit Account', 'wpstream' ),
                    'watch-later'       => esc_html__( 'Watch Later', 'wpstream-theme' ),
                    'customer-logout'   => __( 'Logout', 'wpstream' ),
                );
            }else{
                $items = array(
                    'dashboard'         => __( 'Dashboard', 'wpstream' ),
                    'orders'            => __( 'Orders', 'wpstream' ),
                    'edit-address'      => __( 'Addresses', 'wpstream' ),
                    'edit-account'      => __( 'Edit Account', 'wpstream' ),
                    'customer-logout'   => __( 'Logout', 'wpstream' ),
                );
            }
        }else{
            if(function_exists('wpstream_theme_my_custom_endpoints')){
                $items = array(
                    'dashboard'         => __( 'Dashboard', 'wpstream' ),
                    'orders'            => __( 'Orders', 'wpstream' ),
                    'edit-address'      => __( 'Addresses', 'wpstream' ),
                    'edit-account'      => __( 'Edit Account', 'wpstream' ), 
                    'event-list'        => __( 'My Live Streams', 'wpstream' ),
                    'video-list'        => __( 'My Videos', 'wpstream' ),
                    'start-streaming'   => esc_html__( 'Start Streaming', 'wpstream-theme' ),
        'watch-later'       => esc_html__( 'Watch Later', 'wpstream-theme' ),
                    'customer-logout'   => __( 'Logout', 'wpstream' ),
                );
            }else{
                 $items = array(
                    'dashboard'         => __( 'Dashboard2', 'wpstream' ),
                    'orders'            => __( 'Orders', 'wpstream' ),
                    'edit-address'      => __( 'Addresses', 'wpstream' ),
                    'edit-account'      => __( 'Edit Account', 'wpstream' ), 
                    'event-list'        => __( 'My Live Streams', 'wpstream' ),
                    'video-list'        => __( 'My Videos', 'wpstream' ),
                    'customer-logout'   => __( 'Logout', 'wpstream' ),
                );
                
            }
            
         
        }
        return $items;
}


    
    
        /**
     * Add new endpoint
     *
     * @since     3.0.1
    */
        public function wpstream_custom_endpoint_content_event_list() {
            include plugin_dir_path( __DIR__ ).'woocommerce/myaccount/event_list.php';
        }


        /**
     * Add new endpoint
     *
     * @since     3.0.1
    */
        public function wpstream_custom_endpoint_video_list() {
            include plugin_dir_path( __DIR__ ).'woocommerce/myaccount/video_list.php';
        }

        
        
        
     
        
        /**
     * register shortcodes
     *
     * @since     3.0.1
         * 
    */
        public function wpstream_shortcodes(){
            add_shortcode('wpstream_player',        array($this,'wpstream_insert_player_inpage_local') );
           // add_shortcode('wpstream_list_products', array($this,'wpstream_list_products_function') );
            add_shortcode('wpstream_chat',          array($this,'wpstream_chat_function') );
            add_shortcode('wpstream_player_low_latency', array($this,'wpstream_insert_player_inpage_low_latency') );
            add_shortcode('wpstream_go_live',                array($this,'wpstream_start_streaming_shortocde') );
           
            
            add_shortcode('wpstream_list_media_channels', array($this,'wpstream_media_list_bakery_bypass') );
            add_shortcode('wpstream_list_media_vod', array($this,'wpstream_media_list_bakery_vod_bypass') );
        } 
        
        
        /**
     * register shortcodes
     *
     * @since     3.0.1
         * 
    */  
            
        public function wpstream_bakery_shortcodes(){   
            // register shortcodes for visual composer  
            if( function_exists('vc_map') ):
          
            
                vc_map(
                    array(
                       "name" => esc_html__( "WpStream Start Streaming Button","wpestate"),
                       "base" => "wpstream_go_live",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( 'Insert WpStream Start Streaming Button','wpstream'),
                       "params" => array(
                            array(
                                "type" => "textfield",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Product/Free Product Id","wpestate"),
                                "param_name" => "id",
                                "value" => "",
                                "description" => esc_html__( "If you leave this option blank we will stream on the first free/paid channel for this user","wpestate")
                            ),
                          

                       )
                    )
                );
            
            
            
                vc_map(
                    array(
                       "name" => esc_html__( "WpStream Chat - Beta Version","wpestate"),
                       "base" => "wpstream_chat",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( 'Insert WpStream Chat','wpstream'),
                       "params" => array(
                            array(
                                "type" => "textfield",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Live Stream Id","wpestate"),
                                "param_name" => "id",
                                "value" => "0",
                                "description" => esc_html__( "Add here the live stream id","wpestate")
                            ),

                       )
                    )
                );
            
            
       
                vc_map(
                    array(
                       "name" => esc_html__( "WpStream Player","wpestate"),
                       "base" => "wpstream_player",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( 'Insert WpStream Player','wpstream'),
                       "params" => array(
                            array(
                                "type" => "textfield",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Product/Free Product Id","wpestate"),
                                "param_name" => "id",
                                "value" => "0",
                                "description" => esc_html__( "Add here the live stream id or the video id","wpestate")
                            ),  
                           array(
                                "type" => "textfield",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "User Id","wpestate"),
                                "param_name" => "user_id",
                                "value" => "",
                                "description" => esc_html__( "We will use the first channel of this user id(product id will be ignored.).","wpestate")
                            ),

                       )
                    )
                );

                
                vc_map(
                    array(
                       "name" => esc_html__( "WpStream Player - Low Latency - Private Beta / Requires Approval","wpestate"),
                       "base" => "wpstream_player_low_latency",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( 'Insert WpStream Player','wpstream'),
                       "params" => array(
                            array(
                                "type" => "textfield",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Product/Free Product Id","wpestate"),
                                "param_name" => "id",
                                "value" => "0",
                                "description" => esc_html__( "Add here the live stream id or the video id","wpestate")
                            ),
                             array(
                                "type" => "textfield",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "User Id","wpestate"),
                                "param_name" => "user_id",
                                "value" => "",
                                "description" => esc_html__( "We will use the first channel of this user id(product id will be ignored.).","wpestate")
                            ),

                       )
                    )
                );


                $product_type=array(
                    '0' =>  __('Both','wpstream'),
                    '1' =>  __('Live Event','wpstream'),
                    '2' =>  __('Video on demand','wpstream')
                );

                vc_map(
                    array(
                       "name" => esc_html__( "WpStream Products List","wpestate"),
                       "base" => "wpstream_list_products",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( ' List wpstream products','wpstream'),
                       "params" => array(

                            array(
                                "type" => "dropdown",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Media type","wpestate"),
                                "param_name" => "product_type",
                                "value" => $product_type,
                                "description" => esc_html__( "What type of media(free/paid) ","wpestate")
                            ),

                            array(
                                 "type" => "textfield",
                                 "holder" => "div",
                                 "class" => "",
                                 "heading" => esc_html__( "Media number","wpestate"),
                                 "param_name" => "media_number",
                                 "value" => "",
                                 "description" => esc_html__( "No of media ","wpestate")
                             ),



                       )
                    )
                );
                
                
                $free_paid_type=array(
                 
                   0 =>  esc_html__('Free','wpstream'),
                   1 =>  esc_html__('Paid','wpstream')
                );
                
                $live_settings=array(
                
                    'no'=>esc_html__('no','wpstream'),
                    'yes'=>esc_html__('yes','wpstream'),
                );
            
                $order_by_id=array(
                   
                    0=>esc_html('By date - ASC','wpstream'),
                    1=>esc_html('By date - DESC','wpstream'),
                    2=>esc_html('By title - ASC','wpstream'),
                    3=>esc_html('By title - DESC','wpstream'),
                );
                 
                vc_map(
                    array(
                       "name" => esc_html__( "WpStream Channel List","wpestate"),
                       "base" => "wpstream_list_media_channels",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( ' List wpstream channels','wpstream'),
                       "params" => array(

                      
                           
                            array(
                                "type" => "dropdown",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Show Free or Paid Media ?","wpestate"),
                                "param_name" => "product_type_free_paid",
                                "value" => $free_paid_type,
                                "description" => esc_html__( "What type of media(free/paid) ","wpestate")
                            ),

                            array(
                                "type" => "dropdown",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Only show active channels","wpestate"),
                                "param_name" => "product_show_live",
                                "value" => $live_settings,
                                "description" => esc_html__( "Only show channels that are live streaming right now.","wpestate")
                            ),
                            array(
                                 "type" => "textfield",
                                 "holder" => "div",
                                 "class" => "",
                                 "heading" => esc_html__( "Number of Items per Page","wpestate"),
                                 "param_name" => "media_number",
                                 "value" => "3",
                                 "description" => esc_html__( "How many items will be displayed per page","wpestate")
                             ),

                            array(
                                 "type" => "textfield",
                                 "holder" => "div",
                                 "class" => "",
                                 "heading" => esc_html__( "Link Label for free items","wpestate"),
                                 "param_name" => "free_label",
                                 "value" => esc_html__('Watch now!','wpstream'),
                                 "description" => esc_html__( "Link Label for free items'","wpestate")
                             ),

                            array(
                                "type" => "dropdown",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Order by","wpestate"),
                                "param_name" => "order_by",
                                "value" => $order_by_id,
                                "description" => esc_html__( "Order type","wpestate")
                            ),


                       )
                    )
                );
                     
                     
                   vc_map(
                    array(
                       "name" => esc_html__( "WpStream VOD List","wpestate"),
                       "base" => "wpstream_list_media_vod",
                       "class" => "",
                       "category" => esc_html__( 'WpStream','wpstream'),
                       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
                       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
                       'weight'=>100,
                       'icon'   =>'',
                       'description'=>esc_html__( ' List wpstream video on demand','wpstream'),
                       "params" => array(

                      
                           
                            array(
                                "type" => "dropdown",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Show Free or Paid Media ?","wpestate"),
                                "param_name" => "product_type_free_paid",
                                "value" => $free_paid_type,
                                "description" => esc_html__( "What type of media(free/paid) ","wpestate")
                            ),

                          
                            array(
                                 "type" => "textfield",
                                 "holder" => "div",
                                 "class" => "",
                                 "heading" => esc_html__( "Number of Items per Page","wpestate"),
                                 "param_name" => "media_number",
                                 "value" => "3",
                                 "description" => esc_html__( "How many items will be displayed per page","wpestate")
                             ),

                            array(
                                 "type" => "textfield",
                                 "holder" => "div",
                                 "class" => "",
                                 "heading" => esc_html__( "Link Label for free items","wpestate"),
                                 "param_name" => "free_label",
                                 "value" => esc_html__('Watch now!','wpstream'),
                                 "description" => esc_html__( "Link Label for free items'","wpestate")
                             ),

                            array(
                                "type" => "dropdown",
                                "holder" => "div",
                                "class" => "",
                                "heading" => esc_html__( "Order by","wpestate"),
                                "param_name" => "order_by",
                                "value" => $order_by_id,
                                "description" => esc_html__( "Order type","wpestate")
                            ),


                       )
                    )
                );   

                
            endif;
            
            
            // add shorcotes to editor interface
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }

            if (get_user_option('rich_editing') == 'true') {
                add_filter('mce_external_plugins', array( $this,'wpstream_add_plugin') );
                add_filter('mce_buttons_2', array($this,'wpstream_register_button') );    
            }
        }
        
        /**
     * list channels - shortcode function
     *
     * @since     3.0.1
         * 
    */
        
        public function wpstream_media_list_bakery_bypass($attributes){
           
          
             
             $attributes =   shortcode_atts( 
                array(
                    'media_number'                       => 3,
                    'product_type_free_paid'             => '0',
                    'product_show_live'                  => 'no',
                    'free_label'                         =>  '',
                    'order_by'                           =>  '0'
                ), $attributes) ;
              
            $attributes['product_type']=1;
            
            if( $attributes['product_type_free_paid']=='Paid'){
                $attributes['product_type_free_paid']=1;
            }
            if( $attributes['product_type_free_paid']=='Free'){
                $attributes['product_type_free_paid']=0;
            }
            
            if($attributes['order_by']==0){
                $order_by_id=array(
                   
                    0=>esc_html('By date - ASC','wpstream'),
                    1=>esc_html('By date - DESC','wpstream'),
                    2=>esc_html('By title - ASC','wpstream'),
                    3=>esc_html('By title - DESC','wpstream'),
                );
            
                $attributes['order_by']= array_search($attributes['order_by'], $order_by_id);
            }
            global $wpstream_plugin;
            return  $wpstream_plugin->wpstream_media_list_elementor_function(   $attributes );
    
        }
        
        /**
     * list vod - shortcode function
     *
     * @since     3.0.1
         * 
    */
        
        public function wpstream_media_list_bakery_vod_bypass($attributes){
           
          
             
             $attributes =   shortcode_atts( 
                array(
                    'media_number'                       => 3,
                    'product_type_free_paid'             => '0',
                    'product_show_live'                  => 'no',
                    'free_label'                         =>  '',
                    'order_by'                           =>  '0'
                ), $attributes) ;
              
            $attributes['product_type']=2;
            
            if( $attributes['product_type_free_paid']=='Paid'){
                $attributes['product_type_free_paid']=1;
            }
            if( $attributes['product_type_free_paid']=='Free'){
                $attributes['product_type_free_paid']=0;
            }
            
            if($attributes['order_by']==0){
                $order_by_id=array(
                   
                    0=>esc_html('By date - ASC','wpstream'),
                    1=>esc_html('By date - DESC','wpstream'),
                    2=>esc_html('By title - ASC','wpstream'),
                    3=>esc_html('By title - DESC','wpstream'),
                );
            
                $attributes['order_by']= array_search($attributes['order_by'], $order_by_id);
            }
            global $wpstream_plugin;
            return  $wpstream_plugin->wpstream_media_list_elementor_function(   $attributes );
    
        }
    
        /**
     * shortocode player
     *
     * @since     3.0.1
         * 
    */
        public function wpstream_chat_function($attributes, $content = null){
            $product_id     =   '';
            $return_string  =   '';
            $attributes =   shortcode_atts( 
                array(
                    'id'                       => 0,
                ), $attributes) ;


            if ( isset($attributes['id']) ){
                $product_id=$attributes['id'];
            }
            
            
            $return_string.= '<div class="wpstream_plugin_chat_wrapper">';
            ob_start();
                $this->main->wpstream_player->wpstream_chat_wrapper($product_id);
                $return_string.= ob_get_contents();
            ob_end_clean(); 
            $return_string.='</div>'; 
            $this->main->wpstream_player->wpstream_connect_to_chat($product_id);
            
            return $return_string;
        }
           
        
        
        
        /**
     * shortocode player
     *
     * @since     3.0.1
         * 
    */
        
        
        public function wpstream_insert_player_inpage_local($attributes, $content = null){
                $product_id     =   '';
                $return_string  =   '';
                $attributes =   shortcode_atts( 
                    array(
                        'id'                       => 0,
                        'user_id'                  => 0,
                    ), $attributes) ;


                if ( isset($attributes['id']) ){
                    $product_id = intval( $attributes['id'] );
                }
                
                if ( isset($attributes['user_id']) ){
                    $user_id = intval( $attributes['user_id'] );
                }
  
                if(intval($product_id)==0 && $user_id!=0 ){
                    $product_id= $this->main->wpstream_player_retrive_first_id($user_id);
                }
                
                ob_start();
                $this->main->wpstream_player->wpstream_video_player_shortcode($product_id);
                $return_string= ob_get_contents();
                ob_end_clean(); 

                return $return_string;
        }

          
        /**
     * shortocode function for start streaming
     *
     * @since     3.7
         * 
    */
        
        
        public function wpstream_start_streaming_shortocde($attributes, $content = null){
                $product_id     =   '';
                $return_string  =   '';
                
                $attributes =   shortcode_atts( 
                    array(
                        'id'                       => 0,
                    ), $attributes) ;


                if ( isset($attributes['id']) ){
                    $product_id=intval($attributes['id']);
                }
                

                ob_start();
                    global $wpstream_plugin;
                    $wpstream_plugin->wpstream_live_stream_unit_wrapper(   $product_id,'front' );
                    $return_string= ob_get_contents();
                ob_end_clean(); 

                return $return_string;
        }

        
        
        /**
     * shortocode player low latency
     *
     * @since     3.0.1
         * 
    */
        
        
        public function wpstream_insert_player_inpage_low_latency($attributes, $content = null){
                $product_id     =   '';
                $return_string  =   '';
                $attributes =   shortcode_atts( 
                    array(
                        'id'                       => 0,
                         'user_id'                  => 0,
                    ), $attributes) ;


                if ( isset($attributes['id']) ){
                    $product_id=$attributes['id'];
                }
                
                   
                if ( isset($attributes['user_id']) ){
                    $user_id = intval( $attributes['user_id'] );
                }

                
                if(intval($product_id)==0 && $user_id!=0){
                    $product_id= $this->main->wpstream_player_retrive_first_id($user_id);
                }
                
                ob_start();
                $this->main->wpstream_player->wpstream_video_player_shortcode_low_latency($product_id);
                $return_string= ob_get_contents();
                ob_end_clean(); 

                return $return_string;
        }

        
        
        /**
     * list products - shortcode function
     *
     * @since     3.0.1
         * 
    */
        
        public function wpstream_list_products_function($atts, $content=null){

                $media_number     = "";  
                $product_type     = ""; 
                $attributes = shortcode_atts(
                        array(
                                'media_number' =>   '4',
                                'product_type' =>   __('Free Live Channel','wpstream'),

                        ), $atts);

                if ( isset($attributes['media_number']) ){
                    $media_number=$attributes['media_number'];
                }

                if ( isset($attributes['product_type']) ){
                    $product_type=$attributes['product_type'];
                }

                if($product_type== __('Free Live Channel','wpstream') ){
                    $product_type=1;
                }else{
                    $product_type=2;
                }

                $return_string=""; 



                $args = array(
                    'post_type'      => 'wpstream_product',
                    'post_status'    => 'publish',
                    'meta_query'     =>array(
                                        array(
                                        'key'      => 'wpstream_product_type',
                                        'value'    => $product_type,
                                        'compare'  => '=',
                                        ),
                        ),
                    'posts_per_page' =>$media_number,
                    'page'          => 1
                );

   
                $media_list= new WP_Query($args);

                if($product_type==1){
                    $see_product= __('See Free Live Chanel','wpstream');
                }else{
                    $see_product =__('See Free Video','wpstream');
                }



                while($media_list->have_posts()):$media_list->the_post();
                    $return_string.='<div class="wpstream_product_unit">'
                    .'<div class="product_image" style="background-image:url('.wp_get_attachment_thumb_url(get_post_thumbnail_id()).')"></div>'
                    .'<a href="'.get_permalink().'" class="product_title" >'.get_the_title().'</a>'
                    .'<a href="'.get_permalink().'"class="see_product">'.$see_product.'</a>'
                    .'</div>';
                endwhile;

                wp_reset_postdata();
                wp_reset_query();


                return   '<div class="shortcode_list_wrapper">'.$return_string.'</div>';

        }

        
        
        /**
     * register shortcodes - add buttons in js
     *
     * @since     3.0.1
         * 
    */
        
        public function wpstream_add_plugin($plugin_array) {   
            $plugin_array['wpstream_player']                = plugin_dir_url( __FILE__ ). '/js/shortcodes.js';
            $plugin_array['wpstream_list_products']         = plugin_dir_url( __FILE__ ). '/js/shortcodes.js';
            $plugin_array['wpstream_list_products_channels']= plugin_dir_url( __FILE__ ). '/js/shortcodes.js';
            return $plugin_array;
        }
         
        /**
     * register shortcodes - add buttons
     *
     * @since     3.0.1
         * 
    */
        public function wpstream_register_button($buttons) {
            array_push($buttons, "|", "wpstream_player");
            array_push($buttons, "|", "wpstream_list_products");    
            array_push($buttons, "|", "wpstream_list_products_channels"); 
            return $buttons;
        }


        
        /**
     * wpstream cors
     *
     * @since     3.0.1
         * 
    */
        
        public function wpstream_cors_check_and_response(){
            if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
                header('Access-Control-Allow-Methods: POST, GET');
                header('Access-Control-Allow-Headers: Authorization');
                header('Access-Control-Max-Age: 1');  //1728000
                header("Content-Length: 0");
                header("Content-Type: text/plain charset=UTF-8");
                exit(0);
            }
        }
        
        /**
     * set user cookie
     *
     * @since     3.0.1
         * 
    */

        public function wpstream_set_cookies(){
            $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;

            if(isset($local_event_options['ses_encrypt']) && intval($local_event_options['ses_encrypt'])==1 )    {  
            

                if (session_status() == PHP_SESSION_NONE) {
                   session_start();
                }

                if( !isset( $_REQUEST[ 'wpstream_livedrm' ]) && !isset( $_REQUEST[ 'wpstream_voddrm' ])  && !isset( $_REQUEST[ 'keys2' ]) ) {

                    if( !isset($_SESSION['wpstream_id']) ){

                        $_SESSION['wpstream_id']= uniqid();
                    }
                }
            }
        }
        
        /**
         * editd 4.0
         * 
     * get key for live stream
     *
     * @since     3.0.1
         * 
    */
        
        
        public function wpstream_live_streaming_key(){
            
            $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;

            if( isset( $_REQUEST[ 'wpstream_livedrm' ]) && $_REQUEST[ 'wpstream_livedrm' ]!=''  ) {
               
                if(isset($local_event_options['ses_encrypt']) && intval($local_event_options['ses_encrypt'])==1 )    {  
                    
                    if( !isset( $_SESSION['wpstream_id'] ) ){
                        session_write_close ();die('no session');
               
                    }
                }
              
                
                $streamname_received    =   esc_html($_REQUEST[ 'wpstream_livedrm' ]);
                $stream_key_array       =   explode('-', $streamname_received);
             
                $streamname             =   $stream_key_array[0];
                $current_user           =   wp_get_current_user();        
              
                $event_list_free_posts =    get_transient(  'free_event_streamName_'.$streamname ) ;
              
                wp_reset_postdata();
                wp_reset_query();
            
              
                if ( false === $event_list_free_posts ) {   
              
                    $args_free = array(
                        'posts_per_page'    => -1,
                        'cache_results'             =>  false,
                        'update_post_meta_cache'    =>  false,
                        'update_post_term_cache'    =>  false,
                        'post_type'         => 'wpstream_product',
                        'post_status'       => 'publish',
                        'meta_query'        =>      array(
                                                        array(
                                                        'key'     => 'stream_name',
                                                        'value'   => $streamname,
                                                        'compare' => '=',
                                                        )
                                                    ),
                        'fields'=>'ids',
                    );
                
                    $event_list_free        =   new WP_Query($args_free);
                    $event_list_free_posts  =   $event_list_free->posts;
                    set_transient(  'free_event_streamName_'.$streamname, $event_list_free->posts ,60);
                }

                if ( !empty($event_list_free_posts )  ){
                    ////////////////////////////////////////////////////////////   
                    // when we have a free event
                    ////////////////////////////////////////////////////////////
                    $the_id                     =   $event_list_free_posts[0];
                    $show_id                    =   $the_id;        
                    $get_key                    =   $this->wpstream_get_encryption_key_remonting($show_id,$streamname_received);

                    $seconds_to_cache = 301;
                    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
                    header("Expires: $ts");
                    header("Pragma: cache");
                    header("Cache-Control: max-age=$seconds_to_cache");


                    print $get_key;
                    die();
                        
                        
                }else{
                    ////////////////////////////////////////////////////////////
                    //  this is for paid products
                    ////////////////////////////////////////////////////////////
                    
                    if ( is_user_logged_in() && intval($current_user->ID)!=0 ) {  

                            $event_list_paid_posts  =    get_transient(  'paid_event_streamName_'.$streamname ) ;
                         
                        
                        
                            if ( false === $event_list_paid_posts ) {  
                                $args = array(
                                    'posts_per_page'    => -1,
                                    'post_type'         => 'product',
                                    'post_status'       => 'publish',
                                    'meta_query' => array(
                                        array(
                                                'key'     => 'stream_name',
                                                'value'   => $streamname,
                                                'compare' => '=',
                                        ),
                                    ),
                                    'tax_query'         => array(
                                                'relation'  => 'AND',
                                                array(
                                                    'taxonomy'  =>  'product_type',
                                                    'field'     =>  'slug',
                                                    'terms'     =>  array('live_stream','subscription')
                                                )
                                            ),
                                     'fields'=>'ids',
                                );


                                $event_list             = new WP_Query($args);
                                $event_list_paid_posts  = $event_list->posts;
                              
                                set_transient(  'paid_event_streamName_'.$streamname, $event_list->posts ,60);
                            }
                         
                            if ( !empty($event_list_paid_posts )  ){
                             
                                $the_id     =    $event_list_paid_posts[0];
                                $show_id    =   $the_id;


                                $is_valid_subscription=0;
                                if(class_exists ('WC_Subscription')){
                                    $is_valid_subscription = wcs_user_has_subscription( $current_user->ID, $show_id ,'active');
                                }


                                if(function_exists('wpstream_check_global_subscription_model')){
                                    if( wpstream_check_global_subscription_model() ){
                                        $is_valid_subscription=1;// this is global subscription
                                    }
                                }

                                
                                if( $this->main->wpstream_player->wpstream_in_plugin_check_global_subscription_model($show_id) ){
                                    $is_valid_subscription=1;// this is global subscription
                                }


                                if( wc_customer_bought_product( $current_user->email, $current_user->ID, $show_id) || $is_valid_subscription==1 ){     
                                    $get_key = $this->wpstream_get_encryption_key_remonting($show_id,$streamname_received);                            
                                    
                                    $seconds_to_cache = 302;
                                    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
                                    header("Expires: $ts");
                                    header("Pragma: cache");
                                    header("Cache-Control: max-age=$seconds_to_cache");
                                    
                                    print $get_key;
                                    die();

                                }else{
                                    exit('live - no ticket ');
                                }

                            } else{
                                exit('live - no event');
                            }

                        }else{
                            exit('live - user not log or anwser');
                        }
                        
                }
                exit('no free or paid event');
                
            }else{
               
                return;
            }

        }
         
         
         
             
         /**
     * get remote key for live
     *
     * @since     3.0.1
         * 
    */
         
        public function wpstream_get_encryption_key_remonting ($show_id,$streamname_received){
            $get_key = get_transient( $show_id.'_api20_streamName' );
          

            if ( false ===  $get_key  ) {           
                    
              $url = get_post_meta($show_id,'hls_key_retrieval_url',true).'/'.$streamname_received;
                   
                    $get= wp_remote_get( $url );

                    if(is_array($get)){
                        $get_key = $get['body'];
                    }else{
                       $get_key='';
                    }
                    
                    set_transient(  $show_id.'_api20_streamName', $get_key, 30 );
            }
            return $get_key;
        }

         
         
         /**
     * get key for 3rdparty
     *
     * @since     3.0.1
         * 
    */
        
        
        public function wpstream_live_streaming_key_for_3rdparty(){
   
            if( isset( $_REQUEST[ 'thirdkeys' ]) && $_REQUEST[ 'thirdkeys' ]!='' ) {
            
                $thirdkeys         =   esc_html($_REQUEST[ 'thirdkeys' ]);
              
                //live_event_carnat2
                
                $args = array(
                    'post_type'      => array('product','wpstream_product'),
                    'post_status'    => 'publish',
                    'meta_query'     =>array(
                                        array(
                                        'key'      => 'live_event_carnat2',
                                        'value'    => $thirdkeys,
                                        'compare'  => '=',
                                        ),
                        ),
                    
                  
                );

         
                $media_list= new WP_Query($args);
                if($media_list->have_posts()){
                    while($media_list->have_posts()):$media_list->the_post();
                
                        $media_id       =   get_the_ID();
                        $replay_array   =   array(
                           // '', // fb will be here
                            stripslashes( get_post_meta($media_id,'wpstream_youtube_rtmp',true )),
                            stripslashes( get_post_meta($media_id,'wpstream_twich_rtmp',true) ),
                        );
                        
                        $reply_final=array('rtmp_urls'=>$replay_array);
                        header('Content-Type: application/json;charset=utf-8');
                        print json_encode($reply_final,JSON_UNESCAPED_SLASHES);
                        die();
                        
                        
                    endwhile;
                }else{
                    print'{}';
                    die('');
                }
                
            }

         }
        
          
         
    /**
     * get key for vod
     *
     * @since     3.0.1
    * 
    */
    public function wpstream_live_streaming_key_vod(){
        $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;
        $current_user        =   wp_get_current_user();   
        
       
        
        if( isset( $_REQUEST[ 'wpstream_voddrm' ]) && $_REQUEST[ 'wpstream_voddrm' ]!=''  ) {

            if(isset($local_event_options['ses_encrypt']) && intval($local_event_options['ses_encrypt'])==1 )    {  
     
                if( !isset( $_SESSION['wpstream_id'] ) ){
                    unset($_SESSION['wpstream_id']);
                    session_write_close ();session_register_shutdown();
                    die('no session');
                }
            }
        
            $hlsDecryptionKeyIndex  = esc_html($_REQUEST[ 'wpstream_voddrm' ]);
            $vod_list_free_posts    = get_transient(  'vod_decryption_key_index_'.$hlsDecryptionKeyIndex ) ;
           
            if ( false === $vod_list_free_posts ) {   
                $args_free = array(
                    'posts_per_page'    => -1,
                    'cache_results'             =>  false,
                    'update_post_meta_cache'    =>  false,
                    'update_post_term_cache'    =>  false,
                    'post_type'         => 'wpstream_product_vod',
                    'post_status'       => 'publish',
                    'meta_query'        =>      array(
                                                    array(
                                                    'key'     => 'hlsDecryptionKeyIndex',
                                                    'value'   => $hlsDecryptionKeyIndex,
                                                    'compare' => '=',
                                                    )
                                                ),
                    'fields'=>'ids',
                );
            
                $event_list_free = new WP_Query($args_free);
              
                $vod_list_free_posts= $event_list_free->posts;
                set_transient(  'vod_decryption_key_index_'.$hlsDecryptionKeyIndex, $event_list_free->posts ,60);
            }// end check transient



            if ( !empty($vod_list_free_posts )  ){
                ////////////////////////////////////////////////////////////   
                // when we have a free event
                ////////////////////////////////////////////////////////////
                $the_id                     =   $vod_list_free_posts[0];
                $get_key                    =   get_post_meta($the_id,'hlsDecryptionKey',true);

                $seconds_to_cache = 301;
                $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
                header("Expires: $ts");
                header("Pragma: cache");
                header("Cache-Control: max-age=$seconds_to_cache");


                print base64_decode( $get_key );
                die();
                    
                    
            }else{
                ////////////////////////////////////////////////////////////
                //  this is for paid products
                ////////////////////////////////////////////////////////////
                if ( is_user_logged_in() && intval($current_user->ID)!=0 ) {  
                    $hlsDecryptionKeyIndex  = esc_html($_REQUEST[ 'wpstream_voddrm' ]);
                    $event_list_paid_posts  =    get_transient(  'paid_vod_key_index_'.$hlsDecryptionKeyIndex ) ;
                 
                
                    if ( false === $event_list_paid_posts ) {
                        print '  transient paid expired ';  
                        $args = array(
                            'posts_per_page'    => -1,
                            'post_type'         => 'product',
                            'post_status'       => 'publish',
                            'meta_query' => array(
                                array(
                                        'key'     => 'hlsDecryptionKeyIndex',
                                        'value'   => $hlsDecryptionKeyIndex,
                                        'compare' => '=',
                                ),
                            ),
                            'tax_query'         => array(
                                        'relation'  => 'AND',
                                        array(
                                            'taxonomy'  =>  'product_type',
                                            'field'     =>  'slug',
                                            'terms'     =>  array('video_on_demand','subscription')
                                        )
                                    ),
                             'fields'=>'ids',
                        );


                        $event_list = new WP_Query($args);
                        $vod_list_paid_posts = $event_list->posts;
                      
                        set_transient(  'paid_vod_key_index_'.$hlsDecryptionKeyIndex, $event_list->posts ,60);
                    }

                 
                    if ( !empty($vod_list_paid_posts )  ){
                        
                        $the_id     =    $vod_list_paid_posts[0];
                        $show_id    =   $the_id;


                        $is_valid_subscription=0;
                        if(class_exists ('WC_Subscription')){
                            $is_valid_subscription = wcs_user_has_subscription( $current_user->ID, $show_id ,'active');
                        }


                        if(function_exists('wpstream_check_global_subscription_model')){
                            if( wpstream_check_global_subscription_model() ){
                                $is_valid_subscription=1;// this is global subscription
                            }
                        }

                        
                        if( $this->main->wpstream_player->wpstream_in_plugin_check_global_subscription_model($show_id) ){
                            $is_valid_subscription=1;// this is global subscription
                        }


                        if( wc_customer_bought_product( $current_user->email, $current_user->ID, $show_id) || $is_valid_subscription==1 ){     
                                              
                            $get_key                    =   get_post_meta($show_id,'hlsDecryptionKey',true);
                        
                            $seconds_to_cache = 302;
                            $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
                            header("Expires: $ts");
                            header("Pragma: cache");
                            header("Cache-Control: max-age=$seconds_to_cache");
                            
                            print base64_decode( $get_key );
                            die();

                        }else{
                            exit('vod - no ticket ');
                        }

                    } else{
                        exit('vod - no item');
                    }

                }else{
                    exit('vod - user not log or no answer');
                }


            }



        }


    }
     
       
    


         
    public function wpstream_get_vod_key($filename){   
            return;
//           global $wpstream_plugin;
//            $vod_key = get_transient("vod_key".$filename);
//            if(false===$vod_key){
//                $token  = $wpstream_plugin->wpstream_live_connection->wpstream_get_token();
//                $domain = parse_url ( get_site_url() );
//
//                $values_array=array(
//                    "filename"           =>  $filename,
//                );
//                $url            =   WPSTREAM_CLUBLINKSSL."://www.".WPSTREAM_CLUBLINK."/wp-json/rcapi/v1/uservodkey/get/?access_token=".$token;
//
//
//                $arguments = array(
//                    'method'        => 'GET',
//                    'timeout'       => 45,
//                    'redirection'   => 5,
//                    'httpversion'   => '1.0',
//                    'blocking'      => true,
//                    'headers'       => array(),
//                    'body'          => $values_array,
//                    'cookies'       => array()
//                );
//                $response       = wp_remote_post($url,$arguments);
//                $received_data  = json_decode( wp_remote_retrieve_body($response) ,true);
//
//
//                if( isset($response['response']['code']) && $response['response']['code']=='200'){
//                    set_transient("vod_key".$filename,$received_data,120);
//                    return ($received_data);
//                }else{     
//                    return 'failed connection';
//                }
//            }else{
//                return $vod_key;
//            }

    }
        
        
        
        
        
        
        
        
         
        /**
     * wrapper start around woo
     *
     * @since     3.0.1
         * 
    */
        
        
        public function wpstream_non_image_content_wrapper_start() {
            if ( is_user_logged_in() ) {
                global $product;
                $current_user   =   wp_get_current_user();
                $product        =   wc_get_product();

                if($product){
                    $product_id = $product->get_id();
                    if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ){
                        echo '<div id="wpstream_product_wrap">';
                    }else{
                        echo '<div id="wpstream_product_wrap_no_buy">';
                    }
                }
            }

        }

        /**
     * wrapper end around woo
     *
     * @since     3.0.1
         * 
    */
        

        function wpstream_non_image_content_wrapper_end() { 
           // echo '</div>';
        }
        
      
        /**
     * wpstream thank you extra
     *
     * @since     3.12
         * 
    */
        
        function wpstream_thankyou_extra($var,$order){
            return $this->wpstream_get_ordered_items($order);
        }
        
          
        /**
     * wpstream thank you extra on order
     *
     * @since     3.12
         * 
    */
        function wpstream_email_order_details($order, $sent_to_admin, $plain_text, $email){
            print $this->wpstream_get_ordered_items($order).'</br>';
        }
        
        
             
        /**
     * wpstream compose message for complted order and thank you page
     *
     * @since     3.12
         * 
    */
        
        function wpstream_get_ordered_items($order){
          
            $message    =  esc_html( get_option('wpstream_product_thankyou','Thanks for your purchase. You can access your item at any time by visiting the following page: {item_link}')) ;
            $list       =   '';
            $product_id = 0;
            
            foreach( $order->get_items() as $line_item ) {
                // The WC_Product object
                $product = $line_item->get_product(); // Added
                $list .= '<a href="'.$product->get_permalink().'">'.$product->get_title().'</a>, ';// Changed
                $product_id = $product->get_id();
            }
            $list       = trim($list,', ');
            $message    = str_replace('{item_link}', $list, $message);
            
            $term_list                  =   wp_get_post_terms($product_id, 'product_type');
            $product                    =   wc_get_product($product_id);
        
            $product_type               =   $product->get_type();

            
             if( $term_list[0]->name=='live_stream' || $term_list[0]->name=='video_on_demand' ||  $product_type=='subscription' ){
                return $message;
             }else{
                $message    = esc_html('Thank you. Your order has been received','wpstream');
                     return $message;
             }
            
          
        }
}
