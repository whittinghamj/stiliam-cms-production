/* FWDUVPIMA */
(function (window){
var FWDUVPIMA = function(
		prt
		){
		var self = this;
		var prototype = FWDUVPIMA.prototype;
		this.controller_do = prt.controller_do;
		this.isStopped = true;
		this.videoScreen_do = prt.videoScreen_do;
		this.isReady = false;
		this.isMbl = FWDUVPUtils.isMobile;
	
		//##########################################//
		/* initialize  */
		//##########################################//
		self.init = function(){
			self.resizeAndPosition();
		};
		
		/*
		 * Setup IMA.
		 * ------------------------------------------------------
		*/
		this.setUpIMA = function(){
			if(!self.adContainer){
				self.adContainer =  new FWDUVPDisplayObject("div");
				self.adContainer.getStyle().position = 'relative';
				self.addChild(self.adContainer);
				prt.videoHolder_do.addChildAt(self, prt.videoHolder_do.getChildIndex(prt.dumyClick_do));
				
				self.video_el = self.videoScreen_do.video_el;
			
				// Create the ad display container.
				self.adDisplayContainer = new google.ima.AdDisplayContainer(self.adContainer.screen, self.video_el);
			}
			
			// Create ads loader.
			self.adsLoader = new google.ima.AdsLoader(self.adDisplayContainer);
			self.adsLoader.addEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, self.onAdsManagerLoaded,false);
			self.adsLoader.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR,self.onAdError,false);
		}
		
		this.onAdsManagerLoaded = function(e){
			
			// Get the ads manager.
			var adsRenderingSettings = new google.ima.AdsRenderingSettings();
			adsRenderingSettings.restoreCustomPlaybackStateOnAdBreakComplete = true;
		
			// videoContent should be set to the content video element.
			self.adsManager = e.getAdsManager(self.video_el);
			
			// Add listeners to the required events.
			self.adsManager.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR, self.onAdError);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.LOADED, self.onAdLoaded);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED, self.onContentPauseRequested);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED, self.onContentResumeRequested);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.AD_PROGRESS, self.onContentProgress);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.STARTED, self.onContentPlay);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.RESUMED, self.onContentPlay);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.PAUSED, self.onContentPaused);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.ALL_ADS_COMPLETED, self.onAllAdsComplete);
			self.adsManager.addEventListener(google.ima.AdEvent.Type.CLICK, self.onClick);
			
			if(prt.data.aom_bl) self.setVolume(0);
			
			self.isReady = true;
			if(prt.hider) prt.hider.stop();
			if(prt.data.autoPlay_bl || prt.data.aom_bl || prt.isThumbClick_bl && FWDUVPPassword.isCorect) self.play(e);
		}
		
		this.onAdError = function(e){
			console.log('IMA ERROR - ' + e.getError());
			prt.isIMA = false;
			self.stop();
			if(self.videoScreen_do.isSafeToBeControlled_bl || prt.data.autoPlay_bl || prt.isThumbClick_bl && FWDUVPPassword.isCorect){
				prt.videoPoster_do.hide(true);
				self.videoScreen_do.play();
				if(prt.hider) prt.hider.start();
			}
		}
		
		this.onAdLoaded = function(e){
			self.isLinear = e.getAd().isLinear();
			self.resizeAndPosition();
			if(!self.isLinear) self.videoScreen_do.play();
		}
		
		this.onContentPauseRequested = function(e){
			self.started = true;
			self.isLinear = e.getAd().isLinear();
			self.resizeAndPosition();
			prt.subtitle_do.stopToLoadSubtitle();
			prt.dumyClick_do.setX(-8000);

			if(self.controller_do){
				self.controller_do.disableAtbButton();
				self.controller_do.disableRewindButton();
				self.controller_do.disableSubtitleButton();
				if(window['FWDUVPCC']) FWDUVPCC.disableButton();
				self.controller_do.disableMainScrubber();
				self.controller_do.updateHexColorForScrubber(true);
				self.controller_do.disablePlaybackRateButton();
				self.controller_do.disableQualtyButton();
				self.controller_do.resetsAdsLines();
			}
			self.videoScreen_do.pause();

			// This function is where you should setup UI for showing ads (e.g.
			// display ad timer countdown, disable seeking etc.)
			// setupUIForAds();
		}

		this.onContentResumeRequested = function(){
			self.started = false;
			self.isPlaying = false;
			prt.dumyClick_do.setX(0);
			prt.videoPoster_do.hide(true);
			if(prt.hider) prt.hider.start();
			self.videoScreen_do.play();
			if(self.controller_do){
				if(window['FWDUVPCC']) FWDUVPCC.enableButton();
				self.controller_do.enableAtbButton();
				self.controller_do.enableRewindButton();
				if(self.controller_do.ccBtn_do) self.controller_do.ccBtn_do.enable();
				self.controller_do.enableMainScrubber();
				self.controller_do.updateHexColorForScrubber(false);
				self.controller_do.enableQualtyButton();
				self.controller_do.enablePlaybackRateButton();
			}
			var curPlaylist = prt.data.playlist_ar[prt.id];
			if(curPlaylist.subtitleSource) prt.loadSubtitle(curPlaylist.subtitleSource[curPlaylist.subtitleSource.length - 1 - curPlaylist.startAtSubtitle]["source"]);
			self.cuepointsId_to = setTimeout(self.setupCuepoints, 500);
		  // This function is where you should ensure that your UI is ready
		  // to play content. It is the responsibility of the Publisher to
		  // implement this function when necessary.
		  // setupUIForContent();
		}
		
		this.onContentProgress = function(e){
			var curTime = FWDUVPVideoScreen.formatTime(e.w.currentTime);
			var totalTime = FWDUVPVideoScreen.formatTime(e.w.duration);
			var percent = e.w.currentTime /e.w.duration;
			prt.videoScreenUpdateTimeHandler({curTime: curTime, totalTime:totalTime, seconds:e.w.currentTime, totalTimeInSeconds:e.w.duration});
			prt.videoScreenUpdateHandler({percent:percent});
		}
		
		this.onContentPlay = function(e){
			self.isPlaying = true;
			prt.videoScreenPlayHandler();
		}
		
		this.onContentPaused = function(e){
			self.isPlaying = false;
			prt.videoScreenPauseHandler();
		}
		
		this.onAllAdsComplete = function(){
			if(self.hasPostRoll){
				console.log('all ads competessss');
				self.hasPostRoll = false;
				prt.videoScreenPlayCompleteHandler();
			}
		}
		
		this.onClick = function(){
			self.pause();
		}
		
		/*
		 * Destroy IMA.
		 * ------------------------------------------------------
		*/
		this.destroyIMA = function(){
			if(!self.adsLoader) return;
			if(self.adsLoader){
				self.adsLoader.destroy();
			}
			self.adsLoader = null;
			
			if(self.adsManager){
				self.adsManager.destroy();
			}
			self.adsManager = null;
		}
		
		/*
		 * Setup quepoints.
		 * ------------------------------------------------------
		*/
		this.setupCuepoints = function(){
			self.curpointsData = self.adsManager.getCuePoints();
			if(!self.curpointsData) return
			
			if(!self.ads_ar){
				self.cuePointsIma = self.curpointsData;
				self.ads_ar = [];
				
				for(var i=0; i<self.cuePointsIma.length; i++){
					var cp = self.cuePointsIma[i];
					if(cp == -1){
						cp = self.videoScreen_do.video_el.duration; 
						self.hasPostRoll = true;
					}
					self.ads_ar[i] = {timeStart:cp}
					
				}
			}
		
			if(self.controller_do){
				self.controller_do.setupAdsLines(self.ads_ar, 0,0, true);
				self.controller_do.positionAdsLines(self.videoScreen_do.video_el.duration);
			}
		}
		
		this.updateCuepointLines = function(t){
		
			if(self.started || !self.curpointsData) return;	
			if(self.controller_do){
				var ad;
				var line;
				for(var i=0; i<self.ads_ar.length; i++){
					ad = self.ads_ar[i];
					line = self.controller_do.lines_ar[i];
					if(t > ad.timeStart && !ad.played_bl){
						ad.played_bl = true;
						if(self.controller_do.line_ar) self.controller_do.line_ar[i].setVisible(false);
						break;
					}
				}
			}
		}
		
		/*
		 * Resize and position
		 * ------------------------------------------------------
		*/
		this.resizeAndPosition = function(){
			
			var offsetY = 0;
			if(prt.controller_do){
				if(!self.isLinear) offsetY = self.controller_do.sH;
			}
		
			self.sW = prt.tempVidStageWidth;
			self.sH = prt.tempVidStageHeight;
			self.setWidth(self.sW);
			self.setHeight(self.sH);
			self.setY(-offsetY);
			if(self.adContainer){
				self.adContainer.setWidth(self.sW);
				self.adContainer.setHeight(self.sH);
			}
			if(self.adsManager) self.adsManager.resize(self.sW, self.sH, google.ima.ViewMode.NORMAL);
			if(self.controller_do){
				self.controller_do.positionAdsLines(self.videoScreen_do.video_el.duration);
			}
		}
		 
		/*
		 * Set source.
		 * ------------------------------------------------------
		*/
		this.setSource = function(source){
			self.source = source;
			self.stop();
			self.videoScreen_do.initVideo();
			self.videoScreen_do.video_el.load();
			//if(prt.data.autoPlay_bl || prt.data.aom_bl || prt.isThumbClick_bl && FWDUVPPassword.isCorect) prt.videoPoster_do.hide(true);
			prt.videoScreenUpdateHandler({percent:0});
			prt.dumyClick_do.setX(-8000);
			if(self.controller_do){
				self.controller_do.disablePlaybackRateButton();
			}
			self.setUpIMA();
			
			var adsRequest = new google.ima.AdsRequest();
			adsRequest.adTagUrl = source;
			
			// Specify the linear and nonlinear slot sizes. This helps the SDK to
			// select the correct creative if multiple are returned.
			adsRequest.linearAdSlotWidth = prt.tempVidStageWidth;
			adsRequest.linearAdSlotHeight = prt.tempVidStageHeight;
			adsRequest.nonLinearAdSlotWidth = prt.tempVidStageWidth;
			adsRequest.nonLinearAdSlotHeight = prt.tempVidStageHeight;
			self.adsLoader.requestAds(adsRequest);
		}
		
		/*
		 * Control methods.
		 * ------------------------------------------------------
		*/
		this.play =  function(e){
			if(!self.isReady) return;
		
			// Initialize the container. Must be done via a user action on mobile devices.
			if(self.isStopped){
				self.adDisplayContainer.initialize();
				try{
					// Initialize the ads manager. Ad rules playlist will start at this time.
					self.adsManager.init(640, 360, google.ima.ViewMode.NORMAL);
					// Call play to start showing the ad. Single video and overlay ads will
					// start at this time; the call will be ignored for ad rules.
					self.resizeAndPosition();
					self.adsManager.start();
					self.isStopped = false;
				}catch (adError) {
					console.log(adError);
					// An error may be thrown if there was a problem with the VAST response.
					self.videoScreen_do.play();
				}
			}else{
				if(self.started){
					self.adsManager.resume();
				}else{
					self.videoScreen_do.play();
				}
			}
		}
		
		this.pause = function(){
			if(!self.isReady) return;
			if(self.started){
				self.adsManager.pause();
			}else{
				self.videoScreen_do.pause();
			}
		}
		
		this.togglePlayPause = function(){
			if(self.isPlaying){
				self.pause();
			}else{
				self.play();
			}
		}
		
		this.setVolume = function(vol){
			if(vol != undefined) self.volume = vol;
			if(self.adsManager){
				self.adsManager.setVolume(self.volume);
			}
		};
		
		this.playPostRoll =  function(){
			self.adsLoader.contentComplete();
		}
		
		this.stop = function(resetIma){
			
			self.isReady = false;
			self.started = false;
			self.isStopped = true;
			self.isLinear = true;
			self.hasPostRoll = false;
			clearTimeout(self.cuepointsId_to);
			self.setWidth(0);
			self.ads_ar = self.curpointsData = null;
			if(self.adsManager) self.adsManager.stop();
			if(self.controller_do){
				if(self.controller_do.ccBtn_do) self.controller_do.ccBtn_do.enable();
				self.controller_do.updateHexColorForScrubber(false);
			}
			prt.dumyClick_do.setX(0);
			self.destroyIMA();
		}
	
		self.init();
	};
	
	/* set prototype */
	FWDUVPIMA.setPrototype = function(){
		FWDUVPIMA.prototype = null;
		FWDUVPIMA.prototype = new FWDUVPDisplayObject("div");
	};

	FWDUVPIMA.prototype = null;
	window.FWDUVPIMA = FWDUVPIMA;
}(window));