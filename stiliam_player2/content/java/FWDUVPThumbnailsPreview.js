/* FWDUVPThumbnailsPreview */
(function (window){
var FWDUVPThumbnailsPreview = function(
		controller
		){
		var self = this;
		var prototype = FWDUVPThumbnailsPreview.prototype;
		self.main = controller.prt;
		self.vtt_ar;
		self.cWidth = controller.data.thumbnailsPreviewWidth;
		self.cHeight = controller.data.thumbnailsPreviewHeight;
		self.bkColor =  controller.data.thumbnailsPreviewBackgroundColor;
		self.borderColor = controller.data.thumbnailsPreviewBorderColor;
		self.labelBkColor = controller.data.thumbnailsPreviewLabelBackgroundColor;
		self.labelFontColor = controller.data.thumbnailsPreviewLabelFontColor;
		self.duration;
		self.borderSize = 1;
		
		this.isLded = false;
		this.isLded = false;
		this.isMbl = FWDUVPUtils.isMobile;
	
	
		//##########################################//
		/* initialize self */
		//##########################################//
		self.init = function(){
			self.getStyle().zIndex = 1;
			self.setOverflow("visible");
			self.getStyle().pointerEvents = 'none';
			self.setBkColor(self.borderColor);
			self.setWidth(self.cWidth + self.borderSize * 2);
			self.setHeight(self.cHeight + self.borderSize * 2);
			self.mainHld = new FWDUVPDisplayObject("div");
			self.mainHld.setWidth(self.cWidth);
			self.mainHld.setHeight(self.cHeight);
			self.mainHld.setX(self.borderSize);
			self.mainHld.setY(self.borderSize);
			self.mainHld.setBkColor(self.bkColor);
			
			self.addChild(self.mainHld);
			
			self.pointerHolder_do = new FWDUVPDisplayObject("div");
			self.pointerHolder_do.setOverflow('visible');
			self.addChild(self.pointerHolder_do);
			
			self.text_do = new FWDUVPDisplayObject("div");
			self.text_do.hasTransform3d_bl = false;
			self.text_do.hasTransform2d_bl = false;
			self.text_do.setBackfaceVisibility();
			self.text_do.setDisplay("inline-block");
			self.text_do.getStyle().fontFamily = "Arial";
			self.text_do.getStyle().fontSize= "12px";
			self.text_do.setBkColor(self.labelBkColor);
			self.text_do.getStyle().color = self.labelFontColor;
			self.text_do.getStyle().whiteSpace= "nowrap";
			self.text_do.getStyle().fontSmoothing = "antialiased";
			self.text_do.getStyle().webkitFontSmoothing = "antialiased";
			self.text_do.getStyle().textRendering = "optimizeLegibility";
			self.text_do.getStyle().padding = "6px";
			self.text_do.getStyle().paddingTop = "4px";
			self.text_do.getStyle().paddingBottom = "4px";
			self.text_do.screen.className = 'preview-thubnail-text';
		
			self.pointerHolder_do.addChild(self.text_do);
			
			self.pointer_do = new FWDUVPDisplayObject("div");
			self.pointer_do.setBkColor(self.labelBkColor);
	
			self.pointer_do.screen.style = "border: 4px solid transparent; border-top-color: " + self.borderColor + ";";
			self.pointer_do.setWidth(0);
			self.pointerHolder_do.addChild(self.pointer_do);
		
			self.hide();
			self.setAlpha(0);
			self.setVisible(false);
			self.setY(-100);
		};
		
		
		this.stopToLoad = function(){
			if(self.xhr != null){
				try{self.xhr.abort();}catch(e){}
				self.xhr.onreadystatechange = null;
				self.xhr.onerror = null;
				self.xhr = null;
			}
			this.isLded = false;
		};
		
	
		this.load = function(path){
			self.vtt_ar = [];
			self.sourceURL_str = path;
			self.prevSourceURL_str = self.sourceURL_str;
			self.xhr = new XMLHttpRequest();
			self.xhr.onreadystatechange = self.onLoad;
			self.xhr.onerror = self.onError;
			
			try{
				self.xhr.open("get", self.sourceURL_str + "?rand=" + parseInt(Math.random() * 99999999), true);
				self.xhr.send();
			}catch(e){
				var message = e;
				if(e){if(e.message)message = e.message;}
			}
		}
		
		
		this.onLoad = function(e){
			var response;
			if(self.xhr.readyState == 4){
				if(self.xhr.status == 404){
					self.dispatchEvent(FWDUVPData.LOAD_ERROR, {text:"Thumbnails preview .vtt file not found: <font color='#FF0000'>" + self.sourceURL_str + "</font>"});
				}else if(self.xhr.status == 408){
					self.dispatchEvent(FWDUVPData.LOAD_ERROR, {text:"Loadiong thumbnails preview .vtt file file file request load timeout!"});
				}else if(self.xhr.status == 200){
					self.vtt_txt = self.xhr.responseText;
					self.parseVtt(self.vtt_txt);
					self.positionPointer();
					self.add();
					
					self.setLabel('00:00', 0);
				}
			}
			
			self.dispatchEvent(FWDUVPThumbnailsPreview.LOAD_COMPLETE);
		};
		
		this.onError = function(e){
			try{
				if(window.console) console.log(e);
				if(window.console) console.log(e.message);
			}catch(e){};
			self.dispatchEvent(FWDUVPThumbnailsPreview.LOAD_ERROR, {text:"Error loading thumbnails preview .vtt file : <font color='#FF0000'>" + self.sourceURL_str + "</font>."});
		};
		
		//##########################################//
		/* set label */
		//##########################################//
		this.setLabel = function(label, duration){
			
			if(label === undefined ) return;
			
			var imgSrc = "";
			if(self.vtt_ar){
				for(var i=0; i<self.vtt_ar.length; i++){
					start = self.vtt_ar[i].startDuration;
					end = self.vtt_ar[i].endDuration;
					if(start <= duration  && end > duration ){
						imgSrc = self.vtt_ar[i].imagePath;
						if(imgSrc != self.prevImgSrc){
							self.mainHld.getStyle().background = 'url("' +imgSrc + '") no-repeat center center';
							self.mainHld.getStyle().backgroundSize = "cover";
							self.prevImgSrc = imgSrc;
						}
						break;
					};
				}
			}
			
			
			self.text_do.setInnerHTML(label);
			setTimeout(function(){
				if(self == null) return;
					self.pointerHolder_do.setWidth(self.text_do.getWidth());
					self.pointerHolder_do.setHeight(self.text_do.getHeight());
					self.positionPointer();
				},20);
		};
		
		this.positionPointer = function(offsetX){
			var finalX;
			var finalY;
			
			if(!offsetX) offsetX = 0;
			
			finalX = parseInt((self.w - self.text_do.getWidth())/2) + offsetX;
			finalY = self.h - self.text_do.getHeight();
			self.pointer_do.setX(parseInt( self.text_do.getWidth() - 8)/2);
			self.pointer_do.setY(self.text_do.getHeight());
			self.pointerHolder_do.setX(finalX);
			self.pointerHolder_do.setY(finalY - self.borderSize);
		};
		
		
		//##########################################//
		/* parse vtt file */
		//##########################################//
		self.parseVtt = function(file_str){
			 self.isLded = true;
			 function strip(s) {
				if(s ==  undefined) return "";
		        return s.replace(/^\s+|\s+$/g,"");
		     }
			 
			file_str = file_str.replace(/\r\n|\r|\n/g, '\n');
			file_str = strip(file_str);
		    var srt_ = file_str.split('\n\n');
		    
		    var cont = 0;
			
		    for(s in srt_) {
		        var st = srt_[s].split('\n');
		        if(st.length >=2) {
		            //define variable type as Object
		            self.vtt_ar[cont] = {};
		            self.vtt_ar[cont].start = strip(st[0].split(' --> ')[0]);
		            self.vtt_ar[cont].end = strip(st[0].split(' --> ')[1]);
					self.vtt_ar[cont].imagePath = strip(st[1]);
		            self.vtt_ar[cont].startDuration = FWDUVPUtils.formatTimeWithMiliseconds(self.vtt_ar[cont].start);
		            self.vtt_ar[cont].endDuration = FWDUVPUtils.formatTimeWithMiliseconds(self.vtt_ar[cont].end);
		        }
		        cont++;
		    }
			self.vtt_ar.splice(0,1);
		};
		
		//################################################//
		/* Add remove from DOM */
		//################################################//
		this.add = function(){
			controller.addChild(self);
		}
		
		
		this.remove =  function(){
			self.stopToLoad();
			self.hide();
			if(controller.contains(self)) controller.removeChild(self);
		}
		
		//################################################//
		/* Hide and show */
		//################################################//
		this.show = function(animate){
			if(!controller.contains(self)) return;
			
			self.duration = controller.prt.totalTimeInSeconds;
			if(!self.duration) return;
			self.isShowed_bl = true;
			clearTimeout(self.hideWithDelayId_to);
			FWDAnimation.killTweensOf(self);
			clearTimeout(self.showWithDelayId_to);
			self.showWithDelayId_to = setTimeout(self.showFinal, 100);
		};
		
		this.showFinal = function(){
			self.setVisible(true);
			FWDAnimation.to(self, .4, {alpha:1, onComplete:function(){self.setVisible(true);}, ease:Quart.easeOut});
		};
		this.hide = function(){
			if(!controller.contains(self)) return;
			if(!self.isShowed_bl) return;
			clearTimeout(self.hideWithDelayId_to);
			self.hideWithDelayId_to = setTimeout(function(){
				clearTimeout(self.showWithDelayId_to);
				FWDAnimation.killTweensOf(self);
				self.setVisible(false);
				self.isShowed_bl = false;	
				self.setAlpha(0);
			}, 100);
			
		};
	
		
		self.init();
	};
	
	/* set prototype */
	FWDUVPThumbnailsPreview.setPrototype = function(){
		FWDUVPThumbnailsPreview.prototype = null;
		FWDUVPThumbnailsPreview.prototype = new FWDUVPTransformDisplayObject("div");
	};
	
	FWDUVPThumbnailsPreview.LOAD_ERROR = 'loadError';
	FWDUVPThumbnailsPreview.LOAD_COMPLETE = 'loadComplete';
	FWDUVPThumbnailsPreview.START_TO_SCRUB = "startToScrub";
	FWDUVPThumbnailsPreview.SCRUB = "scrub";
	FWDUVPThumbnailsPreview.STOP_TO_SCRUB = "stopToScrub";
	FWDUVPThumbnailsPreview.prototype = null;
	window.FWDUVPThumbnailsPreview = FWDUVPThumbnailsPreview;
}(window));