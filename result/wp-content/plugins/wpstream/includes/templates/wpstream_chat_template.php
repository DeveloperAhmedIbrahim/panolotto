
<div class="chat_wrapper main">
      
    <div class=" wpstream_chat_container">
         
                    <div class=" wpestream_chat_meat">
                     
                                <div id="panel">
                                    <div id="users-dialog"  >
                                        <div id="close-users-dialog">x</div>
                                        <h4>Current users</h4>
                                        <div id="users-content"></div>
                                    </div>
                                    
                                    <p id="typing"><br></p>
                                    <hr>

                                
                                    <div class="wpstream_chat_input">
                                        <div class="col-lg-12">
                                            <div class="input-group">

                                                <textarea id="message" type="text"></textarea>
                                                <span class="input-group-btn wpstream_chat_actions">
                                                    <button id="send" class="btn btn-primary btn-flat">Connect</button>
                                                    <div id ="user" class="wpstream_chat_users"><i class="fas fa-user"></i></div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                                
                      
                   
                    </div>
                 
                    <div id="badges" class="text-right pull-right">
                        <span style="display:none;"><label id="users" class="label">0 USERS</label></span>
                        <span><label id="admin" class="label label-warning" style="display:none">ADMIN</label></span>                              
                    </div>
      
    </div>
</div>




    <div id="help-dialog" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <h4><b>Current available commands:</b></h4>
                        <div class="col-xs-3"><b>
                            <b>COMMAND</b>
                            <br>/pm
                            <br>/me or /em
                            <br>/shrug
                            <br>/name
                            <br>/users
                            <br>/help
                            <br>/clear
                            <br>/reconnect
                        </b></div>
                        <div class="col-xs-2">
                            <b>VARIABLES</b>
                            <br>[user] [message]
                            <br>[message]
                            <br>[message]
                            <br>[name]
                            <br>
                            <br>
                            <br>
                        </div>
                        <div class="col-xs-7">
                            <b>DESCRIPTION</b>
                            <br>Sends a private <i>[message]</i> for <i>[user]</i>
                            <br>Sends <i>[message]</i> in italics
                            <br>Sends <i>[message]</i> followed by 'Â¯\_(ãƒ„)_/Â¯'
                            <br>Changes your name to <i>[name]</i>
                            <br>Shows users on the server
                            <br>Shows this help dialog
                            <br>Clears your chat history
                            <br>Reconnects to the server
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="admin-help-dialog" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <h4><b>Current available administrator commands:</b></h4>
                        <div class="col-xs-3"><b>
                            <b>COMMAND</b>
                            <br>/alert
                            <br>/kick
                            <br>/ban
                            <br>/role
                        </b></div>
                        <div class="col-xs-2">
                            <b>VARIABLES</b>
                            <br>[message]
                            <br>[user]
                            <br>[user] [minutes]
                            <br>[user] [1-3]
                        </div>
                        <div class="col-xs-7">
                            <b>DESCRIPTION</b>
                            <br>Sends global <i>[message]</i>
                            <br>Kicks <i>[user]</i> from the server
                            <br>Bans <i>[user]</i> from the server for <i>[minutes]</i>
                            <br>Changes <i>[user]</i> administrator permissions
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="options-dialog" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4><b>Chat options:</b></h4>
                    <div class="togglebutton">
                        <label>Emojis<input id="emoji" type="checkbox"></label>
                    </div>
                    <div class="togglebutton">
                        <label>Greentext<input id="greentext" type="checkbox"></label>
                    </div>
                    <div class="togglebutton" id="toggle-inline">
                        <label>Inline Images<input id="inline" type="checkbox"></label>
                    </div>
                    <div class="togglebutton">
                        <label>Mention Sound<input id="sound" type="checkbox"></label>
                    </div>
                    <div class="togglebutton" id="toggle-desktop" style="display:none">
                        <label>Desktop Notifications<input id="desktop" type="checkbox"></label>
                    </div>
                    <div class="togglebutton" id="toggle-synthesis" style="display:none">
                        <label>Speech Synthesis [Experimental]<input id="synthesis" type="checkbox"></label>
                    </div>
                    <div class="togglebutton" id="toggle-recognition" style="display:none">
                        <label>Speech Recognition [Experimental]<input id="recognition" type="checkbox"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    



<!--
//    global $post;
//    wpstream_connect_to_chat($post->ID);-->
   
