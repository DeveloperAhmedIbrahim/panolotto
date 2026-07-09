/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * player,'.$live_event_uri_final.','.$live_conect_views.'
 */

window.WebSocket = window.WebSocket || window.MozWebSocket;
if (!window.WebSocket) {
  console.log("Sorry, but your browser does not support WebSockets");
}

function wpstream_player_initialize(settings) {
  const player = new WpstreamPlayer(settings);
}

class WpstreamPlayer {
  // id;
  // trailerUrl;
  // contentUrl;
  // statsUri;
  // autoplay;
  // ruler = 0; //0 - basic; 1 - ajax; 2 - ws
  // state = -1;
  //-1 - unknown
  // 0 - stopped
  // 1 - notstarted
  // 2 - started
  // 4 - init
  // 5 - paused
  // 6 - startup
  // 7 - onair
  // 9 - ended
  // 10 - finished

  // liveConnect;
  // wrapper;
  // counter;
  // chat;

  constructor(settings) {
    console.log("[]WpstreamPlayer: ", settings);
    this.settings = settings;
    this.id = settings.videoElementId;
    this.trailerUrl = settings.trailerUrl;
    this.contentUrl = settings.contentUrl;
    this.statsUri = settings.statsUri;
    this.autoplay = settings.autoplay;
    this.playTrailerButton = jQuery(`#${settings.playTrailerButtonElementId}`);
    this.muteTrailerButton = jQuery(`#${settings.muteTrailerButtonElementId}`);
    this.unmuteTrailerButton = jQuery(
      `#${settings.unmuteTrailerButtonElementId}`
    );
    this.titleOverlay = document.getElementById(settings.titleOverlayElementId);
    this.liveConnect = new LiveConnect(this);
    this.wrapper = jQuery("#wpstream_live_player_wrapper" + this.id);
    console.log("wrapper: ", this.wrapper);
    this.channelId = this.wrapper.attr("data-product-id");
    console.log("channelId: ", this.channelId);
    this.playback = new WpstreamPlayback(this, this.id, this.autoplay);
    this.counter = new LiveCounter(this.wrapper, this.id);
    this.liveMessage = new WpstreamLiveMessage(this.wrapper, this.id);
    this.chat = new WpstreamChat();
    this.setRuler(1);

    
    let player = this.playback.player;
    console.log("player: ", player);
    let playerElement = player.el();
    if (this.titleOverlay){
      playerElement.appendChild(this.titleOverlay);
    }
    // console.log("playerElement: ", playerElement);
    let playerParentElement = playerElement.parentNode;

    if (
      playerParentElement.classList.contains(
        "wpstream_simple_player_shortcode_wrapper"
      )
    ) {
      settings.trailerUrl = null;
    }
    

    if (settings.trailerUrl) {
      const owner = this;
      this.playTrailerButton.on("click", function () {
        console.log("playTrailer()");
        owner.playTrailerButton.hide();
        owner.playback.playTrailer(owner.trailerUrl, true);
      });
      this.muteTrailerButton.on("click", function () {
        owner.playback.player.muted(true);
      });
      this.unmuteTrailerButton.on("click", function () {
        owner.playback.player.muted(false);
      });
    }
    this.playTrailerButton.hide();
  }

  setRuler(ruler) {
    console.log("setRuler: " + ruler);
    let oldRuler = this.ruler;
    console.log("oldRuler: ", oldRuler);
    this.ruler = ruler;
    switch (ruler) {
      case 1:
        if (oldRuler != 1) {
          this.getDynamicSettings();
        }
        clearTimeout(this.retrieveDynamicSettingsTimeout);
        let self = this;
        this.retrieveDynamicSettingsTimeout = setTimeout(
          () => self.getDynamicSettings(),
          30 * 1000
        );
        break;
      case 2:
        clearTimeout(this.retrieveDynamicSettingsTimeout);
        break;
    }
  }

  getDynamicSettings() {
    console.log("getDynamicSettings()");
    let ajaxurl = wpstream_player_vars.admin_url + "admin-ajax.php";
    let owner = this;
    jQuery.ajax({
      type: "POST",
      url: ajaxurl,
      dataType: "json",
      data: {
        action: "wpstream_player_check_status",
        channel_id: this.channelId,
      },
      success: function (data) {
        console.log("dynamicSettings: ", data);
        if (data == 0) {
          owner.setState("stopped");
        } else if (data.started == "no") {
          owner.setState("notstarted");
          owner.chat.disconnect();
        } else if (data.started == "yes") {
          let liveConnectUri = data.live_conect_views;
          owner.liveConnect.setup(liveConnectUri);
          let contentUrl = data.event_uri;
          owner.setState("started");
          owner.setContentSrc(contentUrl);
          owner.chat.connect(data.chat_url);
        }
      },
      error: function (error) {
        console.log("dynamicSettingsError: ", error);
      },
    });
    if (this.ruler <= 1) {
      this.setRuler(1);
    }
  }

  setContentSrc(uri) {
    this.playback.setContentSrc(uri);
  }

  setState(state) {
    console.log("setState: ", state);
    const oldState = this.state;
    console.log("oldState: ", oldState);
    this.state = state;

    if (
      this.trailerUrl &&
      state != "onair" &&
      state != "started" &&
      state != "startup"
    ) {
      this.playback.playTrailer(this.trailerUrl);
    }

    switch (state) {
      case "stopped":
      case "notstarted":
      case "starting":
        this.liveMessage.showStoppedMessage();
        this.playback.pauseContent();
        break;
      case "started":
        this.liveMessage.hide();
        break;
      case "init":
      case "paused":
        this.liveMessage.showMessage(state);
        this.playback.pauseContent();
        break;
      case "ended":
        this.liveMessage.showMessage(state);
        this.playback.pauseContent(true);
        break;
      case "startup":
        this.liveMessage.showMessage(state);
        if (oldState != "onair") {
          this.playback.pauseContent();
        }
        break;
      case "onair":
        this.liveMessage.hide();
        this.playback.playContent();
        break;
    }
  }

  showHideMuteTrailerButtons() {
    console.log("showHideMuteTrailerButtons()");
    console.log("playingTrailer: ", this.playback.playingTrailer);
    console.log("muted: ", this.playback.player.muted());
    if (this.playback.playingTrailer) {
      if (this.playback.player.muted()) {
        this.muteTrailerButton.hide();
        this.unmuteTrailerButton.show();
      } else {
        this.muteTrailerButton.show();
        this.unmuteTrailerButton.hide();
      }
    } else {
      this.muteTrailerButton.hide();
      this.unmuteTrailerButton.hide();
    }
  }

  onLiveConnectActive(isActive) {
    console.log("onLiveConnectActive: ", isActive);
    this.setRuler(isActive ? 2 : 1);
    if (!isActive) {
      this.counter.hide();
    }
  }

  updateViewerCount(count) {
    console.log("updateViewerCount: ", count);
    this.counter.show();
    this.counter.setCount(count);
  }

  showTitleOverlay(show = true) {
    if (this.titleOverlay){
      this.titleOverlay.style.opacity = show ? '1' : '0';
    }
  }
}

class WpstreamPlayback {
  // player;
  // timeQueue = [];
  // master;
  // paused = false;
  // played = false;

  constructor(master, id, autoplay) {
    this.timeQueue = [];
    this.paused = false;
    this.played = false;
    this.contentSrc = null;
    this.trailerState = "notstarted";
    this.playingTrailer = false;
    this.master = master;
    this.setupBasePlayer(id, autoplay);
    this.runWatchdog();
  }

  setupBasePlayer(id, autoplay) {
    console.log("setupBasePlayer: ", id, autoplay);
    let contentUrl = this.master.contentUrl;
    console.log("contentUrl: ", contentUrl);
    let llhls = isLlHls(contentUrl);
    console.log("llhls: ", llhls);
    this.player = videojs("wpstream-video" + id, {
      html5: {
        vhs: {
          useBandwidthFromLocalStorage: true,
          limitRenditionByPlayerDimensions: false,
          useDevicePixelRatio: true,
          overrideNative: !videojs.browser.IS_SAFARI,
          cacheEncryptionKeys: true,
          llhls,
        },
      },
      errorDisplay: false,
      autoplay: autoplay,
      preload: "auto",
      // muted    : true
    });

    if (this.master.settings.theaterModeButtons) {
      const Button = videojs.getComponent("Button");
      const owner = this;

      class TheaterModeEnterButton extends Button {
        constructor(player, options) {
          super(player, options);
          this.controlText("Switch to Theater Mode");
          this.addClass(
            owner.master.settings.theaterModeButtons.enterTheaterModeButton.skin
          );
        }
        handleClick() {
          owner.theaterModeEnterButton.hide();
          owner.theaterModeLeaveButton.show();
          console.log("entering Theater Mode...");
          eval(
            owner.master.settings.theaterModeButtons.enterTheaterModeButton
              .callback
          );
        }
      }
      class TheaterModeLeaveButton extends Button {
        constructor(player, options) {
          super(player, options);
          this.controlText("Switch to Normal Mode");
          this.addClass(
            owner.master.settings.theaterModeButtons.leaveTheaterModeButton.skin
          );
        }
        handleClick() {
          owner.theaterModeEnterButton.show();
          owner.theaterModeLeaveButton.hide();
          console.log("leaving Theater Mode...");
          eval(
            owner.master.settings.theaterModeButtons.leaveTheaterModeButton
              .callback
          );
        }
      }

      videojs.registerComponent(
        "TheaterModeEnterButton",
        TheaterModeEnterButton
      );
      videojs.registerComponent(
        "TheaterModeLeaveButton",
        TheaterModeLeaveButton
      );
      this.theaterModeEnterButton = owner.player
        .getChild("controlBar")
        .addChild("TheaterModeEnterButton", {}, 17);
      this.theaterModeLeaveButton = owner.player
        .getChild("controlBar")
        .addChild("TheaterModeLeaveButton", {}, 18);
      this.theaterModeLeaveButton.hide();
    }

    // this.player.controls(false);
    this.player.bigPlayButton.hide();
    // player.controlBar.progressControl.hide();
    const owner = this;

    this.player.on('useractive', function() {
      owner.master.showTitleOverlay();
    });

    this.player.on('userinactive', function() {
      owner.master.showTitleOverlay(false);
    });

    this.player.on("play", function (event) {
      console.log("Play");

      if (this.userActive()) {
        owner.master.showTitleOverlay();
      }

      owner.played = true;
      console.log("src: ", owner.player.currentSrc());
      console.log("playingTrailer: ", owner.playingTrailer);
      if (owner.playingTrailer) {
        console.log("trailerState: ", owner.trailerState);
        if (owner.trailerState == "attempted") {
          owner.trailerState = "playing";
          owner.master.liveMessage.showAtBottom(true);
        }
        showDomElements('playing_trailer', owner.player.el());
      } else {
        showDomElements('playing_content', owner.player.el());
      }
      owner.master.playTrailerButton.hide();
      owner.master.showHideMuteTrailerButtons();
    });
    this.player.on("pause", function (event) {
      console.log("Pause");
    });
    this.player.on("ended", () => {
      console.log("Ended");
      console.log("playingTrailer: ", this.playingTrailer);
      if (this.playingTrailer) {
        showDomElements('idle', owner.player.el());
        owner.stopTrailer();
      } else {
        console.log("content ended");
        this.player.controls(false);
        this.player.hasStarted(false);
      }
      this.master.showHideMuteTrailerButtons();
    });
    this.player.on("durationchange", () => {
      const duration = this.player.duration();
      console.log("durationchange: ", duration);
      if (duration > 0 && duration < Infinity) {
        this.player.controls(false);
      }
    });
    this.player.on("error", (error) => {
      console.log("error: ", error);
    });
    this.player.on("volumechange", () => {
      console.log("muted: ", this.player.muted());
      this.master.showHideMuteTrailerButtons();
    });

    console.log("src: ", this.player.currentSrc());
    // setTimeout(() => {
    //     this.player.bigPlayButton.show();
    // }, 2000);
  }

  playTrailer(src, click) {
    console.log("### playTrailer: ", src, click);
    console.log("trailerState: ", this.trailerState);
    // if (this.trailerState == 'notstarted' || this.trailerState == 'attempted'){

    if (this.trailerState == "notstarted" && !click) {
      this.master.playTrailerButton.show();
    }

    if (this.trailerState == "notstarted" || click) {
      this.playingTrailer = true;
      const player = this.player;
      player.controls(false);
      player.src({ src });
      if (click) {
        player.play();
        // showDomElements('playing_trailer');  //probably not needed
      }
      this.trailerState = "attempted";
    }
  }

  stopTrailer() {
    console.log("stopTrailer()");
    console.log("trailerState: ", this.trailerState);
    if (this.trailerState == "playing") {
      console.log("trailer has ended");
      this.trailerState = "ended";
      this.master.liveMessage.showAtBottom(false);
      this.player.hasStarted(false);
    }
    this.playingTrailer = false;
  }

  setContentSrc(src, force) {
    console.log("### setContentSrc: ", src, force);
    console.log("currentSrc: ", this.player.currentSrc());
    console.log("paused: ", this.player.paused());
    console.log("currentTime: ", this.player.currentTime());
    this.playingTrailer = false;
    this.contentSrc = src;

    const owner = this;
    clearTimeout(this.setSrcTimeout);
    this.setSrcTimeout = setTimeout(
      () => {
        console.log("setting src...");
        // src = src != null ? src : owner.player.currentSrc()
        // owner.player.src({
        //     src,
        //     type: "application/x-mpegURL",
        //     llhls: isLlHls(src)
        // });
        owner.playContent(force);
      },
      force ? 1 : 2000
    );

    // this.player.controlBar.show();
    // this.player.loadingSpinner.show();
    this.player.controls(true);
    // this.player.muted(true);
    // this.player.play();
  }

  playContent(forced) {
    console.log("playContent() ", forced);
    this.paused = false;
    this.stopTrailer();
    this.playingTrailer = false;
    this.master.playTrailerButton.hide();
    this.player.bigPlayButton.show();
    clearTimeout(this.setSrcTimeout);
    console.log("player.paused: ", this.player.paused());
    console.log("currentTime: ", this.player.currentTime());
    console.log("objectivelyPlayingContent: ", this.objectivelyPlayingContent);

    // if (this.player.paused() || forced || this.player.currentTime() === 0){
    if (!this.objectivelyPlayingContent || forced) {
      this.player.src({
        src: this.contentSrc,
        type: "application/x-mpegURL",
        llhls: isLlHls(this.contentSrc),
      });
      console.log("autoplay: ", this.player.autoplay());
      // this.player.currentTime(0);
      console.log("played: ", this.played);
      if (this.played) {
        var promise = this.player.play();
        // console.log("promise: ", promise);
        let player = this.player;
        if (promise !== undefined) {
          promise
            .then(function () {
              console.log("Autoplay started ;)");
            })
            .catch(function (error) {
              console.log("Autoplay did not work ", error);
            });
        }
        console.log("no promise");
      }
    }
    this.player.controlBar.show();
    this.player.loadingSpinner.show();
    this.player.controls(true);
  }

  pauseContent(stop) {
    console.log("pauseContent()");
    this.paused = true;
    clearTimeout(this.setSrcTimeout);
    console.log("playingTrailer: ", this.playingTrailer);
    if (!this.playingTrailer) {
      console.log("paused: ", this.player.paused());
      this.player.pause();
      console.log("currentTime: ", this.player.currentTime());
      this.player.controlBar.hide();
      this.player.loadingSpinner.hide();
      this.player.controls(false);
      if (stop) {
        this.player.hasStarted(false);
      }
    }
  }

  runWatchdog() {
    //console.log("runWatchdog()");
    const currentTime = this.player.currentTime();
    this.timeQueue.push(currentTime);

    var objectivelyPlayingContent = false;
    if (currentTime > 0 && !this.playingTrailer) {
      const queueLength = this.timeQueue.length;
      if (queueLength > 1) {
        if (this.timeQueue[queueLength - 1] > this.timeQueue[queueLength - 2]) {
          objectivelyPlayingContent = true;
        }
      }
    }
    this.objectivelyPlayingContent = objectivelyPlayingContent;

    if (this.timeQueue.length > 25) {
      this.timeQueue.shift();
      if (this.timeQueue[0] === this.timeQueue[this.timeQueue.length - 1]) {
        console.log(
          "queue: ",
          this.timeQueue[0],
          this.timeQueue[this.timeQueue.length - 1]
        );
        console.log("paused: ", this.paused);
        console.log("ruler: ", this.master.ruler);
        console.log("state: ", this.master.state);
        console.log("player paused: ", this.player.paused());
        console.log("currentTime: ", this.player.currentTime());

        if (this.master.ruler == 2) {
          if (!this.player.paused()) {
            this.playContent(true);
          }
        } else if (this.master.state > 1) {
          if (!this.player.paused() || this.player.currentTime() === 0) {
            this.playContent(true);
          }
        }
        this.timeQueue = [];
      }
    }
    let self = this;
    setTimeout(() => self.runWatchdog(), 1 * 1000);
  }
}

function showDomElements(state, target){
  console.log("showDomElements: ", state, target);
  switch (state){
    case 'idle':
      // jQuery(".wpstream_hide_on_trailer").show();
      showHideNearby(target, ".wpstream_hide_on_trailer");
      // jQuery(".wpstream_hide_on_play").show();
      showHideNearby(target, ".wpstream_hide_on_play");
      // jQuery(".wpstream_bundble_title_details h1 ").show();
      showHideNearby(target, ".wpstream_bundble_title_details h1 ");
      // jQuery(".wpstream_bundble_title_details .wpstream-product-description").show();
      showHideNearby(target, ".wpstream_bundble_title_details .wpstream-product-description");
      // jQuery(".wpstream_bundble_title_details .wpstream-product-categories-wrapper").show();
      showHideNearby(target, ".wpstream_bundble_title_details .wpstream-product-categories-wrapper");
      // jQuery(".wpstream_video_on_demand_play_trailer").show();
      showHideNearby(target, ".wpstream_video_on_demand_play_trailer");
      // jQuery(".wpstream_video_on_demand_unmute_trailer").hide();
      showHideNearby(target, ".wpstream_video_on_demand_unmute_trailer", false);
      // jQuery(".vjs-wpstream").removeClass("wpstream_theme_player_has_trailer");
      addRemoveClassNearby(target, '.vjs-wpstream', 'wpstream_theme_player_has_trailer', false)
      break;
    case 'playing_trailer':
      // jQuery(".wpstream_hide_on_trailer").hide();
      showHideNearby(target, ".wpstream_hide_on_trailer", false);
      // jQuery(".wpstream_hide_on_play").show();
      showHideNearby(target, ".wpstream_hide_on_play");
      // jQuery(".wpstream_bundble_title_details h1 ").hide();
      showHideNearby(target, ".wpstream_bundble_title_details", false);
      // jQuery(".wpstream_bundble_title_details .wpstream-product-description").hide();
      showHideNearby(target, ".wpstream_bundble_title_details .wpstream-product-description", false);
      // jQuery(".wpstream_bundble_title_details .wpstream-product-categories-wrapper").hide();
      showHideNearby(target, ".wpstream_bundble_title_details .wpstream-product-categories-wrapper", false);
      // jQuery(".vjs-wpstream").addClass("wpstream_theme_player_has_trailer");
      addRemoveClassNearby(target, '.vjs-wpstream', 'wpstream_theme_player_has_trailer')
      break;
    case 'playing_content':
      // jQuery(".wpstream_hide_on_trailer").hide();
      showHideNearby(target, ".wpstream_hide_on_trailer", false);
      // jQuery(".wpstream_hide_on_play").hide();
      showHideNearby(target, ".wpstream_hide_on_play", false);
      // jQuery(".vjs-wpstream").removeClass("wpstream_theme_player_has_trailer");
      addRemoveClassNearby(target, '.vjs-wpstream', 'wpstream_theme_player_has_trailer', false)
      break;
  } 
}

function showHideNearby(target, targetClass, show = true) {
    var method = show ? 'show' : 'hide';
    var nearbyElements = findNearbyElements(target, targetClass);
    nearbyElements.each(function() {
        jQuery(this)[method]();
    });
}

function addRemoveClassNearby(target, targetClass, className, add = true) {
    var method = add ? 'addClass' : 'removeClass';
    var nearbyElements = findNearbyElements(target, targetClass);
    nearbyElements.each(function() {
        jQuery(this)[method](className);
    });
}

function findNearbyElements(target, targetClass) {
    var $target = jQuery(target);
    var nearbyElements = $target.parents().slice(0, 4).find(targetClass);
    // .add($target.closest('.container').find('.' + targetClass));
    return nearbyElements;
}

class WpstreamChat {
  // connected = '';

  constructor() {
    this.connected = "";
  }

  connect(url) {
    this.connected = "yes";
    if (typeof connect === "function") {
      connect(url);
    }
  }

  disconnect() {
    if (typeof showChat === "function" && this.connected === "yes") {
      showChat("info", null, wpstream_player_vars.chat_not_connected);
      this.connected = "no";
    }
  }
}

class WpstreamLiveMessage {
  // element;
  // msg;
  // originalMessage;
  // customMessage;
  // state = -1; // -1 - unknown; 0 - hidden; 1 - showing original msg; 3 - showing paused msg; 5 - showing custom msg

  static customMessageStates = [
    "stopped",
    "init",
    "paused",
    "startup",
    "ended",
  ];
  bottom = false;

  constructor(wrapper, id) {
    this.state = "none";
    this.element = wrapper.find(".wpstream_not_live_mess");
    this.msg = wrapper.find(".wpstream_not_live_mess_mess");
    this.originalMessage = this.msg.html();
    console.log("originalMessage: ", this.originalMessage);
    var playerElement = jQuery("#wpstream-video" + id);
    this.element.appendTo(playerElement);
  }

  setCustomMessage(message){
    this.customMessage = message;
    this.showMessage(this.state);
  }

  showMessage(state) {
    // console.log("showMessage: ", state);
    var label;
    if (
      this.customMessage &&
      WpstreamLiveMessage.customMessageStates.includes(state)
    ) {
      label = this.customMessage;
    } else {
      label = wpstream_player_vars[`wpstream_player_state_${state}_msg`];
    }
    this.msg.text(label);
    this.msg.addClass(`wpstream_player_state_${state}_class`);
    // don't show if label is empty or spaces
    if (!/^\s*$/.test(label)) this.show();
    else this.hide();
    this.state = state;
  }

  showOriginalMessage() {
    this.msg.html(this.originalMessage);
    this.show();
    this.state = "original";
  }

  showAtBottom(show) {
    console.log("showAtBottom: ", show);
    this.bottom = show;
    this.element[0].style.top = this.bottom ? "80%" : "31%";

    if (this.state == "stopped" || this.state == "original") {
      this.showStoppedMessage();
    }
  }

  showStoppedMessage() {
    console.log("element: ", this.element);
    if (this.bottom) {
      this.showMessage("stopped");
    } else {
      this.showOriginalMessage();
    }
  }

  //public
  hide() {
    this.element.hide();
    this.state = 0;
  }

  //private
  show() {
    this.element.show();
  }
}

class LiveCounter {
  // element;
  constructor(wrapper, id) {
    console.log("[]LiveCounter: ", wrapper, id);
    this.element = wrapper.find(".wpestream_live_counting");
    this.element.css("background-color", "rgb(174 69 69 / 90%)");
    //var playerElement = wrapper.find('.wpstream-video' + id);
    var playerElement = jQuery("#wpstream-video" + id);
    console.log("playerElement: ", playerElement);
    this.element.appendTo(playerElement);
    this.hide();
  }

  show() {
    this.element.show();
  }

  hide() {
    this.element.hide();
  }
  setCount(count) {
    this.element.html(count + " Viewers");
  }
}

class LiveConnect {
  // master;
  // wsUri;
  // ws;
  // connectCount = 0;
  // connected = false;
  // pendingConnect = false;

  constructor(master) {
    this.connectCount = 0;
    this.connected = false;
    this.pendingConnect = false;
    this.master = master;
  }

  setup(wsUri) {
    console.log("setup: ", wsUri);
    this.close();
    this.wsUri = wsUri;
    this.connect();
  }

  close() {
    if (this.ws != null) {
      this.ws.close();
    }
    this.ws = null;
  }

  connect() {
    let connectAttempt = ++this.connectCount;
    console.log("connect() ", connectAttempt);
    this.pendingConnect = true;
    try {
      this.ws = new WebSocket(this.wsUri);
      let owner = this;
      this.ws.onopen = function () {
        console.log("connected. ", connectAttempt);
        owner.pendingConnect = false;
        owner.master.onLiveConnectActive(true);
        //socket_connection.send(`{"type":"register","data":"${now}"}`);
      };
      this.ws.onclose = function () {
        console.log("onclose.. ", connectAttempt);
        owner.master.onLiveConnectActive(false);
      };
      this.ws.onerror = function (error) {
        console.log("onerror: ", connectAttempt, error);
        owner.master.onLiveConnectActive(false);
      };
      this.ws.onmessage = function (message) {
        console.log("onmessage: ", connectAttempt, message.data);
        owner.processMessage(message.data);
      };
    } catch (error) {
      console.log(error);
      this.master.onLiveConnectActive(false);
    }
  }

  processMessage(msg) {
    console.log("processMessage: ", msg);
    var json;
    try {
      json = JSON.parse(msg);
    } catch (e) {
      console.log("Invalid JSON: ", msg);
      return;
    }
    if (json.type) {
      switch (json.type) {
        case "viewerCount":
          this.master.updateViewerCount(json.data);
          break;
        case "onair":
          if (json.info) {
            this.master.setState(json.info.broadcasting);
          } else {
            this.master.setState(json.data ? "onair" : "paused");
          }
          break;
        case "status":
          this.master.liveMessage.setCustomMessage(json.data);
          break;
        default:
          console.log("invalid type: ", json.type);
      }
    }
  }
}

function wpstream_read_websocket_info(
  event_id,
  player,
  player_wrapper,
  socket_wss_live_conect_views_uri,
  event_uri
) {
  console.log(
    "wpstream_read_websocket_info: ",
    event_id,
    player,
    player_wrapper,
    socket_wss_live_conect_views_uri,
    event_uri
  );
  console.log("sldpPlayer: ", sldpPlayer);
  if (sldpPlayer != null) {
    var chat = new WpstreamChat();
    chat.connect(socket_wss_live_conect_views_uri);
  }
}

jQuery(document).ready(function ($) {
  console.log("ready!");
  var event_id;
  var player_wrapper;
  jQuery(".wpstream_live_player_wrapper").each(function () {
    console.log("wrapper: ", this, $(this));
    if ($(this).hasClass("wpstream_low_latency")) {
      return;
    }
    event_id = jQuery(this).attr("data-product-id");
    player_wrapper = jQuery(this);

    //wpstream_check_player_status_ticker(player_wrapper,event_id);
  });
});

var sldpPlayer;

function initPlayer(playerID, low_latency_uri, muted, autoplay) {
  console.log("initPlayer: ", low_latency_uri);
  var is_muted = false;
  var is_autoplay = true;
  if (muted === "muted") {
    is_muted = true;
  }

  if (autoplay !== "autoplay") {
    is_autoplay = false;
  }

  console.log("is_muted " + is_muted + "/ " + is_autoplay);

  let player = OvenPlayer.create(playerID, {
    autoStart: is_autoplay,
    autoFallback: false,
    mute: is_muted,
    sources: [
      {
        type: "webrtc",
        file: low_latency_uri,
      },
    ],
    hlsConfig: {
      liveSyncDuration: 1.5,
      liveMaxLatencyDuration: 3,
      maxLiveSyncPlaybackRate: 1.5,
    },
    webrtcConfig: {
      timeoutMaxRetry: 100,
      connectionTimeout: 10000,
    },
  });
}

function removePlayer() {
  sldpPlayer.destroy();
}

// {
//  videoElementId
//  trailerUrl
//  videoUrl
//  autoplay
//  muted
//  playTrailerButtonElementId
//  playVideoButtonElementId
// }
function wpstream_player_initialize_vod(settings) {
  console.log("wpstream_player_initialize_vod: ", settings);
  var playing = settings.trailerUrl ? "trailer" : "content";

  const playTrailerButton = jQuery(`#${settings.playTrailerButtonElementId}`);
  const playVideoButton = jQuery(`#${settings.playVideoButtonElementId}`);
  const muteTrailerButton = jQuery(`#${settings.muteTrailerButtonElementId}`);
  const unmuteTrailerButton = jQuery(
    `#${settings.unmuteTrailerButtonElementId}`
  );
  const titleOverlay = document.getElementById(settings.titleOverlayElementId);

  muteTrailerButton.hide();
  unmuteTrailerButton.hide();

  if (!settings.trailerUrl) {
    playTrailerButton.hide();
  }
  if (!settings.videoUrl) {
    playVideoButton.hide();
  }

  const initialSrc = settings.trailerUrl
    ? getSrc(settings.trailerUrl)
    : getSrc(settings.videoUrl);
  console.log("initialSrc: ", initialSrc);

  const player = videojs(settings.videoElementId);
  player.preload("auto");
  player.playsinline(true);
  player.controls(!settings.trailerUrl);
  player.autoplay(settings.autoplay);
  player.muted(settings.muted);

  const originalPoster = player.poster();

  player.src({ ...initialSrc, autoplay: true, muted: true });
  if (titleOverlay){
    player.el().appendChild(titleOverlay);
  }

  if (settings.theaterModeButtons) {
    const Button = videojs.getComponent("Button");
    const owner = this;

    class TheaterModeEnterButton extends Button {
      constructor(player, options) {
        super(player, options);
        this.controlText("Switch to Theater Mode");
        this.addClass(settings.theaterModeButtons.enterTheaterModeButton.skin);
      }
      handleClick() {
        owner.theaterModeEnterButton.hide();
        owner.theaterModeLeaveButton.show();
        console.log("entering Theater Mode...");
        eval(settings.theaterModeButtons.enterTheaterModeButton.callback);
      }
    }
    class TheaterModeLeaveButton extends Button {
      constructor(player, options) {
        super(player, options);
        this.controlText("Switch to Normal Mode");
        this.addClass(settings.theaterModeButtons.leaveTheaterModeButton.skin);
      }
      handleClick() {
        owner.theaterModeEnterButton.show();
        owner.theaterModeLeaveButton.hide();
        console.log("leaving Theater Mode...");
        eval(settings.theaterModeButtons.leaveTheaterModeButton.callback);
      }
    }

    videojs.registerComponent("TheaterModeEnterButton", TheaterModeEnterButton);
    videojs.registerComponent("TheaterModeLeaveButton", TheaterModeLeaveButton);
    this.theaterModeEnterButton = player
      .getChild("controlBar")
      .addChild("TheaterModeEnterButton", {}, 17);
    this.theaterModeLeaveButton = player
      .getChild("controlBar")
      .addChild("TheaterModeLeaveButton", {}, 18);
    this.theaterModeLeaveButton.hide();
  }

  player.on("play", () => {
    console.log("play()");
    playTrailerButton.hide();
    if (player.userActive()) {
      showTitleOverlay();
    }
    console.log("playing: ", playing);
    if (playing == "trailer") {
      player.poster(null);
      player.controls(false);
      showDomElements('playing_trailer', player.el());
    } else {
      playVideoButton.hide();
      player.controls(true);
      showDomElements('playing_content', player.el())
    }
    showHideMuteButtons();
  });

  player.on("ended", () => {
    console.log("ended");
    if (playing == "trailer") {
      console.log("trailer has ended");
      if (settings.videoUrl) {
        playing = "content";
        player.controls(true);
        player.autoplay(false);
        player.muted(false);
        player.poster(originalPoster);
        player.src(getSrc(settings.videoUrl));
      } else {
        player.hasStarted(false);
      }
      player.bigPlayButton.hide();
      playTrailerButton.show();
      showHideMuteButtons();
      showDomElements('idle', player.el())
    } else {
      console.log("video has ended");
    }
  });

  player.on("volumechange", () => {
    console.log("muted: ", player.muted());
    // console.log("paused: ", player.paused());
    if (!player.paused()) {
      showHideMuteButtons();
    }
  });

  function showHideMuteButtons() {
    console.log("showHideMuteButtons()");
    if (playing == "trailer") {
      if (player.muted()) {
        muteTrailerButton.hide();
        unmuteTrailerButton.show();
      } else {
        muteTrailerButton.show();
        unmuteTrailerButton.hide();
      }
    } else {
      muteTrailerButton.hide();
      unmuteTrailerButton.hide();
    }
  }

  function showTitleOverlay(show = true) {
    if (titleOverlay){
      titleOverlay.style.opacity = show ? '1' : '0';
    }
  }

  player.on("error", () => {
    console.log("error()");
    if (playing == "trailer") {
      console.log("trailer failed");
      playTrailerButton.hide();
      if (videoUrl) {
        playing = "content";
        player.controls(true);
        player.src(getSrc(settings.videoUrl));
      }
    }
  });

  player.on('useractive', function() {
    showTitleOverlay();
  });

  player.on('userinactive', function() {
    showTitleOverlay(false);
  });

  playTrailerButton.on("click", function () {
    console.log("playTrailer()");

    if (playing != "trailer") {
      playing = "trailer";
      player.src(settings.trailerUrl);
    }
    player.play();
  });

  muteTrailerButton.on("click", function () {
    if (playing == "trailer") {
      console.log("muteTrailer()");
      player.muted(true);
    }
  });

  unmuteTrailerButton.on("click", function () {
    if (playing == "trailer") {
      console.log("unmuteTrailer()");
      player.muted(false);
    }
  });

  playVideoButton.on("click", function () {
    console.log("playVideo");
    if (!settings.videoUrl) return;
    if (playing == "trailer") {
      playing = "content";
      player.controls(true);
      player.muted(false);
      player.src(getSrc(settings.videoUrl));
    }

    player.play();
  });
}

function getSrc(url) {
  return {
    src: url,
    type: url.endsWith(".m3u8") ? "application/x-mpegURL" : url.includes("www.youtube") || url.includes("youtu.be") ? "video/youtube" : null,
  };
}

function isLlHls(url) {
  return /ll[a-z]+\.m3u8/.test(url);
}