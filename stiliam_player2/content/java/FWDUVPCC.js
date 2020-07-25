/* FWDUVPCC */
(function (window){
var FWDUVPCC = function(
		controller
		){
		var self = this;
		var prototype = FWDUVPCC.prototype;
		var main = controller.prt;
	
		this.session;
		this.remotePlayer;
		this.currentTime;
		this.controller_do = controller;
		this.mediaStatus;
		this.isReady;
		this.playerState;
		this.isSeeking_bl;
		
		const PLAYER_STATE = {
		  IDLE: 'IDLE',
		  BUFFERING: 'BUFFERING',
		  LOADED: 'LOADED',
		  PLAYING: 'PLAYING',
		  PAUSED: 'PAUSED'
		};
	
		//##########################################//
		/* initialize  */
		//##########################################//
		self.init = function(){
			self.isReady = false;
			
			var count = 0;
		 	var loadCastInterval = setInterval(function(){
				if(window['chrome'] && window['chrome']['cast'] && window['chrome']['cast'].isAvailable) {
					console.log('Chormecast API has loaded.');
					clearInterval(loadCastInterval);	
					self.initAPI();
				}
			}, 1000);
			self.initializeController();
			self.setupCastingScreen();
		};

		//##########################################//
		/* initialize controller */
		//##########################################//
		this.initializeController =  function(){
			//controller.addListener(FWDUVPController.CAST, self.startCastingHandler);
			controller.addListener(FWDUVPController.UNCAST, self.stopCastingHandler);
		}
		
		this.stopCastingHandler = function(){
			self.stopCasting();
		}


		//##########################################//
		/* initialize API */
		//##########################################//
		this.initAPI = function(){
			
			var options = {};
			self.isReady = true;
			options.receiverApplicationId = chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID; 
			options.autoJoinPolicy = chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED;
			cast.framework.CastContext.getInstance().setOptions(options);
		
			FWDUVPlayer.keyboardCurInstance = main;
			
		    self.setupCastButton();
			self.checkButtonState();
			self.setupPlayerController();
		}
		
		//##########################################//
		/* Setup casting screen*/
		//##########################################//
		this.setupCastingScreen =  function(){
			self.cs_do = new FWDUVPDisplayObject("div");
			self.cs_do.hasTransform3d_bl = false;
			self.cs_do.hasTransform2d_bl = false;
			self.cs_do.setBackfaceVisibility();
			self.cs_do.getStyle().fontFamily = "Arial";
			self.cs_do.getStyle().fontSize= "12px";
			self.cs_do.getStyle().letterSpacing = '0.6px';
			self.cs_do.getStyle().whiteSpace= "nowrap";
			self.cs_do.getStyle().textAlign = "center";
			self.cs_do.getStyle().padding = "10px";
			self.cs_do.getStyle().paddingLeft = "12px";
			self.cs_do.getStyle().paddingRight = "12px";
			self.cs_do.setX(10);
			self.cs_do.setY(10);
			self.cs_do.getStyle().background = "#000000BB";
			self.cs_do.getStyle().color = "#FFF";
			self.cs_do.setInnerHTML('<img src="' + main.data.sknPth + 'cc-icon.png"/><span class="fwdcs_do" style="position: relative;top: -6px;left: 10px;margin-right: 10px"">Connecting to Chromecast</span>');
		}
		
		//##########################################//
		/* Setup cast button */
		//##########################################//
		this.setupCastButton =  function(){
			self.btn = document.createElement("google-cast-launcher");
			self.btn.style.display = 'block';
			self.btn.style.position = 'absolute';
			self.btn.style.opacity = 0;
			controller.ccBtn_do.screen.removeEventListener("toustart", controller.ccBtn_do.onDown);
			controller.ccBtn_do.screen.removeEventListener("touchend", controller.ccBtn_do.onMouseUp);
			controller.ccBtn_do.screen.appendChild(self.btn);
			setTimeout(function(){
				self.btn.style.display = 'block';
			}, 500);
		}
		
		FWDUVPCC.disableButton = function(){
			if(self.btn) self.btn.style.width = '0';
			if(self.controller_do && self.controller_do.ccBtn_do) self.controller_do.ccBtn_do.disable();
		}
		
		FWDUVPCC.enableButton = function(){
			if(self.btn) self.btn.style.width = '100%';
			if(self.controller_do && self.controller_do.ccBtn_do) self.controller_do.ccBtn_do.enable();
		}
		
		this.checkButtonState = function(){
			if(!self.isReady) return;
			if(main.videoType_str != FWDUVPlayer.VIDEO && main.videoType_str != FWDUVPlayer.MP3 || main.videoSourcePath_str.indexOf(".m3u8") != -1){
				self.controller_do.removeCCButton();
				self.stopCasting();
			}else{
				self.controller_do.addCCButton();
				if(self.isCasting){
					self.mainPlaying_bl = main.isPlaying_bl = false;
				}
				main.curTimeInSecond = 0;
				if(self.isCasting) self.loadMedia();
			}
		}
		
		this.isValidFormat = function(){
			if(main.videoType_str == FWDUVPlayer.VIDEO || main.videoType_str == FWDUVPlayer.MP3) return true;
			return false;
		}
		
		//##########################################//
		/* Setup remotePlayer controller */
		//##########################################//
		this.setupPlayerController = function(){
			self.remotePlayer = new cast.framework.RemotePlayer();
			self.remotePlayerController = new cast.framework.RemotePlayerController(self.remotePlayer);
			self.setVolume();
			self.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.IS_CONNECTED_CHANGED,
				function(e){
					if(self.remotePlayer.isConnected){
						controller.ccBtn_do.setButtonState(0);
						main.main_do.addChild(self.cs_do);
						self.mainPlaying_bl = main.isPlaying_bl;
						main.stop();
						self.loadMedia();
						self.isCasting = main.isCasting = true;
						console.log('Connected');
					}else{
						self.btn.style.left = '0';
						controller.ccBtn_do.setButtonState(1);
						main.curTimeInSecond = 0;
						main.isCasting = false;
						self.controller_do.disableSubtitleButton();
					
						if(self.playerState == PLAYER_STATE.PLAYING && !self.isMbl
						  && self.videoSource == main.finalVideoPath_str){
							var curTime;
							curTime = FWDUVPUtils.formatTime(self.currentTime);
							if(curTime.length == 5) curTime = "00:" + curTime;
							if(curTime.length == 7) curTime = "0" + curTime;
							main.castStartAtTime = curTime;
							self.stop();
							main.play();
						}else{
							self.stop();
							main.castStartAtTime = undefined;
						}
						try{
							main.main_do.removeChild(self.cs_do);
						}catch(e){}
						
						self.isStopped_bl = false;
						self.isCasting = false;
						self.playerState = undefined;
						console.log('Disconnected');
					}
				}
			);
		}
		
		this.play = function(){
			if(self.playerState == PLAYER_STATE.IDLE){
				self.loadMedia(true);
			}else if(self.remotePlayer.isPaused) {
			  self.remotePlayerController.playOrPause();
			}
		};
		
		this.pause = function () {
			self.playerState = PLAYER_STATE.PAUSED;
			if(!self.remotePlayer.isPaused) {
			  self.remotePlayerController.playOrPause();
			}else{
				self.playerState = PLAYER_STATE.PLAYING;
			}
		};
		
		self.allowToggle = true;
		this.togglePlayPause = function () {
			//bug stops the events to be received
			 if(self.allowToggle){
				self.remotePlayerController.playOrPause();
			 }
		};
		
		this.stop = function(){
			if(!self.isCasting) return;
			clearTimeout(self.setLoopId_to);
			clearTimeout(self.loadMediLoopId_to);
			self.stopToCheckPlaybackComplete();
			self.remotePlayerController.playOrPause();
			self.remotePlayerController.stop();
			self.controller_do.showPlayButton();
			self.controller_do.disableMainScrubber()
			if(self.controller_do.ttm) self.controller_do.ttm.hide();
			if(self.controller_do.thumbnailsPreview_do) self.controller_do.thumbnailsPreview_do.hide();
			if(self.controller_do.rewindButton_do) self.controller_do.rewindButton_do.disable();
			if(self.controller_do.downloadButton_do) self.controller_do.downloadButton_do.disable();
			self.isStopped_bl = true;
			if(main.lrgPlayBtn) main.lrgPlayBtn.show();
			self.playerState = PLAYER_STATE.IDLE;
			main.curTimeInSecond = 0;
			self.updateDisplay();
		}
		
		// Scrubb
		this.startToScrub = function(){
			self.isSeeking_bl = false;
			self.allowToggle = false;
		}
		
		this.stopToScrub = function(){
			self.isSeeking_bl = false;
			self.allowToggle = false;
			clearTimeout(self.allowToToggle);
			self.allowToToggle = setTimeout(function(){
				 self.allowToggle = true;
			},2000);
		}
		
		this.seek = function(percent){
			seekTime = Math.round(percent * self.getDuration());
			self.remotePlayer.currentTime = seekTime;
			self.remotePlayerController.seek();
		}
		
		this.getCurrentTime = function () {
			return  Math.round(this.remotePlayer.currentTime);
		};

		this.getDuration = function () {
			return Math.round(this.remotePlayer.duration);
		};
		
		this.scrubbAtTime = function(duration){
			self.allowToggle = false;
			clearTimeout(self.allowToToggle);
			self.allowToToggle = setTimeout(function(){
				 self.allowToggle = true;
			},2000);
			self.remotePlayer.currentTime = duration;
			self.remotePlayerController.seek();
		}
		
		// Volume
		this.setVolume = function(){
			self.remotePlayer.volumeLevel = main.volume;
			self.remotePlayerController.setVolumeLevel();
		}

		//##########################################//
		/* Setup remove player events */
		//##########################################//
		this.addPlayerEvents =  function(){
			
			// Triggers when the media info or the remotePlayer state changes
			self.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.MEDIA_INFO_CHANGED,
				function(event) {
					var session = cast.framework.CastContext.getInstance().getCurrentSession();
					if (!session) {
						self.mediaInfo = null;
						self.updateDisplay();
						return;
					}

					var media = session.getMediaSession();
					if (!media) {
						self.mediaInfo = null;
						self.updateDisplay();
						return;
					}

					self.mediaInfo = media.media;
					
					if(media.playerState == PLAYER_STATE.PAUSED) {
						self.changePlayPauseState(PLAYER_STATE.PAUSED);
					}else if(media.playerState == PLAYER_STATE.PLAYING){
						self.changePlayPauseState(PLAYER_STATE.PLAYING);
					}
					
					if(self.isStopped_bl) self.updateDisplay();
				}
			);
			
			this.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.IS_PAUSED_CHANGED,
				function(){
					if(self.remotePlayer.isPaused) {
						self.changePlayPauseState(PLAYER_STATE.PAUSED);
					}else if (this.playerState !== PLAYER_STATE.PLAYING) {
						self.changePlayPauseState(PLAYER_STATE.PLAYING);
					}
				}
			 );
			 
			this.changePlayPauseState = function(state){
				 if(state == PLAYER_STATE.PAUSED) {
					self.controller_do.showPlayButton();
					if(main.lrgPlayBtn) main.lrgPlayBtn.show();
					self.playerState = PLAYER_STATE.PAUSED;
				}else if (this.playerState !== PLAYER_STATE.PLAYING) {
					self.controller_do.showPauseButton();
					if(main.lrgPlayBtn) main.lrgPlayBtn.hide();
					self.playerState = PLAYER_STATE.PLAYING;
					self.controller_do.enableMainScrubber();
					if(self.controller_do.rewindButton_do) self.controller_do.rewindButton_do.enable();
					if(self.controller_do.downloadButton_do) self.controller_do.downloadButton_do.enable();
					self.startToCheckPlaybackComplete();
					self.setLoopId_to = setTimeout(function(){
						self.allowToLoop = true;
					}, 1000);
				}
				if(!self.isStopped_bl) self.updateDisplay();
			}
			
			// Update time
			this.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.CURRENT_TIME_CHANGED,
				function (event){
					var time = FWDUVPVideoScreen.formatTime(self.getCurrentTime()) + "/" + FWDUVPVideoScreen.formatTime(self.getDuration());
					self.controller_do.updateTime(time);
					if(self.getCurrentTime()) self.currentTime = self.getCurrentTime();
					if(!self.isSeeking_bl){
						self.controller_do.updateMainScrubber(self.getCurrentTime()/self.getDuration());
					}
				}
			);
			
			// Update volume
			this.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.VOLUME_LEVEL_CHANGED,
				function(){
					self.controller_do.updateVolume(self.remotePlayer.volumeLevel);
				}
			 );
		
			// Play complete handler
			this.startToCheckPlaybackComplete = function(){
				self.stopToCheckPlaybackComplete();
				self.pbc_int = setInterval(self.checkPlaybackComplete);
			}
			
			this.stopToCheckPlaybackComplete = function(){
				clearInterval(self.pbc_int);
			}
			
			this.checkPlaybackComplete = function(){
				if(self.getDuration() > 0) self.isStopped_bl = false;
				if(self.isSeeking_bl) return;
				if(self.getCurrentTime() == self.getDuration() || self.getDuration() == 0){	
					if(!self.isStopped_bl){
						self.stop();
						if(self.allowToLoop){
							if((main.data.stopVideoWhenPlayComplete_bl || main.data.playlist_ar.length == 1)
							|| (main.data.stopAfterLastVideoHasPlayed_bl && main.data.playlist_ar.length - 1 == main.id)){
								self.stop();
							}else if(main.data.shuffle_bl){
								main.playShuffle();
							}else if(main.data.loop_bl){
								self.loadMediLoopId_to = setTimeout(function(){
									self.loadMedia(true);
								}, 500);
							}else{
								main.playNext();
							}
							self.allowToLoop = false;
							return;
						}
					}
					
				}
			}
		}
	
		// Load subtitle
		this.loadSubtitle = function(){
			var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
			var media = castSession.getMediaSession();
			tracksInfoRequest = new chrome.cast.media.EditTracksInfoRequest([main.ccSS]);
			media.editTracksInfo(tracksInfoRequest, function(e){},function(e){console.log(e);});
		}
		
		// Style subtitle
		this.styleSubtitle = function(mediaInfo){
			var textTrackStyle = new chrome.cast.media.TextTrackStyle([main.ccSS]);
			textTrackStyle.backgroundColor = '#00000000', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.foregroundColor = '#FFFFFFFF', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.edgeType = 'DROP_SHADOW', // can be: "NONE", "OUTLINE", "DROP_SHADOW", "RAISED", "DEPRESSED"
			textTrackStyle.edgeColor = '#00000066', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.fontScale = 1, // transforms into "font-size: " + (fontScale*100) +"%"
			textTrackStyle.fontStyle = 'NORMAL', // can be: "NORMAL", "BOLD", "BOLD_ITALIC", "ITALIC",
			textTrackStyle.fontFamily = 'Droid Sans', // specific font family
			textTrackStyle.fontGenericFamily = 'CURSIVE', // can be: "SANS_SERIF", "MONOSPACED_SANS_SERIF", "SERIF", "MONOSPACED_SERIF", "CASUAL", "CURSIVE", "SMALL_CAPITALS",
			textTrackStyle.windowColor = '#00000066', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.windowRoundedCornerRadius = 10, // radius in px
			textTrackStyle.windowType = 'ROUNDED_CORNERS' // can be: "NONE", "NORMAL", "ROUNDED_CORNERS"
			mediaInfo.textTrackStyle = textTrackStyle;
		}
		
		// Load media
		this.loadMedia = function(autoplay){
			
			var path1 = location.origin;
			var path2 = location.pathname;
			self.videoSource = FWDUVPUtils.getValidSource(main.finalVideoPath_str);
			var posterSource = FWDUVPUtils.getValidSource(main.posterPath_str);
			
			var mediaInfo = new chrome.cast.media.MediaInfo(self.videoSource);
			mediaInfo.metadata = new chrome.cast.media.GenericMediaMetadata();
			var ct = 'video/mp4';
			if(main.videoType_str == FWDUVPlayer.MP3){
				ct = 'audio/mp3';
			}
		
			mediaInfo.contentType = ct;
			//mediaInfo.metadata.title = 'test';
			mediaInfo.metadata.images = [{'url' : posterSource}];
			self.styleSubtitle(mediaInfo);
			
			var subData = main.data.playlist_ar[main.id].subtitleSource;
			
			if(subData){
				var tracks = [];
				for(var i=0; i<subData.length - 1; i++){
					var track = new chrome.cast.media.Track(i + 1, chrome.cast.media.TrackType.TEXT);
					track.trackContentId = FWDUVPUtils.getValidSource(subData[i]['source']);
					track.trackContentType = 'text/vtt';
					track.subtype = chrome.cast.media.TextTrackType.SUBTITLES;
					track.name = subData[i]['label'];
					track.customData = null;
					tracks[i] = track;
				}
				
				track = new chrome.cast.media.Track(0, chrome.cast.media.TrackType.TEXT);
				track.trackContentId = FWDUVPUtils.getValidSource('content/subtitles/empty.vtt');
				track.subtype = chrome.cast.media.TextTrackType.SUBTITLES;
				track.name = '';
				track.customData = null;
				track.trackContentType = 'text/vtt';
				tracks.unshift(track);
				mediaInfo.tracks = tracks;
			}
			
			var request = new chrome.cast.media.LoadRequest(mediaInfo);
			
			if(self.mainPlaying_bl || autoplay || main.isThumbClick_bl){
				request.autoplay = true;
			}else{
				request.autoplay = false;
				self.pause();
			}
		
			self.playerState = PLAYER_STATE.BUFFERING;
			
			request.currentTime = main.curTimeInSecond;
			self.setVolume();
			self.addPlayerEvents();
			cast.framework.CastContext.getInstance().getCurrentSession().loadMedia(request).then(
				function() {
					if(subData){
						var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
						var media = castSession.getMediaSession();
						self.controller_do.enableSubtitleButton()
						
						tracksInfoRequest = new chrome.cast.media.EditTracksInfoRequest([main.ccSS]);
						media.editTracksInfo(tracksInfoRequest, function(e){
							if(request.autoplay) self.changePlayPauseState(PLAYER_STATE.PLAYING);
						},function(e){console.log(e);});
					}
					self.playerState = PLAYER_STATE.LOADED;
				},
				function (errorCode) {
					self.playerState = PLAYER_STATE.IDLE;
					console.log('Remote media load error: ' + errorCode);
					self.updateDisplay();
			  }
		  )
		 
		}
		
		//##########################################//
		/* Setup remove remotePlayer */
		//##########################################//
		this.updateDisplay = function(param){
			var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
		
			if(castSession && castSession.getMediaSession() && castSession.getMediaSession().media){
				var media = castSession.getMediaSession();
				var mediaInfo = media.media;
				
				if(mediaInfo.metadata){
					self.mediaTitle = mediaInfo.metadata.title;
					mediaEpisodeTitle = mediaInfo.metadata.episodeTitle;
					// Append episode title if present
					self.mediaTitle = mediaEpisodeTitle ? self.mediaTitle + ': ' + mediaEpisodeTitle : self.mediaTitle;
					// Do not display mediaTitle if not defined.
					self.mediaTitle = (self.mediaTitle) ? self.mediaTitle + ' ' : '';
					mediaSubtitle = mediaInfo.metadata.subtitle;
					mediaSubtitle = (mediaSubtitle) ? mediaSubtitle + ' ' : '';
					self.deviceName = castSession.getCastDevice().friendlyName;
				}
			}
			var ctn = document.getElementsByClassName("fwdcs_do")[0];
			if(self.deviceName && ctn) ctn.innerHTML = self.mediaTitle + self.playerState + ' on ' + self.deviceName;
		}
		
		this.stopCasting = function(){
			try{
				var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
				castSession.endSession(true);
			}catch(e){}
		}
		
		self.init();
	};
	
	/* set prototype */
	FWDUVPCC.setPrototype = function(){
		FWDUVPCC.prototype = null;
		FWDUVPCC.prototype = new FWDUVPEventDispatcher("div");
	};

	FWDUVPCC.prototype = null;
	window.FWDUVPCC = FWDUVPCC;
}(window));