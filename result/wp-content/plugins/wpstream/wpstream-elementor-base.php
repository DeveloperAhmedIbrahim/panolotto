<?php
namespace ElementorWpStream;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin_Base {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {       
            require_once( __DIR__ . '/widgets/player.php' );
            require_once( __DIR__ . '/widgets/wpstream_chat.php' );
            require_once( __DIR__ . '/widgets/player-lowlatency.php' );
            require_once( __DIR__ . '/widgets/start_streaming.php' );
            require_once( __DIR__ . '/widgets/media_list_channels.php' );
            require_once( __DIR__ . '/widgets/media_list_vod.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
            // Its is now safe to include Widgets files
            $this->include_widgets_files();

            // Register Widgets
  
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Wpstream_Player_Base() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Wpstream_Chat_Base() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Wpstream_Player_LowLatecy_Base() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Wpstream_Start_Streaming_Base() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Wpstream_Media_List_Channel() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Wpstream_Media_List_Vod() );
    
        }

      
        
	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
        public function add_elementor_widget_categories($elements_manager){
            $elements_manager->add_category(
		'wpstream',
		[
			'title' => __( 'WpStream Widgets', 'wpstream' ),
			'icon'  => 'fa fa-home',
		]
            );
           

        }
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
                
                add_action( 'elementor/elements/categories_registered',  [ $this, 'add_elementor_widget_categories' ]  );
	}
}

// Instantiate Plugin Class
Plugin_Base::instance();