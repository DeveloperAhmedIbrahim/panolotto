<?php
namespace ElementorWpStream\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wpstream_Media_List_Vod extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'class Wpstream_Media_List_Vod';
	}

        public function get_categories() {
		return [ 'wpstream' ];
	}
        
        
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
            return '<div class="wpestate_elementor_widget_title">'.__( 'WpStream - Video on Demand List', 'wpstream' ).'</div>';
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-play-o';
	}

	

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
	return [ '' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
     
        public function elementor_transform($input){
            $output=array();
            if( is_array($input) ){
                foreach ($input as $key=>$tax){
                    $output[$tax['value']]=$tax['label'];
                }
            }
            return $output;
        }

        protected function _register_controls() {
            global $all_tax;

           $product_type=array(
                    0 =>  __('Both','wpstream'),
                    1 =>  __('Live Event','wpstream'),
                    2 =>  __('Video on demand','wpstream')
            );
           
            $free_paid_type=array(
                0 =>  esc_html__('Free','wpstream'),
                1 =>  esc_html__('Paid','wpstream')
            );
            
            
            $order_by_id=array(
                0=>esc_html('By date - ASC','wpstream'),
                1=>esc_html('By date - DESC','wpstream'),
                2=>esc_html('By title - ASC','wpstream'),
                3=>esc_html('By title - DESC','wpstream'),
            );
            
            
            $live_settings=array(
                'no'=>esc_html__('no','wpstream'),
                'yes'=>esc_html__('yes','wpstream'),
            );

            $this->start_controls_section(
                    'section_content',
                    [
                            'label' => __( 'Content', 'wpstream' ),
                    ]
            );

//            $this->add_control(
//                  'product_type',
//                  [
//                      'label' => __( 'What type of media', 'wpstream' ),
//                      'type' => \Elementor\Controls_Manager::SELECT,
//                      'default' => $product_type[0],
//                      'options' => $product_type
//                  ]
//            );
            
            $this->add_control(
                  'product_type_free_paid',
                  [
                      'label' => __( 'Show Free or Paid Media ?', 'wpstream' ),
                      'type' => \Elementor\Controls_Manager::SELECT,
                      'default' => $free_paid_type[0],
                      'options' => $free_paid_type
                  ]
            );
            
          
//            $this->add_control(
//                  'product_show_live',
//                  [
//                      'label' => __( 'Show only live events', 'wpstream' ),
//                      'type' => \Elementor\Controls_Manager::SELECT,
//                      'default' => 'no',
//                      'options' => $live_settings
//                  ]
//            );    
           
           
            $this->add_control(
                    'media_number',
                    [
                        'label' => __( 'Number of Items per Page', 'wpstream' ),
                        'label_block'=>true,
                        'type' => Controls_Manager::TEXT,
                        'description'=>__('How many items will be displayed per page','wpstream'),
                        'default'=>3
                    ]
            );
            
            $this->add_control(
                'row_number',
                [
                    'label' => __( 'Number of Items per row', 'wpstream' ),
                    'label_block'=>true,
                    'type' => Controls_Manager::TEXT,
                    'description'=>__('How many items will be displayed per row. Maximum no is 4','wpstream'),
                    'default'=>3
                ]
            );
            $this->add_control(
                    'free_label',
                    [
                        'label' => __( 'Link Label for free items', 'wpstream' ),
                        'label_block'=>true,
                        'type' => Controls_Manager::TEXT,
                        'default'=>esc_html__('Watch now!','wpstream'),
                        'description'=>__('Link Label for free items','wpstream')
                    ]
            );
              
                
            
            $this->add_control(
                  'order_by',
                  [
                      'label' => __( 'Order by', 'wpstream' ),
                      'type' => \Elementor\Controls_Manager::SELECT,
                      'default' => 0,
                      'options' => $order_by_id
                  ]
            ); 
                
            $this->end_controls_section();

		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
        
         public function wpresidence_send_to_shortcode($input){
            $output='';
            if($input!==''){
                $numItems = count($input);
                $i = 0;

                foreach ($input as $key=>$value){
                    $output.=$value;
                    if(++$i !== $numItems) {
                      $output.=', ';
                    }
                }
            }
            return $output;
        }
        
	protected function render() {
            $settings = $this->get_settings_for_display();

            $attributes['product_type']                 =   2 ;  
            $attributes['product_type_free_paid']       =   $settings['product_type_free_paid'] ;  
            $attributes['media_number']                 =   $settings['media_number'] ;  
            $attributes['row_number']                   =   $settings['row_number'] ;  
            $attributes['free_label']                   =   $settings['free_label'] ;  
            $attributes['order_by']                     =   $settings['order_by'] ;  
            $attributes['product_show_live']            =   'no';
            global $wpstream_plugin;
           
          
            
           // echo  $wpstream_plugin->admin->wpstream_live_stream_unit(   $attributes['id'],'front' );
            echo  $wpstream_plugin->wpstream_media_list_elementor_function(   $attributes );
	}


}
