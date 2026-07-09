<?php

class Wpstream_Live_Api_Connection  {

    

    
    public function __construct() {
        add_action( 'wp_ajax_wpstream_give_me_live_uri', array($this,'wpstream_give_me_live_uri') );  
        add_action( 'wp_ajax_wpstream_turn_of_channel',  array($this,'wpstream_turn_of_channel') );  
        add_action( 'wp_ajax_wpstream_update_local_event_settings',array($this,'wpstream_update_local_event_settings'));
        
        add_action( 'wp_ajax_wpstream_check_dns_sync', array($this,'wpstream_check_dns_sync') );
        add_action( 'wp_ajax_wpstream_check_event_status', array($this,'wpstream_check_event_status') );
        
        add_action( 'wp_ajax_wpstream_close_event', array($this,'wpstream_close_event') );
        add_action( 'wp_ajax_wpstream_get_download_link', array($this,'wpstream_get_download_link') );  
        add_action( 'wp_ajax_wpstream_get_delete_file', array($this,'wpstream_get_delete_file') ); 
        
        add_action( 'admin_notices',array($this, 'wpstream_admin_notices') );

      
    }
    
    



    
    /*
     * Admin Notices
     * 
     * 
     * 
     * */
    function wpstream_admin_notices(){
        global $pagenow;
       

       

        if($pagenow!='admin.php'){
            return;
        }

        $permited_pages=array('wpstream_plugin_options','wpstream_live_channels','wpstream_recordings','wpstream_settings');
        if (!empty($_GET['page'])) {
            $page =  esc_html($_GET['page']) ;
            if( !in_array($page, $permited_pages)){
               return;
            }
        }

        if(in_array('curl', get_loaded_extensions())){
            //cURL module has been loaded
        } else{
            print '<div class="api_not_conected wpstream_notice_top">We could not connect to WpStream.net. Make sure you have the php Curl library enabled and your hosting allows  outgoing HTTP Connection. </div>';
        }
    
        $token          =   $this->wpstream_get_token();  
        if($token=='' and $page!='wpstream_plugin_options'){
            // echo 'wpstream_curl_failed: ' . get_option('wpstream_curl_failed');
            $text = get_option('wpstream_curl_failed') == false ?
                'Not connected to WpStream. Please check your credentials <a href="/wp-admin/admin.php?page=wpstream_credentials">here</a>.' :
                'Not connected to WpStream. Please note the errors above and contact support.';

            echo '<div class="api_not_conected wpstream_notice_top">'.__($text,'wpstream').'</div>';
        }
              
}
    
    
    /*
     * Curl request 
     * 
     * 
     * 
     * */

    function wpstream_baker_do_curl_base($url,$curl_post_fields, $expect_json = false, $quiet = false){
        $curl       =   curl_init();
        $api_url    =   WPSTREAM_API.'/'.$url;

        curl_setopt_array($curl, array(
          CURLOPT_URL =>$api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 10,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => http_build_query($curl_post_fields),
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
          ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $curl_failed = false;

        if ($err) {
            // do not echo every time, some operations must return JSON
            if (!$quiet){
                echo '<div class="api_not_conected wpstream_error_curl">Critical: Could Not Connect to WpStream - '.$err.'</div>';
            }

            $curl_failed = $err;

            $response = json_encode(   array(
                'success'      =>  false,
                'error'        =>  $err,
            ));
        }
        else if ($http_code != 200) {
            if (!$quiet){
                switch ($http_code) {
                    case 429:
                        $message = "API: Too many Requests";
                        break;
                    default:
                        $message = "API - Unexpected response: " . $http_code;
                        break;
                }
                echo '<div class="api_not_conected wpstream_error_curl">'.$message.'</div>';
            }
            $curl_failed = $http_code;

            $response = json_encode(   array(
                'success'      =>  false,
                'error'        =>  $http_code,
            ));
        }
        else if ($expect_json){
            $curl_response_decoded  =   json_decode($response,JSON_OBJECT_AS_ARRAY);
            if (JSON_ERROR_NONE !== json_last_error()) {
                if (!$quiet){
                    echo '<div class="api_not_conected wpstream_error_curl">Critical: Malformed API response #: ' . json_last_error() . '</div>';
                }

                $curl_failed = json_last_error();

                $response = json_encode(   array(
                    'success'      =>  false,
                    'error'        =>  json_last_error(),
                ));
            }
        }

        update_option("wpstream_curl_failed", $curl_failed);

        return $response;
    }



 



    /**
     * retreive server id based on show id
     *
     * @since    3.0.1
     * returns live url
    */
    
    function retrive_server_id_based_on_show_id($show_id){
        
            $transient_name = 'server_id_to_return_'.$show_id;
            $server_id_to_return = get_transient( $transient_name );
          
            if ( false ===  $server_id_to_return  ) {
                $token  = $this->wpstream_get_token();
                $values_array=array(
                    "show_id"           =>  intval($show_id),
                );
                $values_array=array();
                $show_id=intval($show_id);
                
                $url="https://rest-baker.wpstream.net/?&apiFunctionName=server_id_by_show_id&show_id=".$show_id."&access_token=".$token;
                $arguments = array(
                    'method'        => 'GET',
                    'timeout'       => 45,
                    'redirection'   => 5,
                    'httpversion'   => '1.0',
                    'blocking'      => true,
                    'headers'       => array(),
                    'body'          => $values_array,
                    'cookies'       => array()
                );

                $response       = wp_remote_post($url,$arguments);

                $received_data  =  wp_remote_retrieve_body($response);

                $received_data_decoded=json_decode($received_data);
     

                if( isset($response['response']['code']) && $response['response']['code']=='200' && $received_data_decoded->success===true && $received_data_decoded->result!=''){
                    $server_id_to_return = $received_data_decoded->result;
                    set_transient( $transient_name, json_decode($server_id_to_return), 60 );
                    return $server_id_to_return;
              
                }else{     
                    return '';
                    
                }
            }else{
                return $server_id_to_return;
            }
            
            die();
    }
    
    
    
    /**
     *  edited 4.0
     * 
     * 
     * 
     * check event status from start stremaing process
     *
     * @since    3.0.1
     * returns live url
    */
    
    public  function wpstream_check_event_status(){
            $channel_id         =   intval($_POST['channel_id']);   
            $notes              =   'wpstream_check_event_status_note';
            if(isset($_POST['notes'])){
                $notes = sanitize_text_field($_POST['notes']);
            }


            $response           =   $this->wpstream_check_event_status_api_call($channel_id,$notes);
       
       

            if( isset($response['success']) && $response['success']){
                $this->api20_wpstream_update_event($response,$channel_id);
                
                if( isset($response['broadcast_url']) && isset($response['status']) && $response['status']==='active' ){
                    
                  
                    $response['live_data_url'] =    $response['qos_url'];
                
                    /* obsolote due to new url format
                    $local_event_options = get_post_meta ($channel_id,'local_event_options',true);
                    if( is_array( $local_event_options ) && intval( $local_event_options['autostart']) ==1 ){
                        $to_split=explode('/',$response['broadcast_url']);
                        $obs_stream = array_pop($to_split);;
                        $obs_uri    = str_replace($obs_stream,'',$response['broadcast_url']);    
                    }else{
                        $to_split=explode('wpstream/',$response['broadcast_url']);
                        $obs_uri = $to_split[0].'wpstream/';
                        $obs_stream = $to_split[1];
                    }
                    */


                    $to_split   =   explode('/',$response['broadcast_url']);
                    $obs_stream =   array_pop($to_split);;
                    $obs_uri    =   str_replace($obs_stream,'',$response['broadcast_url']);   

                    $response['obs_uri']       =    $obs_uri;
                    $response['obs_stream']    =    $obs_stream;

                    update_post_meta($channel_id,'obs_uri',$obs_uri);
                    update_post_meta($channel_id,'obs_stream',$obs_stream);   
                    update_post_meta($channel_id,'broadcast_url',$response['broadcast_url']);

                }
                
              
            }
            
            print json_encode($response);
            die();
                  
    }
    
    /**
     * edited 4.0
     * 
     * check event status from API
     *
     * @since    3.0.1
     * returns live url
    */
    
    
    public  function wpstream_check_event_status_api_call($channel_id,$notes){
        
        $access_token   =   $this->wpstream_get_token();
        $domain         =   parse_url ( get_site_url() );
        $url            =   'channel/info';
       
        // do not make the call if no token is available
        if (!$access_token) return false;

        $curl_post_fields=array( 
            'access_token'  =>  $access_token,
            'channel_id'    =>  $channel_id,
            'domain'        =>  $domain['host'],
            'notes'         =>  $notes
        );
            
       
       
            
          
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
    
        
        $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
        
        return $curl_response_decoded;
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
   
    public function wpstream_reset_event_data($event_id){
        update_post_meta($event_id,'stats_url','');
        update_post_meta($event_id,'hls_playback_url','');
        update_post_meta($event_id,'server_id', '' );
    }
    
    /**
     * edited 4.0
     * update event metadata
     *
     * @since    3.0.1
     * returns live url
    */
    
    
      
    function api20_wpstream_update_event($response,$channel_id){

        if( is_array($response) )  {
            $event_data_for_transient               =   array();
            $transient_name                         =   'event_data_to_return_'.$channel_id;
               
            foreach($response as $key=>$value){
                update_post_meta($channel_id,$key,$value);
                $event_data_for_transient[$key]=$value;
            }
            set_transient($transient_name,$event_data_for_transient,45);
            return $event_data_for_transient;
        }else{
            return false;
        }
        

    }
            
    /**
     * Update event settings
     *
     * @since    3.0.1
     * returns live url
    */
    
    
    public function wpstream_update_local_event_settings(){ 
       
        check_ajax_referer( 'wpstream_start_event_nonce', 'security' );
        if(!is_user_logged_in()){
            exit('not logged in');
        }
        if( !current_user_can('administrator') ){
            exit('not admin');
        }
        
        $show_id        =   intval($_POST['show_id']);
        $option_array   =   $_POST['option'];
        
        $to_save_option=array();
        foreach($option_array as $key=>$value){
            $to_save_option[sanitize_key($key)]=sanitize_text_field($value);
        }

        if(   $to_save_option['low_latency'] == 1 ||   $to_save_option['adaptive_bitrate'] ==1 ){
            $to_save_option['encrypt']=0;
        }

        if(  $to_save_option['encrypt']==1){
            $to_save_option['low_latency'] = 0;
            $to_save_option['adaptive_bitrate'] =0;
        }


     
        update_post_meta ($show_id,'local_event_options',$to_save_option);
        $this->wpstream_update_chanel_on_baker($show_id,$to_save_option);
     
    }
    
  

 /**
     * Update channel settings on baker
     *
     * @since    4.2
     * returns live url
    */
    

    public function wpstream_update_chanel_on_baker($channel_id,$to_save_option){

        $access_token   =   $this->wpstream_get_token();
        if($access_token==''){
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                    'is_record'     =>  '',
                    'conected'      =>  false,
                    'event_data'    =>  '',
                    'error'         =>  esc_html('You are not connected to wpstream.net! Please check your WpStream credentials!','wpstream'),
                ));
            exit();
        }
        $current_user       =   wp_get_current_user();
        $userID             =   $current_user->ID;
    


        $url            =   '/channel/update';
        $local_event_options =   get_post_meta($channel_id,'local_event_options',true);
     
        $domain         = parse_url ( get_site_url() );
        $domain_scheme  =   'http';
        if(is_ssl()){
            $domain_scheme='https';
        }
        
        $domain_ip= esc_html( $_SERVER['SERVER_ADDR'] );
        if($domain_ip==''){
            $domain_ip="0.0.0.0/0";
        }
        
        $corsorigin='*';
        if( isset($local_event_options['domain_lock']) && intval( $local_event_options['domain_lock']) ==0 ){
            $corsorigin='*';
        } else{
            $corsorigin=$domain_scheme.'://'.$domain['host'];
        }


        $url            =   'channel/update';


        $to_record="false";
        if($to_save_option['record']){
            $to_record="true";
        }

        if(   $to_save_option['low_latency'] == 1 ||   $to_save_option['adaptive_bitrate'] ==1 ){
            $to_save_option['encrypt']=0;
        }

        if(  $to_save_option['encrypt']==1){
            $to_save_option['low_latency'] = 0;
            $to_save_option['adaptive_bitrate'] =0;
        }


        $abr='none';
        if($to_save_option['adaptive_bitrate'] ==1 ){
            $abr='common';
        }



        $curl_post_fields=array( 
            'access_token'          =>  $access_token, 
            'channel_id'            =>  $channel_id,
            'domain'                =>  $domain['host'],
            'allow_access_from'     =>  $corsorigin,
            'record'                =>  $to_record,
            'encrypt'               =>  boolval($to_save_option['encrypt']),
            'autostart'             =>  boolval($to_save_option['autostart']),
            'low_latency'           =>  boolval($to_save_option['low_latency']),
            'abr'                   =>  $abr,
            'hls_keys_url_prefix'   =>  get_site_url().'?wpstream_livedrm=',
            'allow_key_access_from' =>  $domain_ip,   
            'to_save_option'        =>  $to_save_option,       
        );
      


        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);  
        print_r($curl_response);
        exit();
    }





    /**
    *
    * added 5.0
    * 
    * Turn off channel
    *
    */
    public function wpstream_turn_of_channel(){
  
        $access_token   =   $this->wpstream_get_token();
        if($access_token==''){
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                    'is_record'     =>  '',
                    'conected'      =>  false,
                    'event_data'    =>  '',
                    'error'         =>  esc_html('You are not connected to wpstream.net! Please check your WpStream credentials!','wpstream'),
                ));
            exit();
        }
        $current_user       =   wp_get_current_user();
        $userID             =   $current_user->ID;
         

        global $wpstream_plugin;
        if( !$wpstream_plugin->main->wpstream_check_user_can_stream() ){
            exit('You are not allowed to stream.Code 407');
        }


        $channel_id  =   intval($_POST['show_id']);
  
        $url            =   'channel/stop';
        $domain         =   parse_url ( get_site_url() );
        $curl_post_fields=array( 
            'access_token'          =>  $access_token, 
            'channel_id'            =>  $channel_id,
            'domain'                =>  $domain['host'],
            
        );
        
     
   
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);       
        $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);

     
  
        if( isset($curl_response_decoded['success']) && $curl_response_decoded['success']===true   ){
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                'conected'      =>  true,
                'answer'        =>  $curl_response_decoded,
            ));
        }else{        
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                'conected'      =>  false,
                'error'         =>  esc_html__('Channel is already turned off or does not exist!','wpstream'),
                'answer'        =>  $curl_response_decoded,
            ));
        }
        die();
  
  
  
  
    }
  
    
    /**
     *
     * Edited 4.0
     * 
     *  Request live url
     *
     * @since    3.0.1
     * returns live url
     */
    public function wpstream_give_me_live_uri(){
       
        $access_token   =   $this->wpstream_get_token();
        if($access_token==''){
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                    'is_record'     =>  '',
                    'conected'      =>  false,
                    'event_data'    =>  '',
                    'error'         =>  esc_html('You are not connected to wpstream.net! Please check your WpStream credentials!','wpstream'),
                ));
            exit();
        }
         
        $current_user       =   wp_get_current_user();
        $userID             =   $current_user->ID;
         

        global $wpstream_plugin;
        if( !$wpstream_plugin->main->wpstream_check_user_can_stream() ){
            exit('You are not allowed to stream.Code 407');
        }


        $channel_id  =   intval($_POST['show_id']);
        $on_boarding =   '';
        if(isset($_POST['start_onboarding'])){
            $on_boarding =   sanitize_text_field($_POST['start_onboarding']);
        }
       

        $local_event_options =   get_post_meta($channel_id,'local_event_options',true);
        if(!is_array($local_event_options)){
            $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;
        }


        // set encrypt option
        $is_autostart="false";
        if( intval( $local_event_options['autostart']) ==1 ){
            $is_autostart="true";
        }


        // set encrypt option
        $is_encrypt="false";
        if( intval( $local_event_options['encrypt']) ==1 ){
            $is_encrypt="true";
        }

        $low_latency="false";
        if( intval( $local_event_options['low_latency']) ==1 ){
            $low_latency="true";
        }

        $adaptive_bitrate="false";
        if( intval( $local_event_options['adaptive_bitrate']) ==1 ){
            $adaptive_bitrate="true";
        }

        if($adaptive_bitrate=="true" ||   $low_latency=="true"  ){
            $is_encrypt="false";
        }

        if( $is_encrypt=="true"){
            $adaptive_bitrate="false";  
            $low_latency="false";
        }



        // set record option
        $is_record="false";
        if( intval( $local_event_options['record']) ==1 ){
            $is_record="true";
        }

        $corsorigin='';
        if( isset($local_event_options['domain_lock']) && intval( $local_event_options['domain_lock']) ==0 ){
            $corsorigin='*';
        }
            
        
        $event_data         =   $this->wpstream_request_live_stream_uri($channel_id,$is_autostart,$is_record,$is_encrypt,$low_latency,$adaptive_bitrate,$userID,$corsorigin,$on_boarding);
       
        
        
        if( isset($event_data['success']) && $event_data['success']===true   ){
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                'is_record'     =>  $is_record,
                'conected'      =>  true,
                'event_data'    =>  $event_data,

                ));
        }else{
            $default_error= 'Failed to turn channel ON. Please try again in a few minutes.';
            if( isset($event_data['error'])){
                $plumer_error = $event_data['error'];
                switch ($plumer_error) {
                    case 'NOT_ENOUGH_TRAFFIC':
                        $default_error= 'You do not have enough Streaming Data to turn ON a live channel. Please upgrade your subscription for more resources.' ;
                        break;
                    
                }
                
            }
            // cleanup any previous echo before sending json
            ob_end_clean();
            echo json_encode(   array(
                'is_record'     =>  $is_record,
                'conected'      =>  false,
                'event_data'    =>  $event_data,
                'error'         =>  $default_error,
                   

                ));
        }
        die();
    }


    /**
     *
     * Edited 4.0
     * 
     *  Request live url via api
     *
     * @since    3.0.1
     * returns live url
     */


    public function wpstream_request_live_stream_uri($schannel_id,$is_autostart,$is_record,$is_encrypt,$low_latency,$adaptive_bitrate,$request_by_userid,$corsorigin,$on_boarding){    
          
            $domain         = parse_url ( get_site_url() );
            $domain_scheme  =   'http';
            if(is_ssl()){
                $domain_scheme='https';
            }
            
            $domain_ip= esc_html( $_SERVER['SERVER_ADDR'] );
            if($domain_ip==''){
                $domain_ip="0.0.0.0/0";
            }
            
            if($corsorigin!='*'){
                $corsorigin=$domain_scheme.'://'.$domain['host'];
            }
            
            $abr='none';
            if($adaptive_bitrate=="true"){
                $abr='common';
            }
            
            if($low_latency=="true"){
                $low_latency=true;
            }else{
                $low_latency = false;
            }

            
            $url            =   'channel/start';
            $access_token   =   $this->wpstream_get_token();
            
            $metadata_array=array(
                'pluginVersion'=>WPSTREAM_PLUGIN_VERSION
            );

            if($on_boarding!=''){
                $metadata_array['on_boarding']='yes';
            }

            
            $curl_post_fields=array( 
                'access_token'          =>  $access_token, 
                'channel_id'            =>  $schannel_id,
                'domain'                =>  $domain['host'],
                'allow_access_from'     =>  $corsorigin,
                'record'                =>  $is_record,
                'encrypt'               =>  $is_encrypt,
                'low_latency'           =>  $low_latency,
                'abr'                   =>  $abr,
                'hls_keys_url_prefix'   =>  get_site_url().'?wpstream_livedrm=',
                'allow_key_access_from' =>  $domain_ip,
                'metadata'              =>  json_encode($metadata_array),
                'autostart'             =>  $is_autostart,
              //  'fakeError'             =>  'init'
            );
            
         
       
            $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);       
            $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
            // $curl_response_decoded['curl_post_fields']=$curl_post_fields;
            return $curl_response_decoded;   
        


    }











    /**
     * Retrive auth token from tranzient
     *
     * @since    3.0.1
     * returns token
     */
    public function wpstream_get_token(){
        $token =  get_transient('wpstream_token_api');
        if ( false === $token || $token==='') {
            $token = $this->wpstream_club_get_token();
            if ($token !== false){
                set_transient( 'wpstream_token_api', $token ,3500);
            }
            else {
                // cache the failed response for a second, otherwise it'll make a shitload of requests
                set_transient( 'wpstream_token_api', 'failed' , 1);
            }
        }
        $ret = $token === 'failed' ? false : $token;
        return $ret;
    }

    
    public function wpstream_get_token_30(){
        $token =  get_transient('wpstream_token_request_30');
        if ( false === $token || $token==='' ) {
            $token = $this->wpstream_club_get_token_30();
            set_transient( 'wpstream_token_request_30', $token ,600);
        }

        return $token;

    }
    
    
     /**
     * Edited 4.0
     * 
     *  Request auth token from wpstream.net
     *
     * @since    3.0.1
     * returns token fron wpstream
     */
    protected function wpstream_club_get_token(){
        $username       = esc_html ( get_option('wpstream_api_username','') );
        $password       = esc_html ( get_option('wpstream_api_password','') );

        if ( $username=='' || $password==''){
            return;
        }
       
        $url='access_token';
        $curl_post_fields=array(
            'grant_type'    =>  'password',
            'username'      =>  $username,
            'password'      =>  $password          
        );
        
    
        
        $curl_response=$this->wpstream_baker_do_curl_base($url,$curl_post_fields, true);
            
        $response= json_decode($curl_response);

        if( isset($response->access_token) && $response->access_token!='' ){
            return $response->access_token;
        }else{        
             return false;
        }
    }
    
 
    
    /*
     * 
     * Return token for api version 3.0
     * 
     */
    
    
    
    
    protected function wpstream_club_get_token_30(){

        $client_id      = esc_html ( get_option('wpstream_api_key','') );
        $client_secret  = esc_html ( get_option('wpstream_api_secret_key','') );
        $username       = esc_html ( get_option('wpstream_api_username','') );
        $password       = esc_html ( get_option('wpstream_api_password','') );

        if ( $username=='' || $password==''){
            return;
        }
        
        
        $curl = curl_init();
        
        $json = array(
                'grant_type'=>'password',
                'username'  =>$username,
                'password'  =>$password,
                'client_id'=>'qxZ6fCoOMj4cNK8SXRHa5nug6vnswlFWSF37hsW3',
                'client_secret'=>'L1fzLosJf9TlwnCCTZ5pkKmdqqkHShKEi0d4oFNE'
            );

        curl_setopt_array($curl, array(
        CURLOPT_URL => WPSTREAM_CLUBLINKSSL."://www.".WPSTREAM_CLUBLINK."/?oauth=token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS=> json_encode($json),
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        
        if(!$response){
            //
        }
        
        $err = curl_error($curl);
        
  

        
        
        curl_close($curl);
        $response= json_decode($response);

        if(isset($response->access_token)){
            return $response->access_token;
        }else{
            return;
        }
    }
    
 
    
    /**
    * edited 4.0 
    * Return admin package data
    *
    * @since    3.0.1
    * returns pack data 
    */
    
    public function wpstream_request_pack_data_per_user(){

        $event_data_for_transient   =   get_transient( 'wpstream_request_pack_data_per_user_transient' );    
   
        if($event_data_for_transient===false){
            $url            =   'user/quota';
            $access_token   =   $this->wpstream_get_token();
            
            // do not make the call if no token is available
            if (!$access_token) return false;

            $curl_post_fields=array( 
                'access_token'=>$access_token 
            );

            $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
            $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);

            if( isset($curl_response_decoded['success']) && $curl_response_decoded['success']===true   ){
                set_transient( 'wpstream_request_pack_data_per_user_transient', $curl_response_decoded, 15 );
              
                update_option('wpstream_api_username_from_token',$curl_response_decoded['username']);
                return $curl_response_decoded;
            }else{
                return false;
            }
        
        }else{
            return $event_data_for_transient;
        }

    }
    



    /**
    * Edited 4.0
    *  
    * Check Api Status
    *
    * @since    3.0.1
    * returns true or false
    */
    
    function wpstream_client_check_api_status(){
        return true;
    }
    
    
    
    
    
    /**
    * 
    * Edited 4.0 
    * 
    * Start Get live events for users
    *
    * @since    3.0.1
    * returns true or false
    */
    public function wpstream_get_live_event_for_user($with_exit='yes'){
        $current_user       =   wp_get_current_user();
        $userID             =   $current_user->ID;
    
        global $wpstream_plugin;
        if( !$wpstream_plugin->main->wpstream_check_user_can_stream() ){
            if($with_exit=='yes'){
                exit('You are not allowed to start a live stream !');
            }else{
                return;
            }
           
        }


        $event_data         =   $this->wpstream_request_live_stream_for_user($userID);
        $return_event       =   array();
        if(is_array($event_data)):
            foreach ($event_data as $key=>$event){
                $return_event[$event['channel_id']]=$event;
            }
        endif;
        return $return_event;
    }
    
    
    
    
    
 
    
    
    
    
    
    /**
    *  
    * Edited 4.0
    * 
    * Get live events for users 
    *
    * @since    3.0.1
    * returns true or false
    */
    public function wpstream_request_live_stream_for_user($user_id){

        global $wpstream_plugin;
       

        $domain = parse_url ( get_site_url() );
    

        $url            =   'channel/list';
        $access_token   =   $this->wpstream_get_token();

        // do not make the call if no token is available
        if (!$access_token) return false;

        $curl_post_fields=array( 
                'access_token'  =>  $access_token,
                'domain'        =>  $domain['host'],
                'status'        =>  'active'
            );
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
        $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
        
      
       
     
        if( isset($curl_response_decoded['success']) && $curl_response_decoded['success']==true   ){
            return $curl_response_decoded['channels'];
        }else{
            return false;
        }
        
 
    }
    
    
    /**
    * live events  for shortocde - behind tranzisnt 
    * returns an array with  local show id for live events
    * @since    3.0.1
    * returns noda
    */
     public function api20_wpstream_request_live_stream_for_user_for_shortcode($outside=''){
        global $wpstream_plugin;
        $return_array=array();
        
        $result = get_transient('wpstream_live_stream_for_user_for_shortcode');
       
        if($result===false){
            $result = $this->wpstream_request_live_stream_for_user('');
            set_transient('wpstream_live_stream_for_user_for_shortcode',$result,30);
        }
     
        if(is_array($result)):
            foreach($result as $key=>$event){
                $return_array[]=$event['channel_id'];
            }
        endif;
        return $return_array;
     }
    
    
 
    
    /**
    * Delete event
    *
    * @since    3.0.1
    * returns noda
    */
    
    public function wpstream_close_event(){
          //not implemented yet
    }


    /**
    * Get signed upload form data
    *
    * @since    3.0.1
    * returns aws form
    */
    public function wpstream_get_signed_form_upload_data(){
        if( !current_user_can('administrator') ){
            exit('not admin on wpstream_get_signed_form_upload_data');
        }


        $url            =   'video/upload';
        $access_token   =   $this->wpstream_get_token();

        // do not make the call if no token is available
        if (!$access_token) return array(
            'success'      =>  false,
            'error'        =>  'not_connected',
        );
        
        $curl_post_fields=array( 
               'access_token'  =>  $access_token,
        );
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
        $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);


       return $curl_response_decoded;
       exit();

    }






    
    /**
    * 
    * Get video from storage- clear data for front end use
    *
    * @since    3.0.1
    * returns aws data
    * 
    */
    public function wpstream_get_videos(){
        if( !current_user_can('administrator') ){
            exit('not admin on wpstream_get_videos');
        }

        
        $video_options          =   array();
        $video_array            =   $this->wpstream_get_videos_from_api();
     
        $video_list_raw_array   =   $video_array['items'];
        
        
        if(is_array($video_list_raw_array)){
            $keys = array_column($video_list_raw_array, 'time');
            array_multisort($keys, SORT_DESC , $video_list_raw_array);

            foreach ($video_list_raw_array as $key => $videos){
                if($videos['name']!=''):
                    $video_options[$videos['name']]=$videos['name'];
                endif;
            }

        }
        return $video_options;
                       
    }
    
    
    
    /**
    * 
    * Get video from storage- raw data
    *
    * @since    3.0.1
    * returns aws data
    * 
    */
    public function wpstream_get_videos_from_api( ){
  
        if( !current_user_can('administrator') ){
            exit('not admin on wpstream_get_videos_from_api');
        }

    

        $url            =   'video/list';
        $access_token   =   $this->wpstream_get_token();

        // do not make the call if no token is available
        if (!$access_token) return false;

        $curl_post_fields=array( 
               'access_token'  =>  $access_token,
        );
        
        $current_page= get_current_screen();
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
        $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);




        if( isset($curl_response_decoded['success']) && $curl_response_decoded['success']==true   ){
            return $curl_response_decoded;
        }else{
           return array();
        }
            
    }

    
    
    /**
    * Get download link from aws
    *
    * @since    3.0.1
    * returns aws data
    */
    
    function wpstream_get_download_link(){
        
        if( !current_user_can('administrator') ){
            exit('not admin on get_download_link');
        }

        $video_name                 =   sanitize_text_field($_POST['video_name']);

        $url            =   'video/download';
        $access_token   =   $this->wpstream_get_token();

        // do not make the call if no token is available
        if (!$access_token) return false;

        $curl_post_fields=array( 
            'access_token'  =>  $access_token,
            'name'          =>  $video_name,
        );
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields);
        print $curl_response;
        exit();

            

    }

    
     /**
    * Delete file from storage
    *
    * @since    3.0.1
    * 
    */
    public function wpstream_get_delete_file(){
        if( !current_user_can('administrator') ){
            exit('not admin on get_delete_file');
        }

        $video_name                 =   esc_html($_POST['video_name']);
        

        $url            =   'video/delete';
        $access_token   =   $this->wpstream_get_token();

        // do not make the call if no token is available
        if (!$access_token) return false;

        $curl_post_fields=array( 
            'access_token'  =>  $access_token,
            'name'=>$video_name,
        );
        $curl_response          =   $this->wpstream_baker_do_curl_base($url,$curl_post_fields,true);
        $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
        
            
        print $curl_response;   
        
        exit();
            
    
    }


    
    
    

        
        
        
}// end class