<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-wpstream-player
 *
 * @author cretu
 */
class Wpstream_Player{
    public $main;
    
    public function __construct($plugin_main) {
        $this->main = $plugin_main;
          
        add_filter( 'the_content',array($this, 'wpstream_filter_the_title') );
        add_action( 'woocommerce_before_single_product', array($this,'wpstream_user_logged_in_product_already_bought') );
        
        add_action( 'wp_ajax_wpstream_player_check_status', array($this,'wpstream_player_check_status') );  
        add_action('wp_ajax_nopriv_wpstream_player_check_status', array($this,'wpstream_player_check_status'));
     
        
   
        
        
    }
    
    
        
  
    
        
    /**
     * 
     * edited 4.0
     * 
    * check player status
    *
    * @author cretu
    */
    
    public function wpstream_player_check_status(){
        $channel_id                   =   intval($_POST['channel_id']);
     
        
        $transient_name             =   'event_data_to_return_'.   $channel_id;
        $event_data_for_transient   =   get_transient( $transient_name );
       
    

        if ( false ===  $event_data_for_transient || $event_data_for_transient=='' ) { //ws || $hls_to_return==''        
            $notes                      =   'wpstream_player_check_status_note_from_js';   
            $event_status               =   $this->main->wpstream_live_connection-> wpstream_check_event_status_api_call($channel_id,$notes);
            $event_data_for_transient   =   $event_status;

            set_transient($transient_name,$event_data_for_transient,45);
        }
        
        
        if( isset($event_data_for_transient['hls_playback_url']) && $event_data_for_transient['hls_playback_url']!=''){
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
               
                    'started'               =>  'yes',
                    'channel_id'            =>  $channel_id,
                    'event_uri'             =>  $event_data_for_transient['hls_playback_url'],
                    'live_conect_views'     =>  $event_data_for_transient['stats_url'],
                    'chat_url'              =>  $event_data_for_transient['chat_url'],
                    '$event_data_for_transient'=>$event_data_for_transient
                 
                                   
            ));
            update_post_meta($channel_id,'stream_name',$event_data_for_transient['stream_name']);
            update_post_meta($channel_id,'hls_key_retrieval_url',$event_data_for_transient['hls_key_retrieval_url']);
            delete_transient(  'free_event_streamName_'.$event_data_for_transient['stream_name']);

        }else{
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                    'started'               =>  'no',
                    'server_id'             =>  '',
                    'channel_id'              =>  $channel_id,
                    'event_uri'             =>  '',
                    'live_conect_views'     =>  '',
                    'chat_url'              =>  '',
                                   
            ));
       
        }
        
        die();
    }
    
    
    
    /**
    * Insert player in page
    *
    * @author cretu
    */
    public function wpstream_filter_the_title( $content   ) {
            if(function_exists('remove_wpstream_filter')){
                return $content;
            }

            if( is_singular('wpstream_product') || is_singular('wpstream_product_vod') ){
                global $post;
                $args=array('id'=>$post->ID);
                $custom_content = $this->wpstream_insert_player_inpage($args);
                $content = '<div class="wpestream_inserted_player">'.$custom_content.'</div>'.$content;
                return $content;
            }else{
                return $content;
            }
    }
    
    /**
    * Insert player in page
    *
    * @author cretu
    */

    public function wpstream_insert_player_inpage($attributes, $content = null){
        $product_id     =   '';
        $return_string  =   '';
        $attributes =   shortcode_atts( 
            array(
                'id'                       => 0,
            ), $attributes) ;


        if ( isset($attributes['id']) ){
            $product_id=$attributes['id'];
        }
         
        if(intval($product_id)==0){
            $product_id= $this->wpstream_player_retrive_first_id();
        }

        ob_start();
     
        $this->wpstream_video_player_shortcode($product_id);
        $return_string= ob_get_contents();
        ob_end_clean(); 

        return $return_string;
    }

    
    
    
    /**
    * Video Player shortcode
    *
    * @author cretu
    */

    public function wpstream_video_player_shortcode($from_sh_id='') {

 

        if ( is_user_logged_in() ) {
            global $product;
            $current_user   =   wp_get_current_user();
            $product_id     =   intval($from_sh_id);
            $term_list      =   wp_get_post_terms($product_id, 'product_type');
            $possible_bundle = get_post_meta($product_id, 'wpstream_part_of_bundle', true);
         



            if (
                
                ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ) || 
                ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && (intval($possible_bundle)!=0) && wc_customer_bought_product( $current_user->user_email, $current_user->ID, $possible_bundle) ) || 
                get_post_type($product_id)=='wpstream_product' || 
                get_post_type($product_id)=='wpstream_product_vod' ){
                global $product;
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode"><div class="wpstream_player_container">';


                if( get_post_type($product_id) == 'wpstream_product' ){
                    $this->wpstream_live_event_player($product_id);
                }else if( get_post_type($product_id) == 'wpstream_product_vod' ){
                    $this->wpstream_video_on_demand_player($product_id);
                }else{
               
                    $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));

                    if( $term_list[0]->name=='live_stream' || ( $term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' ) ){
                        $this->wpstream_live_event_player($product_id);
                    }else if( $term_list[0]->name=='video_on_demand'  || ($term_list[0]->name=='subscription' && $is_subscription_live_event=='no' ) ){
                        $this->wpstream_video_on_demand_player($product_id);
                    }
                }


                echo '</div></div>';
            }else{
                
                if( get_post_type($product_id) == 'product' ){
                    echo '<div class="wpstream_player_wrapper wpstream_player_shortcode no_buy"><div class="wpstream_player_container">';
                    $message =esc_html( get_option('wpstream_product_not_bought','You did not yet purchase this item.')) ;
                    echo '<div class="wpstream_notice" style="background:#e16767;">'.esc_html($message).'</div>';
                    echo '</div></div>';
                }
            }

         
        }else{
            
            $product_id     =   intval($from_sh_id);
            $term_list      =   wp_get_post_terms($product_id, 'product_type');
            
            if( get_post_type($product_id) == 'product' && ($term_list[0]->name=='live_stream' || $term_list[0]->name=='video_on_demand') ){
                
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode no_buy"><div class="wpstream_player_container">';
                $message= esc_html( get_option('wpstream_product_not_login','You must be logged in to watch this video.')) ;


                echo '<div class="wpstream_notice" style="background:#e16767;">'.esc_html($message).'</div>';
                echo '</div></div>';
            }elseif( get_post_type($product_id) == 'wpstream_product' ){
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode"><div class="wpstream_player_container">'; 
                    $this->wpstream_live_event_player($product_id);               
                echo '</div></div>';
            } else if( get_post_type($product_id) == 'wpstream_product_vod' ){
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode"><div class="wpstream_player_container">'; 
                    $this->wpstream_video_on_demand_player($product_id);
                echo '</div></div>';
            }
        }
    }

    
    
    /**
    * Video Player shortcode - low latency
    *
    * @author cretu
    */

    public function wpstream_video_player_shortcode_low_latency($from_sh_id='') {

        if ( is_user_logged_in() ) {
            global $product;
            $current_user   =   wp_get_current_user();
            $product_id     =   intval($from_sh_id);


            if ( ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ) || get_post_type($product_id)=='wpstream_product' ){
                global $product;
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode"><div class="wpstream_player_container">';


                if( get_post_type($product_id) == 'wpstream_product' ){
                    $this->wpstream_live_event_player_low_latency($product_id);               
                }else{
                    $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                    $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));

                    if( $term_list[0]->name=='live_stream' || ( $term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' ) ){
                        $this->wpstream_live_event_player_low_latency($product_id);
                    }
                }


                echo '</div></div>';
            }else{
                
                if( get_post_type($product_id) == 'product' ){
                    echo '<div class="wpstream_player_wrapper wpstream_player_shortcode no_buy"><div class="wpstream_player_container">';
              
                    $message =esc_html( get_option('wpstream_product_not_bought','You did not yet purchase this item.')) ;
                    echo '<div class="wpstream_notice" style="background:#e16767;">'.$message.'</div>';
                    echo '</div></div>';
                }
            }

         
        }else{
            $product_id     =   intval($from_sh_id);
            if( get_post_type($product_id) == 'wpstream_product' ){
                $this->wpstream_live_event_player_low_latency($product_id);                  
            }
        }
    }

    
    
    
    
    /**
    * Live Event Player
    *
    * @author cretu
    */
    function remove_http($url) {
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
           if(strpos($url, $d) === 0) {
              return str_replace($d, '', $url);
           }
        }
        return $url;
    }
      
    /**
    * Get event settings
    *
    * @author cretu
    */
    
    function wpestream_return_event_settings($product_id){
            
        $local_event_options =   get_post_meta($product_id,'local_event_options',true);
        if(!is_array($local_event_options)){
            $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;
        }
        
        return $local_event_options;
    }
    
    
    
    /**
     * edited in 4.0
     * 
    * Live Event Player
    *
    * @author cretu
    */
    
    function wpstream_live_event_player($channel_id,$poster_show='',$use_chat=''){
           
        $now                =   time().rand(0,1000000);
        $overlay_video_div_id = "random_id_".$now;
        // print '<div id="'.esc_attr($overlay_video_div_id).'" class="vjs-title-overlay wpstream-video-title-overlay">'.esc_html__('Playing:','wpstream').' '.get_the_title($channel_id).'</div>';


        $thumb_id           =   get_post_thumbnail_id($channel_id);
        $thumb              =   wp_get_attachment_image_src($thumb_id,'small');
        $usernamestream     =   esc_html ( get_option('wpstream_api_username','') );
        $autoplay           =   true;
        
        $event_settings     =   $this->wpestream_return_event_settings($channel_id);
        $notes              =   'wpstream_live_event_player_note';
        $event_status       =   $this->main->wpstream_live_connection-> wpstream_check_event_status_api_call($channel_id,$notes);
        $hls_playback_url     =   '';
        $live_conect_views  =   '';
        
        if(isset($event_status['status']) && $event_status['status']=='active'){
            //live event
            if(isset($event_status['hls_playback_url'])){
                $hls_playback_url        =   $event_status['hls_playback_url'];
                
                update_post_meta($channel_id,'stream_name',$event_status['stream_name']);
                update_post_meta($channel_id,'hls_key_retrieval_url',$event_status['hls_key_retrieval_url']);
                delete_transient(  'free_event_streamName_'.$event_status['stream_name']);

                $live_conect_array      =   explode('live.streamer.wpstream.net',$hls_playback_url);
                $live_conect_views      =   $live_conect_array[0].'live.streamer.wpstream.net';
                $live_conect_views      =   $this->remove_http($live_conect_views);
        
            }
             if(isset($event_status['chat_url'])){
                $chat_url = $event_status['chat_url'];
            }
                     
        }else{
            // event not live
        }
        
        if(isset($event_settings['autoplay']) && intval($event_settings['autoplay'])==0){
            $autoplay=false;
        }

        

        echo '<div class="wpstream_live_player_wrapper function_wpstream_live_event_player" data-now="'.$now.'" data-me="'.esc_attr($usernamestream).'" data-product-id="'.$channel_id.'" id="wpstream_live_player_wrapper'.$now.'" > ';
                
            if( ( isset($event_settings['view_count'] ) && intval($event_settings['view_count'])==1 ) || !isset($event_settings['view_count']) ){
                echo '<div id="wpestream_live_counting" class="wpestream_live_counting"></div>';
            }
            
            $show_wpstream_not_live_mess=' style="display:none;" ';
            if(trim($hls_playback_url) ==''){

                $show_wpstream_not_live_mess=''; 
            }
      
            $message_show= esc_html( get_option('wpstream_you_are_not_live','We are not live at this moment')) ;
      
            if(function_exists('wpstream_theme_not_live_section')){
                print wpstream_theme_not_live_section($channel_id);
            }else{
                print '<div class="wpstream_not_live_mess" '.$show_wpstream_not_live_mess.'><div class="wpstream_not_live_mess_back"></div><div class="wpstream_not_live_mess_mess">'.esc_html($message_show).'</div></div>';
            }
            
                 
            $poster_data='';
            if(isset($thumb[0])){
                $poster_data=' poster="'.$thumb[0].'" ';
            }
            if($poster_show=='no'){
                $poster_data='';
            }

            $is_muted=false;
            if( isset($event_settings['mute']) && intval($event_settings['mute'])==1){
                $is_muted=true;
            }
            // override $is_muted and $autoplay here - for testing
            // $autoplay = true;
            // $is_muted = false;

            $autoplay_str = $autoplay ? 'autoplay' : '';
            $is_muted_str = $is_muted ? 'muted' : '';
                
            $video_trailer = '';
            $trailer_attachment_id    =  intval (get_post_meta( $channel_id, 'video_trailer', true ));
            $video_trailer            = '';
            $video_trailer_type       = '';
            $has_trailer_class        = '';
            if($trailer_attachment_id!=0) {
                $video_trailer                 =   wp_get_attachment_url( $trailer_attachment_id );
                $attachment_metadata           =   wp_get_attachment_metadata($trailer_attachment_id);
                if( isset ($attachment_metadata['mime_type']) ) {
                    $video_trailer_type            =   $attachment_metadata['mime_type'];
                }
                $poster_data=''; // cancel poster for theme
                $has_trailer_class='wpstream_theme_player_has_trailer';
            }

            // override trailer url here - for testing
            // $video_trailer = '';
            // $video_trailer = '/wp-content/uploads/2023/10/production-ID_4608975.mp4';
            // $video_trailer = '/wp-content/uploads/2023/10/ultrawide.mp4';



                echo'
                <video id="wpstream-video'.$now.'"     '.$poster_data.'  class="video-js vjs-default-skin  vjs-fluid vjs-wpstream '.esc_attr($has_trailer_class).'" playsinline="true" '.$is_muted_str." ".$autoplay_str.'>
                
                </video>';
                if ($video_trailer){
                    print '<div class="wpstream_theme_trailer_wrapper">';
                    print '<div id="wpstream_live_video_play_trailer_btn_' . $now . '" style="display: none;" class="wpstream_video_on_demand_play_trailer">
                    <svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M26.6667 1.5H3.33337C2.50495 1.5 1.83337 2.17157 1.83337 3V21C1.83337 21.8284 2.50495 22.5 3.33338 22.5H26.6667C27.4951 22.5 28.1667 21.8284 28.1667 21V3C28.1667 2.17157 27.4951 1.5 26.6667 1.5ZM3.33337 0C1.67652 0 0.333374 1.34315 0.333374 3V21C0.333374 22.6569 1.67652 24 3.33338 24H26.6667C28.3236 24 29.6667 22.6569 29.6667 21V3C29.6667 1.34315 28.3236 0 26.6667 0H3.33337ZM4.83337 4C4.55723 4 4.33337 4.22386 4.33337 4.5V6.16667C4.33337 6.44281 4.55723 6.66667 4.83337 6.66667H6.50004C6.77618 6.66667 7.00004 6.44281 7.00004 6.16667V4.5C7.00004 4.22386 6.77618 4 6.50004 4H4.83337ZM23.5 4C23.2239 4 23 4.22386 23 4.5V6.16667C23 6.44281 23.2239 6.66667 23.5 6.66667H25.1667C25.4428 6.66667 25.6667 6.44281 25.6667 6.16667V4.5C25.6667 4.22386 25.4428 4 25.1667 4H23.5ZM4.33337 11.167C4.33337 10.8909 4.55723 10.667 4.83337 10.667H6.50004C6.77618 10.667 7.00004 10.8909 7.00004 11.167V12.8337C7.00004 13.1098 6.77618 13.3337 6.50004 13.3337H4.83337C4.55723 13.3337 4.33337 13.1098 4.33337 12.8337V11.167ZM23.5001 10.667C23.224 10.667 23.0001 10.8909 23.0001 11.167V12.8337C23.0001 13.1098 23.224 13.3337 23.5001 13.3337H25.1668C25.4429 13.3337 25.6668 13.1098 25.6668 12.8337V11.167C25.6668 10.8909 25.4429 10.667 25.1668 10.667H23.5001ZM4.33337 17.833C4.33337 17.5569 4.55723 17.333 4.83337 17.333H6.50004C6.77618 17.333 7.00004 17.5569 7.00004 17.833V19.4997C7.00004 19.7758 6.77618 19.9997 6.50004 19.9997H4.83337C4.55723 19.9997 4.33337 19.7758 4.33337 19.4997V17.833ZM23.5001 17.333C23.224 17.333 23.0001 17.5569 23.0001 17.833V19.4997C23.0001 19.7758 23.224 19.9997 23.5001 19.9997H25.1668C25.4429 19.9997 25.6668 19.7758 25.6668 19.4997V17.833C25.6668 17.5569 25.4429 17.333 25.1668 17.333H23.5001ZM19.0677 13.0997L13.4077 16.5087C13.0434 16.7281 12.6092 16.7094 12.2661 16.5091C11.9218 16.3081 11.6666 15.9224 11.6666 15.4086V8.59072C11.6666 8.07698 11.9218 7.69125 12.2661 7.49026C12.6092 7.28999 13.0434 7.27126 13.4077 7.49064L19.0677 10.8996C19.8663 11.3805 19.8663 12.6188 19.0677 13.0997Z"/>
                    </svg>
                    '.esc_html('Play Trailer').'</div>';
                    print '<div id="wpstream_live_video_mute_trailer_btn_' . $now . '" style="display: none;" class="wpstream_video_on_demand_mute_trailer">
                    <svg width="37" height="36" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.32143 10.0789H8.69499L18.8964 0L21.1428 0.921053V35.1316L18.8964 36L8.69499 25.8684H1.32143L0 24.5526V11.3947L1.32143 10.0789ZM10.175 23.6842L18.5 31.9474V4.10526L10.175 12.3158L9.24999 12.7105H2.64286V23.2368H9.24999L10.175 23.6842ZM37 17.9737C37.0069 22.2216 35.5329 26.3401 32.8295 29.6263L30.9478 27.7579C33.1613 24.9734 34.3629 21.5249 34.3571 17.9737C34.3571 14.2895 33.0885 10.8974 30.9637 8.21053L32.8454 6.34211C35.5382 9.62494 37.0062 13.735 37 17.9737ZM31.7143 17.9737C31.7193 20.8255 30.7895 23.6011 29.0661 25.8789L27.1738 23.9947C28.4127 22.2295 29.0752 20.1272 29.0714 17.9737C29.0751 15.8287 28.4174 13.7344 27.1871 11.9737L29.0793 10.0895C30.7338 12.2868 31.7143 15.0158 31.7143 17.9737ZM26.4286 17.9737C26.4286 19.4842 26.0057 20.8947 25.2657 22.0947L23.3126 20.1526C23.6249 19.4729 23.7876 18.7345 23.7899 17.9869C23.7922 17.2394 23.634 16.5001 23.3258 15.8184L25.2789 13.8737C26.0083 15.0684 26.4286 16.4737 26.4286 17.9737Z" fill="white"/>
                    </svg>

                    </div>';
                    print '<div id="wpstream_live_video_unmute_trailer_btn_' . $now . '" style="display: none;" class="wpstream_video_on_demand_unmute_trailer">
                    <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.15625 8.85688H7.60813L16.5344 0L18.5 0.809375V30.8719L16.5344 31.635L7.60813 22.7319H1.15625L0 21.5756V10.0131L1.15625 8.85688ZM8.90313 20.8125L16.1875 28.0738V3.6075L8.90313 10.8225L8.09375 11.1694H2.3125V20.4194H8.09375L8.90313 20.8125ZM30.5967 11.3127L32.2316 12.9477L28.2287 16.9506L32.2316 20.9559L30.5967 22.5908L26.5938 18.5856L22.5885 22.5908L20.9536 20.9559L24.9588 16.9506L20.9513 12.95L22.5862 11.3151L26.5938 15.3157L30.5967 11.3127Z" fill="white"/>
                    </svg>
                    </div>';
                    print '</div>';
                }
                print '<script type="text/javascript">
                    //<![CDATA[
                        jQuery(document).ready(function(){
                            wpstream_player_initialize({
                                titleOverlayElementId:"'.$overlay_video_div_id.'",
                                videoElementId: "'.$now.'",
                                trailerUrl: "'.$video_trailer.'",
                                contentUrl: "'.$hls_playback_url.'",
                                statsUri:  "'.$live_conect_views.'",
                                autoplay: '.var_export($autoplay, true).',
                                muted: '.var_export($is_muted, true).',
                                playTrailerButtonElementId: "wpstream_live_video_play_trailer_btn_'.$now.'",
                                muteTrailerButtonElementId: "wpstream_live_video_mute_trailer_btn_'.$now.'",
                                unmuteTrailerButtonElementId: "wpstream_live_video_unmute_trailer_btn_'.$now.'",
                            });
                        });
                    //]]>
                    </script>';
            print '</div>';   
           
           
            if(trim($hls_playback_url) ==''){
                // $show_wpstream_not_live_mess=''; 
            }else{
                   print '<script type="text/javascript">
                        //<![CDATA[
                            jQuery(document).ready(function(){
                                var player_wrapper =   jQuery(".wpstream_live_player_wrapper");
                                wpstream_read_websocket_info("'.$channel_id.'","wpstream_live_player_wrapper'.$now.'", player_wrapper ,"'.$chat_url.'", "'.$hls_playback_url.'");
                            });
                        //]]>
                    </script>';
            }
           
           
           if($use_chat=="yes"){
                $this->wpstream_connect_to_chat($channel_id);
           }
           
           usleep (10000);

    }





    /**
    * 
    * Edited in 4.0
    * 
    * 
    * Live Event Player
    *
    * @author cretu
    */
    
    function wpstream_live_event_player_low_latency($channel_id,$poster_show='',$use_chat=''){
            $usernamestream         =   esc_html ( get_option('wpstream_api_username','') );
            $thumb_id               =   get_post_thumbnail_id($channel_id);
            $thumb                  =   wp_get_attachment_image_src($thumb_id,'small');
            
            $event_settings     =   $this->wpestream_return_event_settings($channel_id);
            $notes              =   'wpstream_live_event_player_low_latency_note';
            $event_status       =   $this->main->wpstream_live_connection-> wpstream_check_event_status_api_call($channel_id,$notes);
            $hls_playback_url     =   '';
            $live_conect_views  =   '';
            $now                =   time().rand(0,10);
            
            

            if(isset($event_status['status']) && $event_status['status']=='active'){
                //live event
                if(isset($event_status['sldp_playback_url'])){
                    $hls_playback_url         =   $event_status['sldp_playback_url'];
                      
                    $live_conect_array      =   explode('live.streamer.wpstream.net',$hls_playback_url);
                    $live_conect_views      =   $live_conect_array[0].'live.streamer.wpstream.net';
                    $live_conect_views      =   $this->remove_http($live_conect_views);
            
                }
                 if(isset($event_status['chat_url'])){
                    $chat_url =$event_status['chat_url'];
                }
                      
            }else{
                // event not live
            }
            
            
          
            
            echo '<div class="wpstream_live_player_wrapper function_wpstream_live_event_player_low_latency wpstream_low_latency" data-now="'.$now.'" data-me="'.esc_attr($usernamestream).'" data-product-id="'.$channel_id.'" id="wpstream_live_player_wrapper'.$now.'" > ';
                    
                   
                    if( ( isset($event_settings['view_count'] ) && intval($event_settings['view_count'])==1 ) || !isset($event_settings['view_count']) ){
                        echo '<div id="wpestream_live_counting" class="wpestream_live_counting"></div>';
                    }
                  
                    $show_wpstream_not_live_mess=' style="display:none;" ';
                    if(trim($hls_playback_url) ==''){
                        $show_wpstream_not_live_mess=''; 
                    }
            
                    $message_show= esc_html( get_option('wpstream_you_are_not_live','We are not live at this moment')) ;
                    print '<div class="wpstream_not_live_mess " '.$show_wpstream_not_live_mess.' ><div class="wpstream_not_live_mess_back"></div><div class="wpstream_not_live_mess_mess">'. $message_show.'</div></div>';
                     
                    
                    $poster_data=' poster="'.$thumb[0].'" ';
                    if($poster_show=='no'){
                        $poster_data='';
                    }
                    
                 
                    $is_muted='';
                    if( isset($event_settings['mute']) && intval($event_settings['mute'])==1){
                        $is_muted=' muted ';
                    }
                    
                    
                    $autoplay='autoplay';
                    if(isset($event_settings['autoplay']) && intval($event_settings['autoplay'])==0){
                        $autoplay='';
                    }
                    
                    echo'
                    <div  iccd="player" id="wpstream-video'.$now.'"   '.$poster_data.' '.$is_muted.' class="" >
                    </div>';

                    print '<script type="text/javascript">
                                //<![CDATA[
                                    jQuery(document).ready(function(){
                                        var low_latencyid="wpstream-video'.$now.'";
                                        document.addEventListener("DOMContentLoaded", initPlayer(low_latencyid, "'.$hls_playback_url.'","'.$is_muted.'","'.$autoplay.'" ) ); ';
                                    print'});
                                //]]>
                            </script>';
                 
               
               
                if(trim($hls_playback_url) ==''){
                    // $show_wpstream_not_live_mess=''; 
                }else{
                       print '<script type="text/javascript">
                            //<![CDATA[
                                jQuery(document).ready(function(){
                                    var player_wrapper =   jQuery(".wpstream_live_player_wrapper");
                                    wpstream_read_websocket_info("'.$channel_id.'","wpstream_live_player_wrapper'.$now.'", player_wrapper ,"'.$chat_url.'", "'.$hls_playback_url.'");
                                });
                            //]]>
                        </script>';
                }
               
               
               if($use_chat=="yes"){
                    $this->wpstream_connect_to_chat($channel_id);
               }
               
               usleep (10000);

        }


        
    
        
        /*
        * 
        * Request HLS player
        * 
        * 
        *  
        */
        public function  wpstream_request_video_on_demand_hls_player($video_name,$product_id){
            if($video_name==''){
                return '';
            }
            
            $transient_name =   'wpstream_video_on_demand_'.$video_name;
            $hls_to_return  =   get_transient( $transient_name );
            $hls_to_return  =   false;

            
            if($hls_to_return==false){
             
                $access_token   =   $this->main->wpstream_live_connection->wpstream_get_token();
                $url            =   'video/info';

                //corsorigin de check
                $local_event_options    =   get_option('wpstream_user_streaming_global_channel_options') ;
                $domain                 =   parse_url ( get_site_url() );
                $domain_scheme          =   'http';
                if(is_ssl()){
                    $domain_scheme='https';
                }

             
                $wpstream_vod_domain_lock = intval( get_option('wpstream_vod_domain_lock','') ) ;
                $corsorigin='*';
                if($wpstream_vod_domain_lock === 0 ){
                    $corsorigin=$domain_scheme.'://'.$domain['host'];
                }

               
                $is_encrypt="false";
                $wpstream_vod_encrypt = intval( get_option('wpstream_vod_encrypt','') ) ;
                if( intval( $wpstream_vod_encrypt ) ==1 ){
                    $is_encrypt="true";
                }

                $hlsKeysUrlPrefix    =  get_site_url().'?wpstream_voddrm=';
                $encrypt             =  $is_encrypt;
                $debugDrm            =  false;

                $curl_post_fields=array( 
                    'access_token'      =>  $access_token,
                    'name'              =>  $video_name,
                    'corsOrigin'        =>  $corsorigin,
                    'encryptHls'        =>  $encrypt,
                    'hlsKeysUrlPrefix'  =>  $hlsKeysUrlPrefix,
                    'debugDrm'          =>  $debugDrm,
                );

       
                $curl_response          =   $this->main->wpstream_live_connection->wpstream_baker_do_curl_base($url,$curl_post_fields);
                $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
       
                if($curl_response_decoded['success']){
                    set_transient(  $transient_name, $curl_response_decoded['hlsUrl'] ,300);
                    $hls_to_return =  $curl_response_decoded['hlsUrl'];
                    if( isset($curl_response_decoded['hlsDecryptionKey']) && isset($curl_response_decoded['hlsDecryptionKeyIndex']) ){
                        update_post_meta($product_id,'hlsDecryptionKey',$curl_response_decoded['hlsDecryptionKey']);
                        update_post_meta($product_id,'hlsDecryptionKeyIndex',$curl_response_decoded['hlsDecryptionKeyIndex']);
                    }else{
                        delete_post_meta($product_id,'hlsDecryptionKey');
                        delete_post_meta($product_id,'hlsDecryptionKeyIndex');
                    }
                    
                }else{
                    return '';
                }
               
            }
            
            return $hls_to_return;
                  
        }

        
        
        
        /**
        * VODPlayer uri details
        *
        * @author cretu
        */
        public function wpstream_video_on_demand_player_uri_request($product_id){
           
                $wpstream_data_setup    =   '  data-setup="{}" ';
                
                /* free_video_type
                 * 1 - free live channel
                 * 2 - free video encrypted
                 * 3 - free video -not encrypted
                 */
                
                $post_type              =   get_post_type($product_id);
                $free_video_type        =   intval( get_post_meta($product_id, 'wpstream_product_type', true));                
                if( (  $post_type  =='wpstream_product_vod' && $free_video_type==2 ) || get_post_type($product_id)=='product' ){
                    
                    /* 
                    * IF vide is encrypted-  readed from vod,streaner
                    *
                    */
                   
                    
                    $video_type         =   'application/x-mpegURL';
                    $video_path         =   get_post_meta($product_id,'_movie_url',true); 
                    if(get_post_type($product_id)=='wpstream_product_vod'){
                        $video_path =    esc_html(get_post_meta($product_id, 'wpstream_free_video', true));
                    }
                    $video_path_final = $this->wpstream_request_video_on_demand_hls_player($video_path,$product_id);

                    
                }else if(   $post_type =='wpstream_product_vod'  && $free_video_type==3 ){
                    
                    /* Video is unecrypted - read from local or youtube / vimeo
                    */
                    
                    $video_type         =   'video/mp4';
                    $video_path_final=esc_html(get_post_meta($product_id, 'wpstream_free_video_external', true));
                }
                
            $return_array                       =   array();
            $return_array['video_path_final']   =   $video_path_final;
            $return_array['wpstream_data_setup']=   $wpstream_data_setup;
            $return_array['video_type']         =   $video_type;
            $return_array['free_video_type']    =   $free_video_type;
            $return_array['post_type']          =   $post_type ;
            return $return_array;
 }
     
 
 
         /**
        * VODPlayer url
        *
        * @author cretu
        */

        public function wpstream_video_on_demand_player($product_id){
            
                    $uri_details        =   $this->wpstream_video_on_demand_player_uri_request($product_id);
                    $video_path_final   =   $uri_details['video_path_final'];
                    $wpstream_data_setup =  $uri_details['wpstream_data_setup'];
                    $video_type          =  $uri_details['video_type'];
                    $now                =   time().rand(0,1000000);
                    
                    $overlay_video_div_id = "random_id_".$now;
                    print '<div id="'.esc_attr($overlay_video_div_id).'" class="vjs-title-overlay wpstream-video-title-overlay">'.esc_html__('Playing:','wpstream').' '.get_the_title($product_id).'</div>';


                    $thumb_id               =   get_post_thumbnail_id($product_id);
                    $thumb                  =   wp_get_attachment_image_src($thumb_id,'small');
                    $usernamestream         =   esc_html ( get_option('wpstream_api_username','') );
                    
                    $poster_thumb           =   '';
                    if(isset($thumb[0])){
                        $poster_thumb=$thumb[0];
                    } 

                    $hlsDecryptionKey       =   get_post_meta($product_id,'hlsDecryptionKey',true);
                    $hlsDecryptionKeyIndex  =   get_post_meta($product_id,'hlsDecryptionKeyIndex',true);
                    

                    $pack = $this->main->wpstream_live_connection->wpstream_request_pack_data_per_user();
                    
                

                    $trailer_attachment_id    =  intval (get_post_meta( $product_id, 'video_trailer', true ));
                    $video_trailer            = '';
                    $video_trailer_type       = '';
                    if($trailer_attachment_id!=0) {
                        $video_trailer                 =   wp_get_attachment_url( $trailer_attachment_id );
                        $attachment_metadata           =   wp_get_attachment_metadata($trailer_attachment_id);
                        $video_trailer_type            =   $attachment_metadata['mime_type'];
                    }
                  
                    // override trailer setup here (for testing)
                    // $trailer_attachment_id = 1;
                    // $video_trailer = '/wp-content/uploads/2023/10/production-ID_4608975.mp4';
                    // $video_trailer = '/wp-content/uploads/2023/10/ultrawide.mp4';
                
                    
                    if(isset($pack['available_data_mb']) && $pack['available_data_mb']>0){
                        
                        if($video_path_final==''){
                            if( $uri_details['post_type']=='wpstream_product_vod'  && $uri_details['free_video_type']==3 ){
                            }else{
                                print '<div class="wpstream_vod_notice">This video does not exist or it has been deleted!</div>';
                            }
                           
                        }
                        
                        // TODO (crerem) populate these from VOD settings
                        $autoplay = false;
                        $muted = false;

                        $wpstream_vod_start_muted   =   intval ( get_option('wpstream_vod_start_muted','') );
                        if($wpstream_vod_start_muted===1){
                            $muted=true;
                        }
                        $wpstream_vod_autoplay      =   intval  ( get_option('wpstream_vod_autoplay','') );
                        if($wpstream_vod_autoplay===1){
                            $autoplay=true;
                        }

                        $poster_data = 'poster="'.esc_url($poster_thumb).'"';
                        $has_trailer_class='';
                        if($trailer_attachment_id !=0){
                            $poster_data=''; // cancel poster for theme
                            $has_trailer_class='wpstream_theme_player_has_trailer';
                        }

                        echo '<video id="wpstream-video-vod-'.$now.'" class="'.esc_attr($has_trailer_class).' video-js vjs-default-skin  vjs-fluid kuk wpstream_video_on_demand vjs-wpstream"  data-me="'.esc_attr($usernamestream).'" data-product-id="'.$product_id.'"  playsinline preload="auto"
                          '. $poster_data.' '.$wpstream_data_setup.'>
                                <p class="vjs-no-js">
                                  To view this video please enable JavaScript, and consider upgrading to a web browser that
                                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                </p>
                            </video>';
                            
                        if($trailer_attachment_id !=0){
                            print '<div class="wpstream_theme_trailer_wrapper">';
                            print '<div id="wpstream_video_on_demand_play_trailer_btn_' . $now . '" class="wpstream_video_on_demand_play_trailer">
                            <svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M26.6667 1.5H3.33337C2.50495 1.5 1.83337 2.17157 1.83337 3V21C1.83337 21.8284 2.50495 22.5 3.33338 22.5H26.6667C27.4951 22.5 28.1667 21.8284 28.1667 21V3C28.1667 2.17157 27.4951 1.5 26.6667 1.5ZM3.33337 0C1.67652 0 0.333374 1.34315 0.333374 3V21C0.333374 22.6569 1.67652 24 3.33338 24H26.6667C28.3236 24 29.6667 22.6569 29.6667 21V3C29.6667 1.34315 28.3236 0 26.6667 0H3.33337ZM4.83337 4C4.55723 4 4.33337 4.22386 4.33337 4.5V6.16667C4.33337 6.44281 4.55723 6.66667 4.83337 6.66667H6.50004C6.77618 6.66667 7.00004 6.44281 7.00004 6.16667V4.5C7.00004 4.22386 6.77618 4 6.50004 4H4.83337ZM23.5 4C23.2239 4 23 4.22386 23 4.5V6.16667C23 6.44281 23.2239 6.66667 23.5 6.66667H25.1667C25.4428 6.66667 25.6667 6.44281 25.6667 6.16667V4.5C25.6667 4.22386 25.4428 4 25.1667 4H23.5ZM4.33337 11.167C4.33337 10.8909 4.55723 10.667 4.83337 10.667H6.50004C6.77618 10.667 7.00004 10.8909 7.00004 11.167V12.8337C7.00004 13.1098 6.77618 13.3337 6.50004 13.3337H4.83337C4.55723 13.3337 4.33337 13.1098 4.33337 12.8337V11.167ZM23.5001 10.667C23.224 10.667 23.0001 10.8909 23.0001 11.167V12.8337C23.0001 13.1098 23.224 13.3337 23.5001 13.3337H25.1668C25.4429 13.3337 25.6668 13.1098 25.6668 12.8337V11.167C25.6668 10.8909 25.4429 10.667 25.1668 10.667H23.5001ZM4.33337 17.833C4.33337 17.5569 4.55723 17.333 4.83337 17.333H6.50004C6.77618 17.333 7.00004 17.5569 7.00004 17.833V19.4997C7.00004 19.7758 6.77618 19.9997 6.50004 19.9997H4.83337C4.55723 19.9997 4.33337 19.7758 4.33337 19.4997V17.833ZM23.5001 17.333C23.224 17.333 23.0001 17.5569 23.0001 17.833V19.4997C23.0001 19.7758 23.224 19.9997 23.5001 19.9997H25.1668C25.4429 19.9997 25.6668 19.7758 25.6668 19.4997V17.833C25.6668 17.5569 25.4429 17.333 25.1668 17.333H23.5001ZM19.0677 13.0997L13.4077 16.5087C13.0434 16.7281 12.6092 16.7094 12.2661 16.5091C11.9218 16.3081 11.6666 15.9224 11.6666 15.4086V8.59072C11.6666 8.07698 11.9218 7.69125 12.2661 7.49026C12.6092 7.28999 13.0434 7.27126 13.4077 7.49064L19.0677 10.8996C19.8663 11.3805 19.8663 12.6188 19.0677 13.0997Z"/>
                            </svg>
                            '.esc_html('Play Trailer','wpstream').'</div>';

                            print '<div class="wpstream_video_on_demand_play_video_wrapper" id="wpstream_video_on_demand_play_video_btn_' . $now . '" >
                            <div class="wpstream_video_on_demand_play_video">
                                <svg width="29" height="30" viewBox="0 0 29 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.1808 28.9035L26.274 18.1652C29.1087 16.6503 29.1087 12.7497 26.274 11.2348L6.1808 0.496557C4.88769 -0.194506 3.34623 -0.1355 2.1283 0.495357C0.906043 1.12846 1.0095e-06 2.34351 9.38766e-07 3.96179L0 25.4382C-7.07369e-08 27.0565 0.906042 28.2715 2.1283 28.9046C3.34622 29.5355 4.88769 29.5945 6.1808 28.9035ZM24.8221 13.8026C25.5742 14.2045 25.5742 15.1955 24.8221 15.5974L4.72891 26.3356C3.94628 26.7539 3.01386 26.2165 3.01386 25.4382L3.01386 3.96179C3.01386 3.18347 3.94628 2.6461 4.72891 3.06436L24.8221 13.8026Z" fill="#F1F1F1"/>
                                </svg>
                            </div>
                            '.esc_html('Play Video','wpstream').'
                            </div>';



                            print '<div id="wpstream_video_on_demand_mute_trailer_btn_' . $now . '" class="wpstream_video_on_demand_mute_trailer">
                          
                            <svg width="37" height="36" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.32143 10.0789H8.69499L18.8964 0L21.1428 0.921053V35.1316L18.8964 36L8.69499 25.8684H1.32143L0 24.5526V11.3947L1.32143 10.0789ZM10.175 23.6842L18.5 31.9474V4.10526L10.175 12.3158L9.24999 12.7105H2.64286V23.2368H9.24999L10.175 23.6842ZM37 17.9737C37.0069 22.2216 35.5329 26.3401 32.8295 29.6263L30.9478 27.7579C33.1613 24.9734 34.3629 21.5249 34.3571 17.9737C34.3571 14.2895 33.0885 10.8974 30.9637 8.21053L32.8454 6.34211C35.5382 9.62494 37.0062 13.735 37 17.9737ZM31.7143 17.9737C31.7193 20.8255 30.7895 23.6011 29.0661 25.8789L27.1738 23.9947C28.4127 22.2295 29.0752 20.1272 29.0714 17.9737C29.0751 15.8287 28.4174 13.7344 27.1871 11.9737L29.0793 10.0895C30.7338 12.2868 31.7143 15.0158 31.7143 17.9737ZM26.4286 17.9737C26.4286 19.4842 26.0057 20.8947 25.2657 22.0947L23.3126 20.1526C23.6249 19.4729 23.7876 18.7345 23.7899 17.9869C23.7922 17.2394 23.634 16.5001 23.3258 15.8184L25.2789 13.8737C26.0083 15.0684 26.4286 16.4737 26.4286 17.9737Z" fill="white"/>
                            </svg>
                            </div>';
                            print '<div id="wpstream_video_on_demand_unmute_trailer_btn_' . $now . '" class="wpstream_video_on_demand_unmute_trailer">
                            <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.15625 8.85688H7.60813L16.5344 0L18.5 0.809375V30.8719L16.5344 31.635L7.60813 22.7319H1.15625L0 21.5756V10.0131L1.15625 8.85688ZM8.90313 20.8125L16.1875 28.0738V3.6075L8.90313 10.8225L8.09375 11.1694H2.3125V20.4194H8.09375L8.90313 20.8125ZM30.5967 11.3127L32.2316 12.9477L28.2287 16.9506L32.2316 20.9559L30.5967 22.5908L26.5938 18.5856L22.5885 22.5908L20.9536 20.9559L24.9588 16.9506L20.9513 12.95L22.5862 11.3151L26.5938 15.3157L30.5967 11.3127Z" fill="white"/>
                            </svg>

                            </div>';
                            print '<script type="text/javascript">
                            //<![CDATA[
                                jQuery(document).ready(function(){
                                    console.log("wpstream_video_on_demand_player 886");
                                    wpstream_player_initialize_vod({
                                        titleOverlayElementId:"'.$overlay_video_div_id.'",
                                        videoElementId: "wpstream-video-vod-'.$now.'",
                                        trailerUrl: "'.$video_trailer.'",
                                        videoUrl: "'.$video_path_final.'",
                                        autoplay: '.var_export($autoplay, true).',
                                        muted: '.var_export($muted, true).',
                                        playTrailerButtonElementId: "wpstream_video_on_demand_play_trailer_btn_'.$now.'",
                                        muteTrailerButtonElementId: "wpstream_video_on_demand_mute_trailer_btn_'.$now.'",
                                        unmuteTrailerButtonElementId: "wpstream_video_on_demand_unmute_trailer_btn_'.$now.'",
                                        playVideoButtonElementId: "wpstream_video_on_demand_play_video_btn_'.$now.'",
                                    });
                                });
                            //]]>
                            </script>';
                            print '</div>';
                        }
                        else {
                            print '<script type="text/javascript">
                            //<![CDATA[
                                jQuery(document).ready(function(){
                                  
                                console.log("wpstream_video_on_demand_player 907");

                                    wpstream_player_initialize_vod({
                                        titleOverlayElementId:"'.$overlay_video_div_id.'",
                                        videoElementId: "wpstream-video-vod-'.$now.'",
                                        videoUrl: "'.$video_path_final.'",
                                        autoplay: '.var_export($autoplay, true).',
                                        muted: '.var_export($muted, true).',
                                    });
                                });
                            //]]>
                            </script>';
                        }    
                    }else{
                        print '<div class="wpstream_insuficent_res">'.esc_html__('Insufficient resources to stream this title','wpstream').'</div>';
                    }

        }


        
        /**
        * VODPlayer url - only trailer - used in theme
        *
        * @author cretu
        */
        public function wpstream_video_on_demand_player_only_trailer($product_id){
            
            $now                =   time().rand(0,1000000);
            $overlay_video_div_id = "random_id_".$now;
            print '<div id="'.esc_attr($overlay_video_div_id).'" class="vjs-title-overlay wpstream-video-title-overlay">'.esc_html__('Playing:','wpstream').' '.get_the_title($product_id).'</div>';


       
            $thumb_id               =   get_post_thumbnail_id($product_id);
            $thumb                  =   wp_get_attachment_image_src($thumb_id,'small');
            $usernamestream         =   esc_html ( get_option('wpstream_api_username','') );

            $poster_thumb           =   '';
            if(isset($thumb[0])){
                $poster_thumb=$thumb[0];
            } 


            $trailer_attachment_id    =  intval (get_post_meta( $product_id, 'video_trailer', true ));
            $video_trailer            = '';
            $video_trailer_type       = '';
            if($trailer_attachment_id!=0) {
                $video_trailer                 =   wp_get_attachment_url( $trailer_attachment_id );
                $attachment_metadata           =   wp_get_attachment_metadata($trailer_attachment_id);
                if(isset($attachment_metadata['mime_type'])){
                    $video_trailer_type            =   $attachment_metadata['mime_type'];
                }
              
            }
         
                     
            // TODO (crerem) populate these from VOD settings
            $autoplay = false;
            $muted = true;
            $video_path_final='';
            $has_trailer_class='wpstream_theme_player_has_trailer';

            echo '<video id="wpstream-video-vod-'.$now.'" class="video-js vjs-default-skin  vjs-fluid kuk wpstream_video_on_demand vjs-wpstream '.esc_attr(  $has_trailer_class ).' "  data-me="'.esc_attr($usernamestream).'" data-product-id="'.$product_id.'"  playsinline preload="auto"
                   >
                    <p class="vjs-no-js">
                      To view this video please enable JavaScript, and consider upgrading to a web browser that
                      <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                    </p>
                </video>';
                            
            if($trailer_attachment_id !=0){
                print '<div class="wpstream_theme_trailer_wrapper">';
                print '<div id="wpstream_video_on_demand_play_trailer_btn_' . $now . '" class="wpstream_video_on_demand_play_trailer">
                     <svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M26.6667 1.5H3.33337C2.50495 1.5 1.83337 2.17157 1.83337 3V21C1.83337 21.8284 2.50495 22.5 3.33338 22.5H26.6667C27.4951 22.5 28.1667 21.8284 28.1667 21V3C28.1667 2.17157 27.4951 1.5 26.6667 1.5ZM3.33337 0C1.67652 0 0.333374 1.34315 0.333374 3V21C0.333374 22.6569 1.67652 24 3.33338 24H26.6667C28.3236 24 29.6667 22.6569 29.6667 21V3C29.6667 1.34315 28.3236 0 26.6667 0H3.33337ZM4.83337 4C4.55723 4 4.33337 4.22386 4.33337 4.5V6.16667C4.33337 6.44281 4.55723 6.66667 4.83337 6.66667H6.50004C6.77618 6.66667 7.00004 6.44281 7.00004 6.16667V4.5C7.00004 4.22386 6.77618 4 6.50004 4H4.83337ZM23.5 4C23.2239 4 23 4.22386 23 4.5V6.16667C23 6.44281 23.2239 6.66667 23.5 6.66667H25.1667C25.4428 6.66667 25.6667 6.44281 25.6667 6.16667V4.5C25.6667 4.22386 25.4428 4 25.1667 4H23.5ZM4.33337 11.167C4.33337 10.8909 4.55723 10.667 4.83337 10.667H6.50004C6.77618 10.667 7.00004 10.8909 7.00004 11.167V12.8337C7.00004 13.1098 6.77618 13.3337 6.50004 13.3337H4.83337C4.55723 13.3337 4.33337 13.1098 4.33337 12.8337V11.167ZM23.5001 10.667C23.224 10.667 23.0001 10.8909 23.0001 11.167V12.8337C23.0001 13.1098 23.224 13.3337 23.5001 13.3337H25.1668C25.4429 13.3337 25.6668 13.1098 25.6668 12.8337V11.167C25.6668 10.8909 25.4429 10.667 25.1668 10.667H23.5001ZM4.33337 17.833C4.33337 17.5569 4.55723 17.333 4.83337 17.333H6.50004C6.77618 17.333 7.00004 17.5569 7.00004 17.833V19.4997C7.00004 19.7758 6.77618 19.9997 6.50004 19.9997H4.83337C4.55723 19.9997 4.33337 19.7758 4.33337 19.4997V17.833ZM23.5001 17.333C23.224 17.333 23.0001 17.5569 23.0001 17.833V19.4997C23.0001 19.7758 23.224 19.9997 23.5001 19.9997H25.1668C25.4429 19.9997 25.6668 19.7758 25.6668 19.4997V17.833C25.6668 17.5569 25.4429 17.333 25.1668 17.333H23.5001ZM19.0677 13.0997L13.4077 16.5087C13.0434 16.7281 12.6092 16.7094 12.2661 16.5091C11.9218 16.3081 11.6666 15.9224 11.6666 15.4086V8.59072C11.6666 8.07698 11.9218 7.69125 12.2661 7.49026C12.6092 7.28999 13.0434 7.27126 13.4077 7.49064L19.0677 10.8996C19.8663 11.3805 19.8663 12.6188 19.0677 13.0997Z"/>
                            </svg>
                            '.esc_html('Play Trailer','wpstream').'
                </div>';
                print '<div id="wpstream_video_on_demand_mute_trailer_btn_' . $now . '" class="wpstream_video_on_demand_mute_trailer">
                <svg width="37" height="36" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.32143 10.0789H8.69499L18.8964 0L21.1428 0.921053V35.1316L18.8964 36L8.69499 25.8684H1.32143L0 24.5526V11.3947L1.32143 10.0789ZM10.175 23.6842L18.5 31.9474V4.10526L10.175 12.3158L9.24999 12.7105H2.64286V23.2368H9.24999L10.175 23.6842ZM37 17.9737C37.0069 22.2216 35.5329 26.3401 32.8295 29.6263L30.9478 27.7579C33.1613 24.9734 34.3629 21.5249 34.3571 17.9737C34.3571 14.2895 33.0885 10.8974 30.9637 8.21053L32.8454 6.34211C35.5382 9.62494 37.0062 13.735 37 17.9737ZM31.7143 17.9737C31.7193 20.8255 30.7895 23.6011 29.0661 25.8789L27.1738 23.9947C28.4127 22.2295 29.0752 20.1272 29.0714 17.9737C29.0751 15.8287 28.4174 13.7344 27.1871 11.9737L29.0793 10.0895C30.7338 12.2868 31.7143 15.0158 31.7143 17.9737ZM26.4286 17.9737C26.4286 19.4842 26.0057 20.8947 25.2657 22.0947L23.3126 20.1526C23.6249 19.4729 23.7876 18.7345 23.7899 17.9869C23.7922 17.2394 23.634 16.5001 23.3258 15.8184L25.2789 13.8737C26.0083 15.0684 26.4286 16.4737 26.4286 17.9737Z" fill="white"/>
                </svg>

          
                </div>';
                print '<div id="wpstream_video_on_demand_unmute_trailer_btn_' . $now . '" class="wpstream_video_on_demand_unmute_trailer">
                <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.15625 8.85688H7.60813L16.5344 0L18.5 0.809375V30.8719L16.5344 31.635L7.60813 22.7319H1.15625L0 21.5756V10.0131L1.15625 8.85688ZM8.90313 20.8125L16.1875 28.0738V3.6075L8.90313 10.8225L8.09375 11.1694H2.3125V20.4194H8.09375L8.90313 20.8125ZM30.5967 11.3127L32.2316 12.9477L28.2287 16.9506L32.2316 20.9559L30.5967 22.5908L26.5938 18.5856L22.5885 22.5908L20.9536 20.9559L24.9588 16.9506L20.9513 12.95L22.5862 11.3151L26.5938 15.3157L30.5967 11.3127Z" fill="white"/>
                </svg>

                </div>';
                print '<script type="text/javascript">
                //<![CDATA[
                    jQuery(document).ready(function(){

                      console.log("wpstream_video_on_demand_player trailer  995");
                    
                        wpstream_player_initialize_vod({
                            titleOverlayElementId:"'.$overlay_video_div_id.'",
                            videoElementId: "wpstream-video-vod-'.$now.'",
                            trailerUrl: "'.$video_trailer.'",
                            autoplay: '.var_export($autoplay, true).',
                            muted: '.var_export($muted, true).',
                            playTrailerButtonElementId: "wpstream_video_on_demand_play_trailer_btn_'.$now.'",
                            muteTrailerButtonElementId: "wpstream_video_on_demand_mute_trailer_btn_'.$now.'",
                            unmuteTrailerButtonElementId: "wpstream_video_on_demand_unmute_trailer_btn_'.$now.'",
                        });
                    });
                //]]>
                </script>';
                print '</div>'; 
            }
            else {
                //just show the poster or don't show anything; no player needed
            }    
                  
        }

        
        
        /**
         * 
         * edited 4.0
         * 
        * Retreive username for vod path
        *
        * @author cretu
        */
        private function wpstream_retrive_username(){
            
            return  get_option('wpstream_api_username_from_token');
        }
        
    /**
     * check if the we can add display the player
     *
     * @since     3.12
    * returns html of the player
    */
        
    public function wpstream_check_if_player_can_dsplay($product_id){
        if ( is_user_logged_in() ) {
            
            $term_list              =       wp_get_post_terms($product_id, 'product_type');
            $current_user           =       wp_get_current_user();
            $subscription_model     =       intval( get_option('wpstream_global_sub','')) ;
       
            $product = wc_get_product($product_id);
            $product_type = $product->get_type();
     
        
        if($subscription_model==1){ // if we have Neflix mode               
                if( $product_type=='subscription' ){ // if the product loaded is a subscription and we are on netflix mode
                    return false;
                }
                if($this->wpstream_in_plugin_check_global_subscription_model($product_id)){
                    return true;
                }
            }else{
                // ppv mode
                
                if( $product_type=='subscription' ){
                    
//                        $user_subscriptions = wcs_get_users_subscriptions($current_user->ID );
//                        foreach ($user_subscriptions as $subscription) {
//                                $subscription_status = $subscription->get_status();
//                                echo "</br>*** Subscription ID $subscription->ID for User ID $current_user->ID has status: $subscription_status";
//                        }

        
                    if( wcs_user_has_subscription( $current_user->ID, $product_id ,'active') ) {
                        // user has active subcription 
                        return true;
                    }
                
                }else{
                    if( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id)){
                        return true;
                    }
                }
                
              
            }
            return false;
           
        }
        return false;
        
    }
    
        

    /**
    * check if the we can add display the player - theme variant
    *
    * @since     3.12
    * returns html of the player
    */
        public function wpstream_check_if_player_can_dsplay_theme($product_id){
            
            $post_type= get_post_type($product_id);
            

    
            if($post_type=='wpstream_product_vod' || $post_type=='wpstream_product'){
                return true; // if we have free vod or live
            }



            if ( is_user_logged_in() && $post_type==='product' ) {
               
                $product            = wc_get_product( $product_id );
                $product_type       = $product->get_type();
                $possible_bundle    = get_post_meta($product_id, 'wpstream_part_of_bundle',true);
 

               
              

                $current_user           =       wp_get_current_user();
                $subscription_model     =       intval( get_option('wpstream_global_sub','')) ;
              //  print 'subscription model '.$subscription_model.'</br>';
               
                if($subscription_model==1){ // if we have Neflix mode               
                    if( $product_type=='subscription' ){ // if the product loaded is a subscription and we are on netflix mode
                        return false;
                    }
                    if($this->wpstream_in_plugin_check_global_subscription_model($product_id)){
                        return true;
                    }
                }else{
                    // ppv mode
                    if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ){
                        return true; //simple product bought
                    }else if (function_exists('wcs_user_has_subscription')  && wcs_user_has_subscription( $current_user->ID, $product_id ,'active') ){
                        return true; // subscription boght
                    }else if( get_post_type($product_id) =='product' && 
                            intval($possible_bundle )!=0 &&
                            wc_customer_bought_product( $current_user->user_email, $current_user->ID, $possible_bundle)
                            ){
                        return true; // part of a boght bundle
                    }
                }
               // print ' fac return false </br>';
                return false;
               
            }
            return false;
            
        }
        
        
        /**
     * in plugin - check if global subscription model is enabled
     *
     * @since     3.12
         * 
    */
        public   function wpstream_in_plugin_check_global_subscription_model($product_id) { 
        
            //  $selected_sub= get_post_meta($post->ID,'_wpstream_parent_sub',true);
            if( is_user_logged_in()  && function_exists('wcs_user_has_subscription') ){
      
                global $woocommerce;
                $current_user   =       wp_get_current_user(); 
        
                $subscription_model =   intval( get_option('wpstream_global_sub','')) ;
                $main_subscription  =   intval(  get_option('wpstream_global_sub_id',''));
        
                if($subscription_model==1){
                    $selected_sub=  get_post_meta($product_id,'_wpstream_parent_sub',true)  ;
                    
                    if( is_array($selected_sub) ){
                 
                        // we have per product sub
                        foreach($selected_sub as $key=>$subscrition_id ):
                            if( wcs_user_has_subscription( $current_user->ID, $subscrition_id ,'active') ) {
                                return true;
                            }
                        endforeach;
                        
                    } else if($main_subscription!=0){ 
                        
                        // if we have one main subscription
                        if( wcs_user_has_subscription( $current_user->ID, $main_subscription ,'active') ) {
                            return true;
                        }
                    }
                    
                    
                }
                return false;
        
            }
            // there is no woo subscription or user not logged in
            return false;
        }
        
        
        
        
        
        
        
        
        
        /**
         * 
         * 
         * 
         * 
     * check if the user bought the product and display the player - TO REDo
     *
     * @since     3.0.1
         * returns html of the player
         * 
         * 
         * 
         * 
         * 
    */
          public function wpstream_user_logged_in_product_already_bought($from_sh_id='') {
            if(function_exists('remove_wpstream_filter')){
                return;
            }
            global $product;
            $product_id     =       $product->get_id();
            $current_user           =       wp_get_current_user();

            if ( is_user_logged_in() ) {
                
           
                if($this->wpstream_check_if_player_can_dsplay($product_id) ){
                    
                    echo '<div class="wpstream_player_wrapper "><div class="wpstream_player_container">';

                    $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));
                    $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                   

                    if( $term_list[0]->name=='live_stream' || ($term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' )  ){
                        $this->wpstream_live_event_player($product_id);
                    }else if( $term_list[0]->name=='video_on_demand'  || ($term_list[0]->name=='subscription' && $is_subscription_live_event=='no' ) ){
                        $this->wpstream_video_on_demand_player($product_id);
                    }
                    echo '</div></div>';
                }else{
      
                    
                    $term_list                  =   wp_get_post_terms($product_id, 'product_type');
              
                    if( $term_list[0]->name=='subscription' ){
              
                        if( !wcs_user_has_subscription( $current_user->ID, $product_id ,'active') ) {
                            $this->wpstream_display_no_buy_message('nobuy',$product_id);
                        }
                             
                    }else{
                        $this->wpstream_display_no_buy_message('nobuy',$product_id);
                    }
                      
                
                    
                }

               
            }else{
               
                $this->wpstream_display_no_buy_message('nolog',$product_id);
            }
        }
        
        
        
        
        
        /**
         *
         *   
     * check if the user bought the product and display the player - TO REDo
     *
     * @since     3.0.1
         * returns html of the player
    */
        
        public function wpstream_chat_wrapper($product_id){
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/wpstream_chat_template.php';
        }

        
        
        
        
        
        
        
          /**
     * coonect to chat
     *
     * @since     1.12.2
         * 
    */
        
        
        
        public  function wpstream_connect_to_chat($product_id){
        $current_user           =   wp_get_current_user();
        $userID                 =   $current_user->ID;
        $user_login             =   $current_user->user_login;

        $key='';

        $chat_url                =   get_post_meta($product_id,'chat_url',true);
               

        wp_enqueue_script( 'sockjs-0.3.min' );
        wp_enqueue_script( 'emojione.min.js' );
        wp_enqueue_script( "jquery-effects-core");
        wp_enqueue_script( 'jquery.linkify.min.js');
        wp_enqueue_script( 'ripples.min.js');
        wp_enqueue_script( 'material.min.js');
        wp_enqueue_script( 'chat.js');



        wp_enqueue_style( 'chat.css');
        wp_enqueue_style( 'ripples.css');
        wp_enqueue_style( 'emojione.min.css');


        
       
       if(!is_user_logged_in()){
           $user_login='';
           $chat_url='';
       }

       
       print '<script type="text/javascript">
            //<![CDATA[
                jQuery(document).ready(function(){
                    username = "'.$user_login.'";
                    key="'.$key.'";
                   
                });
            //]]>
        </script>';
      
    }
    
     
        /**
     * display no buy Message
     *
     * @since     3.12.2
         * 
    */
        public function wpstream_display_no_buy_message($log,$product_id) {
      
            if($log=='sub_active'){
                $message= esc_html( get_option('wpstream_subscription_active','Your Subscription is Active.')) ;    
                echo '<div class="wpstream_player_wrapper no_buy"><div class="wpstream_player_container">';
                echo '<div class="wpstream_notice"> '.$message.'</div>';
                echo '</div></div>';
                return;
                
            }else if($log=='nolog'){
               $message= esc_html( get_option('wpstream_product_not_login','You must be logged in to watch this video.')) ;
            }else{
                $message =esc_html( get_option('wpstream_product_not_bought','You did not yet purchase this item.')) ;
            }
            $subscription_model     =       intval( get_option('wpstream_global_sub','')) ;
            if($subscription_model==1){
                $message =esc_html( get_option('wpstream_product_not_subscribe','You did not yet subscribe to this item.'));
            }
            
            
            if( get_post_type($product_id) == 'product' && $subscription_model==0 ){
                $product                    =   wc_get_product($product_id);
                $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                $product_type               =   $product->get_type();
                $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));

                

                if( $term_list[0]->name=='video_on_demand' ||  $term_list[0]->name=='live_stream' || $product_type=='subscription'){
                    
                    echo '<div class="wpstream_player_wrapper no_buy"><div class="wpstream_player_container">';
                    echo '<div class="wpstream_notice">'.$message.'</div>';
                    echo '</div></div>';
                        
                }
                        
            }else  if( get_post_type($product_id) == 'product' && $subscription_model==1 ){
                $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                if( $term_list[0]->name!=='simple'){
                    echo '<div class="wpstream_player_wrapper no_buy"><div class="wpstream_player_container">';
                    echo '<div class="wpstream_notice">  '.$message.'</div>';
                    echo '</div></div>';
                }
            }
            
            
            

                    
                    
        }
   
    
}