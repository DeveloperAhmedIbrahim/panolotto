<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wpstream.net
 * @since      3.0.1
 *
 * @package    Wpstream
 * @subpackage Wpstream/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      3.0.1
 * @package    Wpstream
 * @subpackage Wpstream/includes
 * @author     wpstream <office@wpstream.net>
 */
class Wpstream {

        /**
        * Store plugin main class to allow public access.
        *
        * @since             3.0.1
        * @var object      The main class.
        */
        public $main;
        public $admin;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    3.0.1
	 * @access   protected
	 * @var      Wpstream_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    3.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    3.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    3.0.1
	 */
        
        public $wpstream_live_connection;
        public $wpstream_player;
        public $xtest;
        public $plugin_admin;
        
	public function __construct() {
                $this->main = $this;
		if ( defined( 'WPSTREAM_PLUGIN_VERSION' ) ) {
                    $this->version = WPSTREAM_PLUGIN_VERSION;
		} else {
                    $this->version = '3.0.1';
		}
		$this->plugin_name = 'wpstream';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
                
            
                $this->wpstream_conection();
                $this->wpstream_player();

	}

        
        
        
        
        public function wpstream_convert_band($megabits){
            $gigabit    =   $megabits   *   0.001;
            $gigabit    =   number_format($gigabit,2);
            return $gigabit;
        }

        
        
        
        
        private function wpstream_conection(){
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstream-live-api-connection.php';
            $this->wpstream_live_connection = new Wpstream_Live_Api_Connection();
        }
        
        
        private function wpstream_player(){
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstream-player.php';
            $this->wpstream_player = new Wpstream_Player($this->main);
        }
        
        
        
        
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpstream_Loader. Orchestrates the hooks of the plugin.
	 * - Wpstream_i18n. Defines internationalization functionality.
	 * - Wpstream_Admin. Defines all hooks for the admin area.
	 * - Wpstream_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    3.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstream-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstream-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpstream-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpstream-public.php';
                
                /**
		 * The class responsible for custom post type
		
                 */
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstream_product.php';
                if(  class_exists( 'WooCommerce' ) ){
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc_product_live_stream.php';
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc_product_video_on_demand.php';
                }

		$this->loader = new Wpstream_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpstream_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    3.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpstream_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    3.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

                $this->admin=                $plugin_admin = new Wpstream_Admin( $this->get_plugin_name(), $this->get_version(), $this->main );
                
                $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin,  'enqueue_styles' );
		        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin,  'enqueue_scripts' );
                $this->loader->add_action( 'admin_menu',            $plugin_admin,  'wpstream_manage_admin_menu',999);
                                  
                $plugin_post_types = new Wpstream_Product();
                $this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type', 999 );
                
                // save and render metaboxed
                $this->loader->add_action( 'add_meta_boxes',    $plugin_admin, 'add_wpstream_product_metaboxes' );
                $this->loader->add_action( 'save_post',     $plugin_admin, 'wpstream_free_product_update_post',1,2 );    
                $this->loader->add_action( 'publish_wpstream_product',     $plugin_admin, 'wpstream_publish_wpstream_product',1,2 );    
                
      
              
                // make product virtual
                $this->loader->add_action( 'save_post',  $plugin_admin, 'wpstream_make_product_virtual',  99999, 2 );
                
                
                
                // show streaming controls on sidebar
                $this->loader->add_action('add_meta_boxes', $plugin_admin, 'wpstream_startstreaming_sidebar_meta');

                // on boarding actions
                $this->loader->add_action('admin_footer',$plugin_admin, 'wpstream_admin_footer_onboarding');
                $this->loader->add_action( 'wp_ajax_wpstream_on_board_create_channel',  $plugin_admin,'wpstream_on_board_create_channel' );
                $this->loader->add_action( 'wp_ajax_wpstream_on_board_create_channel_ppv',  $plugin_admin,'wpstream_on_board_create_channel_ppv' );                
                $this->loader->add_action( 'wp_ajax_wpstream_on_board_create_free_vod',  $plugin_admin,'wpstream_on_board_create_free_vod' );     
                $this->loader->add_action( 'wp_ajax_wpstream_on_board_create_ppv_vod',  $plugin_admin,'wpstream_on_board_create_ppv_vod' );     
      

                $this->loader->add_action( 'wp_ajax_wpstream_on_board_login',  $plugin_admin,'wpstream_on_board_login' );
                $this->loader->add_action( 'wp_ajax_wpstream_on_board_register',  $plugin_admin,'wpstream_on_board_register' );
                

                $this->loader->add_action( 'admin_notices',                             $plugin_admin,'wpstream_admin_notice' );
                $this->loader->add_action( 'wp_ajax_wpstream_update_cache_notice',      $plugin_admin,'wpstream_update_cache_notice' );
                


                 
                // add and save category extra fields
                $this->loader->add_action( 'category_edit_form_fields',  $plugin_post_types,   'wpstream_category_callback_function', 10, 2);
                $this->loader->add_action( 'category_add_form_fields',   $plugin_post_types,   'wpstream_category_callback_add_function', 10, 2 );  
                $this->loader->add_action( 'created_category',           $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);
                $this->loader->add_action( 'edited_category',            $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);

                $this->loader->add_action( 'product_cat_edit_form_fields',  $plugin_post_types,  'wpstream_category_callback_function', 10, 2);
                $this->loader->add_action( 'product_cat_add_form_fields',   $plugin_post_types,  'wpstream_category_callback_add_function', 10, 2 );  
                $this->loader->add_action( 'created_product_cat',           $plugin_post_types,  'wpstream_category_save_extra_fields_callback', 10, 2);
                $this->loader->add_action( 'edited_product_cat',            $plugin_post_types,  'wpstream_category_save_extra_fields_callback', 10, 2);


                $this->loader->add_action( 'wpstream_category_edit_form_fields', $plugin_post_types,   'wpstream_category_callback_function', 10, 2);
                $this->loader->add_action( 'wpstream_category_add_form_fields',  $plugin_post_types,   'wpstream_category_callback_add_function', 10, 2 );  
                $this->loader->add_action( 'created_wpstream_category',          $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);
                $this->loader->add_action( 'edited_wpstream_category',           $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);


                $this->loader->add_action( 'wpstream_actors_edit_form_fields',  $plugin_post_types,   'wpstream_category_callback_function', 10, 2);
                $this->loader->add_action( 'wpstream_actors_add_form_fields',   $plugin_post_types,   'wpstream_category_callback_add_function', 10, 2 );  
                $this->loader->add_action( 'created_wpstream_actors',           $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);
                $this->loader->add_action( 'edited_wpstream_actors',            $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);

                $this->loader->add_action( 'wpstream_movie_rating_edit_form_fields',  $plugin_post_types,   'wpstream_category_callback_function', 10, 2);
                $this->loader->add_action( 'wpstream_movie_rating_add_form_fields',   $plugin_post_types,   'wpstream_category_callback_add_function', 10, 2 );  
                $this->loader->add_action( 'created_wpstream_movie_rating',           $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);
                $this->loader->add_action( 'edited_wpstream_movie_rating',            $plugin_post_types,   'wpstream_category_save_extra_fields_callback', 10, 2);
                
         
          
                       
                if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
              
                    $this->loader->add_filter( 'product_type_selector',         $plugin_admin, 'wpstream_add_products' );
                    $this->loader->add_action( 'admin_footer',                  $plugin_admin, 'wpstream_products_custom_js' );
                    $this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'wpstream_hide_attributes_data_panel',10,1 );
                    $this->loader->add_filter( 'woocommerce_is_purchasable',    $plugin_admin, 'wpstream_hide_buy_now_subscription_mode',10,2);
                    
                    $this->loader->add_filter( 'woocommerce_product_options_general_product_data',$plugin_admin, 'wpstream_add_custom_general_fields', 10,1);
                    $this->loader->add_filter( 'woocommerce_process_product_meta',$plugin_admin, 'wpstream_add_custom_general_fields_save',10,1 );
                    $this->loader->add_action( 'woocommerce_live_stream_add_to_cart', $plugin_admin, 'wpstream_add_to_cart',10,1);
                    $this->loader->add_action( 'woocommerce_video_on_demand_add_to_cart', $plugin_admin, 'wpstream_add_to_cart',10,1);
                    $this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $plugin_admin,'replacing_add_to_cart_button', 10, 2 );
                }
                
                
                
                


	}





	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    3.0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpstream_Public( $this->get_plugin_name(), $this->get_version(), $this->main );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
                
                $this->loader->add_action( 'init', $plugin_public,'wpstream_my_custom_endpoints' );
                $this->loader->add_filter( 'query_vars',$plugin_public, 'wpstream_my_custom_query_vars', 0 );
              
                //live stream action                
                $this->loader->add_action('init',$plugin_public,'wpstream_set_cookies',0);
                $this->loader->add_action('init',$plugin_public,'wpstream_live_streaming_key');
                $this->loader->add_action('init',$plugin_public,'wpstream_live_streaming_key_for_3rdparty');
                $this->loader->add_action('init',$plugin_public,'wpstream_live_streaming_key_vod',10);
                // woo action
                
                $this->loader->add_action( 'woocommerce_before_single_product', $plugin_public,'wpstream_non_image_content_wrapper_start', 20 );
                $this->loader->add_action( 'woocommerce_after_single_product', $plugin_public,'wpstream_non_image_content_wrapper_end', 20 );
                $this->loader->add_action( 'woocommerce_thankyou_order_received_text', $plugin_public,'wpstream_thankyou_extra', 20,2 );
                $this->loader->add_action( 'woocommerce_email_order_details', $plugin_public,'wpstream_email_order_details', 20,4 );
                 
                $this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_public,'wpstream_custom_my_account_menu_items' );
                $this->loader->add_action( 'woocommerce_account_event-list_endpoint', $plugin_public,'wpstream_custom_endpoint_content_event_list' );
                $this->loader->add_action( 'woocommerce_account_video-list_endpoint', $plugin_public,'wpstream_custom_endpoint_video_list' );
                

                $this->loader->add_action( 'after_switch_theme', $plugin_public,'wpstream_custom_flush_rewrite_rules' );
                $this->loader->add_action('init', $plugin_public,'wpstream_shortcodes');
                $this->loader->add_action('vc_before_init', $plugin_public,'wpstream_bakery_shortcodes');
                
   
                $this->loader->add_action('wo_before_api', 'wpstream_cors_check_and_response',10,1);
                
              
                
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    3.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     3.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     3.0.1
	 * @return    Wpstream_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     3.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
            return $this->version;
	}
        
        
      
        public function show_user_data($pack_details){
            if( isset($pack_details['available_data_mb']) && isset( $pack_details['available_storage_mb']) ){
                $wpstream_convert_band      =   $this->wpstream_convert_band($pack_details['available_data_mb']);
                if($wpstream_convert_band<0)$wpstream_convert_band=0;
            
                $wpstream_convert_storage =   $this->wpstream_convert_band($pack_details['available_storage_mb']);
                if($wpstream_convert_storage<0)$wpstream_convert_storage=0;
                
                print '<div class="pack_details_wrapper"><strong>'.__('Your account information: ','wpstream').'</strong> '.__('You have','wpstream').'<strong> '.$wpstream_convert_band.' Gb</strong> '.__('available streaming data and','wpstream').' <strong>'.$wpstream_convert_storage.' Gb</strong> '.__('available recording storage','wpstream');
                print '<a href="https://wpstream.net/pricing/" class="wpstream_upgrade_topbar" target="_blank">'.esc_html__('Upgrade Subscription','wpstream').'</a>';
                print '</div>';
                print'<input type="hidden" id="wpstream_band" value="'.$pack_details['available_data_mb'].'">';
                print'<input type="hidden" id="wpstream_storage" value="'.$pack_details['available_storage_mb'].'">';

            }
        }

        
        /**
	 * help function for media list elementor widget
	 *
	 * @since     3.0.1
	 * @return    string   
	 */
        
        public function wpstream_media_list_elementor_function($attributes, $content = null){
          
            
                $media_number=3;
                if ( isset($attributes['media_number']) ){
                    $media_number=$attributes['media_number'];
                }
              
                
                $row_number=3;
                if ( isset($attributes['row_number']) ){
                    $row_number=$attributes['row_number'];
                }
                if($row_number>4){
                    $row_number=4;
                }

             
                ob_start();   

                // check if is vod or live stream
                $meta_query     =   array();
                $tax_query_array=   array();
                $tax_query      =   array();
                
                // check if the media is paid or free
                $event_types            =   array(); 
                $product_type_free_paid =   0;
                if ( isset($attributes['product_type_free_paid']) ){
                    $product_type_free_paid=$attributes['product_type_free_paid'];
                   
                    if($product_type_free_paid==0){
                        $event_types=array('wpstream_product');
                        
                        if ( isset($attributes['product_type']) && intval($attributes['product_type'])==2){
                            $event_types=array('wpstream_product_vod');
                        }

                    }else{
                        $event_types=array('product');
                        if ( isset($attributes['product_type']) ){
                            $product_type       =   $attributes['product_type'];
                            $product_type_slug  =   'video_on_demand';
                            if($product_type==1){
                                $product_type_slug ='live_stream';
                            }
                            
                            $tax_query_array =  array(
                                            'taxonomy'     => 'product_type',
                                            'field'        => 'slug',
                                            'terms'        => $product_type_slug  
                                        );
                            
                            $tax_query= array(
                                        'relation'  => 'AND',
                                        $tax_query_array
                                    );
                         
                        }
                    }
                    
                }
                $see_product='';
                if(isset($attributes['free_label'])){
                    $see_product=$attributes['free_label'];
                }
                
                // pagination
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                if( is_front_page() ){
                    $paged= (get_query_var('page')) ? get_query_var('page') : 1;
                }

                
                //order 
                $orderby='ID';
                $order  ='ASC';
                if ( isset($attributes['order_by']) ){
                    $order_by=intval($attributes['order_by']);
                     switch ($order_by) {
                        case 0:
                            $orderby='ID';
                            $order  ='ASC';
                            break;
                        case 1:
                            $orderby='ID';
                            $order  ='DESC';
                            break;
                        case 2:
                            $orderby='title';
                            $order  ='ASC';
                            break;
                         case 3:
                            $orderby='title';
                            $order  ='DESC';
                            break;
                    }
                                     }
                
                // building wp_query arg array                  
                $args = array(
                    'post_type'      => $event_types,
                    'post_status'    => 'publish',
                    'meta_query'     => $meta_query,
                    'posts_per_page' => $media_number,
                    'paged'          => $paged,
                    'orderby'        => $orderby,
                    'order'          => $order,
                    'tax_query'      =>  $tax_query
                );
                
             
             
                // show live events
                if ( isset($attributes['product_show_live']) && $attributes['product_show_live']=='yes'){
                    $live_event_for_user    =    $this->main->wpstream_live_connection->api20_wpstream_request_live_stream_for_user_for_shortcode('shortcode');           
      
                    
                    if( is_array($live_event_for_user) ){
                        $live_event_for_user[]=0;
                       
                        $args['post__in']=$live_event_for_user;
                    }
                }                
                
       
                

                $media_list= new WP_Query($args);
                if($media_list->have_posts()){
                    print '<ul class="wpstream_media_list_wrapper products columns-'.esc_attr($row_number).'" >';
                        while($media_list->have_posts()):$media_list->the_post();
                            if($product_type_free_paid==0 ){

                                $thumb=wp_get_attachment_image_src(get_post_thumbnail_id(),'medium');
                           
                                $thumb_src='';
                                if( isset($thumb[0]) ){
                                    $thumb_src=$thumb[0];
                                }
                                
                                if(($thumb_src)==''){
                                    $thumb_src= plugin_dir_url( dirname( __FILE__ ) ). 'img/default_300.png';
                                }
                                print '<li class="wpstream_product_unit">'
                                .'<a href="'.get_permalink().'" class="product_title" ><div class="product_image" style="background-image:url('.esc_url($thumb_src).')"></div></a>'
                                .'<a href="'.get_permalink().'" class="product_title" >'.get_the_title().'</a>';

                                if($see_product!=''){
                                    print '<a href="'.get_permalink().'"class="see_product">'.$see_product.'</a>';
                                }
                                print '</li>';
                            }else{
                                wc_get_template_part( 'content', 'product' );
                            }
                        endwhile;
                    print '</ul>';
                }else{
                    print esc_html__('No media found! ','wpstream');
                }
         
                
                wp_reset_query();
                wp_reset_postdata();
                
                $this->wpstream_pagination($media_list->max_num_pages,$range=2);              
                $return_string= ob_get_contents();
                ob_end_clean(); 

                return $return_string;
        }
        
        
        
        
        
        
    /*
     * 
     * 
     * Pagination for media lista
     * 
     * 
     * 
     */  
        
        


    function wpstream_pagination($pages = '', $range = 2){

        $showitems = ($range * 2)+1;
        global $paged;
        if(empty($paged)) $paged = 1;


        if($pages == ''){
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if(!$pages)
            {
                $pages = 1;
            }
        }

        if(1 != $pages){
            print '<ul class="wpstream_pagination ">';
            print '<li class="roundleft"><a href="'.get_pagenum_link($paged - 1).'"> < </a></li>';

            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    if ($paged == $i){
                       print '<li class="active"><a href="'.esc_url(get_pagenum_link($i)).'" >'.$i.'</a><li>';
                    }else{
                       print '<li><a href="'.esc_url(get_pagenum_link($i)).'" >'.$i.'</a><li>';
                    }
                }
            }

            $prev_page= get_pagenum_link($paged + 1);
            if ( ($paged +1) > $pages){
               $prev_page= get_pagenum_link($paged );
            }else{
                $prev_page= get_pagenum_link($paged + 1);
            }


            print '<li class="roundright"><a href="'.$prev_page.'"> > </a><li>';
            print '</ul>';
        }
    }

        
        
        
        
        
        
        
        
        
        
        
        
        /**
	 * help function for player elementr widget
	 *
	 * @since     3.0.1
	 * @return    string   
	 */
        
        public function wpstream_insert_player_elementor($attributes, $content = null){
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
              
                if(intval($product_id)==0 && $user_id!=0 ){
                    $product_id= $this->wpstream_player_retrive_first_id($user_id);
                }
                
   
                
                
                ob_start();
                ?>
                <div class="wpstream_insert_player_elementor_wrapper">
                    <?php
                    $this->main->wpstream_player->wpstream_video_player_shortcode($product_id);
                    ?>
                </div>
                <?php
                $return_string= ob_get_contents();
                ob_end_clean(); 

                return $return_string;
        }
        
          /**
	 * help function for player low latency elementor widget
	 *
	 * @since     3.0.1
	 * @return    string   
	 */
        
        public function wpstream_insert_player__low_latency_elementor($attributes, $content = null){
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
                    $product_id= $this->wpstream_player_retrive_first_id($user_id);
                }
                
              
                ob_start();
                $this->main->wpstream_player->wpstream_video_player_shortcode_low_latency($product_id);
                $return_string= ob_get_contents();
                ob_end_clean(); 

                return $return_string;
        }
        
        
        
        public function wpstream_player_retrive_first_id($received_user_id=''){
            $channel_type   =   get_option ('wpstream_user_streaming_channel_type');
            $product_id     =   $this->wpstream_get_current_event_per_author($received_user_id,$channel_type);
            return $product_id;
        }
        
        
        
         /**
	 * help function for chat elementor widget
	 *
	 * @since     3.0.1
	 * @return    string   
	 */
        
        public function wpstream_insert_chat_elementor($attributes, $content = null){
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
        * edited 4.0 
        * 
        * Check if user is allowed to stream
        *
        * @since    3.7
        */
        
        public function wpstream_check_user_can_stream(){
            $current_user       =   wp_get_current_user();
            
            if( !is_user_logged_in() ){
                return false;
                exit('user not logged in');
            }
            
            if(current_user_can('administrator')){
                // admins can always brodcast
                return true;
            }
            
            $extra_roles    =   get_option( 'wpstream_stream_role', true );
            $user_role      =   $current_user->roles[0];
           
            if (is_array($extra_roles) && in_array($user_role, $extra_roles)){
                return true;
            }
            
            if(function_exists('wpstream_get_option') && intval(wpstream_get_option('allow_streaming_regular_users',''))==1 ){
                return true;
            }
            
            return false;
        }
        

         /**
        * Start Streaming wrapper
        *
        * @since    3.7
        */
        
        public function wpstream_live_stream_unit_wrapper($item_id,$type){
            $item_id = intval($item_id);
       
            if($item_id == 0){
                //retrive or  create channel for front end streamers
                $item_id=$this->wpstream_retrive_front_end_channel();
            }
            print'<div class="wpstream_modal_background"></div>';    
            print '<div class="wpstream_error_modal_notification"><div class="wpstream_error_content">er2</div>
            <div class="wpstream_error_ok wpstream_button" type="button">'.esc_html__('Close','wpstream').'</div>
            </div>';
            $this->admin->wpstream_live_stream_unit(  $item_id,$type );
        }
        
        
         /**
        * Start Streaming wrapper for wpstream theme
        *
        * @since    3.7
        */
        
        public function wpstream_live_stream_unit_wrapper_for_theme($item_id,$type){
            $item_id = intval($item_id);
       
            if($item_id == 0){
                //retrive or  create channel for front end streamers
                $item_id=$this->wpstream_retrive_front_end_channel();
            }
            print'<div class="wpstream_modal_background"></div>';    
            print '<div class="wpstream_error_modal_notification"><div class="wpstream_error_content">er2</div>
            <div class="wpstream_error_ok wpstream_button" type="button">'.esc_html__('Close','wpstream').'</div>
            </div>';
            $this->admin->wpstream_live_stream_unit_for_theme(  $item_id,$type );
        }
        
        
        /**
        * retrive channel for front end streaming
        *
        * @since    3.7
        */
        public function wpstream_retrive_front_end_channel(){
            
            $current_user   = wp_get_current_user();
            $channel_type   = get_option ('wpstream_user_streaming_channel_type');
            $channel_price  = floatval( get_option ('wpstream_user_streaming_default_price') );
            
            $front_end_streamin_channel = $this->wpstream_get_current_event_per_author($current_user->ID,$channel_type);
         
            if(intval($front_end_streamin_channel) == 0){
                $front_end_streamin_channel= $this->wpstrea_create_front_end_event($current_user->ID,$current_user->user_login ,$channel_type,$channel_price);
            }
            return $front_end_streamin_channel;
            
        }
          
        /**
        * create the event from front end
        *
        * @since    3.7
        */
        
        public function wpstrea_create_front_end_event($userID,$userLogin,$channel_type,$channel_price){
            
           if( !$this->wpstream_check_user_can_stream() ){
               return;
           }
        
            $post_type='wpstream_product';
            if($channel_type=='paid'){
                $post_type='product';
            }
           
            $post = array(
                'post_title'	=>  sprintf( esc_html__('%s Channel','wpstream'),$userLogin),
                'post_content'	=>  '',
                'post_type'     =>  $post_type ,
                'post_author'   =>  $userID,
                'post_status'   =>  'publish',
            );
            $post_id =  wp_insert_post($post );  
            if($channel_type=='paid'){
                $product    =   wc_get_product($post_id);
                $price      =   wc_format_decimal($channel_price);
                
                $product = wc_get_product( $post_id );
                // Set the product active price (regular)
                $product->set_price( $price );
                $product->set_regular_price( $price ); // To be sure
                $product->save();
                update_post_meta ($post_id,'event_passed',0);
                wp_set_object_terms( $post_id, 'live_stream', 'product_type' );
            }
            update_post_meta($post_id,'wpstream_product_type',1);
        
            return $post_id;
        }
        
        
        public function wpstream_get_current_event_per_author($userID,$channel_type){
  
            $post_type='wpstream_product';
            if($channel_type=='paid'){
                $post_type='product';
            }
           
            $args = array(
       
                'post_type'         =>  $post_type,
                'author'            =>  $userID,
                'posts_per_page'    =>  1,
                'fields'            =>  'ids'
            )
                    ;
            $author_posts = new WP_Query( $args );
            if( $author_posts->have_posts() ) {
                $author_posts->the_post();
                $the_id= get_the_ID();
            }else{
                 $the_id= 0;
            }
            
            wp_reset_query();
            wp_reset_postdata();
            return $the_id;
        }
    
}
