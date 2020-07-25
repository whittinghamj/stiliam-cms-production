/* FWDUVPVast */
(function (window){
var FWDUVPVast = function(
		data
		){
		var self = this;
		var prototype = FWDUVPVast.prototype;
		self.id = -1;
	
		//##########################################//
		/* initialize  */
		//##########################################//
		self.init = function(){};
		
		//####################################//
		/* load vast */
		//####################################//
		this.setSource = function(source){
			self.closeVast();
			self.isVmapTimesFixed = false;
			data.adsSource_ar = [];
			self.vastData_ar = [];
			self.parsedLienarVastAds_ar = [];
			self.parsedNonLienarVastAds_ar = [];
			self.countVastDataLoaded = 0;
			self.totalDataVastToLoad = 1;
			self.load(source);
			data.dispatchEvent(FWDUVPData.VAST_LOADING);
		}
		
		this.closeVast = function(){
			if(self.vastXHR){
				self.vastXHR.onreadystatechange = null;
				self.vastXHR.onerror = null;
				self.vastXHR.abort();
				self.vastXHR = null;
			}
		}
		
		this.load = function(source){
			var tempObj;
			
			self.vastXHR = new XMLHttpRequest();

			self.vastXHR.onreadystatechange = function(e){
				if(self.vastXHR.readyState == 4){
					if(self.vastXHR.status == 200){
					
						var respObj = FWDUVPUtils.xmlToJson(self.vastXHR.responseXML);

						// Wrapper.
						try{
							var url = respObj['VAST']['Ad']['Wrapper']['VASTAdTagURI']['#cdata-section'];
							if(!url){
								url = respObj['VAST']['Ad']['Wrapper']['VASTAdTagURI']['#text'];
							}
							self.load(url);
							return;
						}catch(e){}
						
						// VMAP.
						if(respObj['vmap:VMAP']){
							if(!respObj['vmap:VMAP']['vmap:AdBreak'].length){
								var obj = {};
								obj.timeOffset = respObj['vmap:VMAP']['vmap:AdBreak']['@attributes']['timeOffset'];
								obj.breakType = respObj['vmap:VMAP']['vmap:AdBreak']['@attributes']['breakType'];
								obj.breakId = respObj['vmap:VMAP']['vmap:AdBreak']['@attributes']['breakId'];
								obj.source = respObj['vmap:VMAP']['vmap:AdBreak']['vmap:AdSource']['vmap:AdTagURI']['#cdata-section'];
								self.vastData_ar.push(obj);
							}else{
								for(var i=0; i<respObj['vmap:VMAP']['vmap:AdBreak'].length; i++){
									var obj = {};
									obj.timeOffset = respObj['vmap:VMAP']['vmap:AdBreak'][i]['@attributes']['timeOffset'];
									obj.breakType = respObj['vmap:VMAP']['vmap:AdBreak'][i]['@attributes']['breakType'];
									obj.breakId = respObj['vmap:VMAP']['vmap:AdBreak'][i]['@attributes']['breakId'];
									obj.source = respObj['vmap:VMAP']['vmap:AdBreak'][i]['vmap:AdSource']['vmap:AdTagURI']['#cdata-section'];
									self.vastData_ar.push(obj);
								}
							}
							self.totalDataVastToLoad = self.vastData_ar.length;
							self.load(self.vastData_ar[self.countVastDataLoaded]['source']);
							
							return;
						}
						
						var linearVast_ar = [];
						var nonLinearVast_ar = [];
						var respObj = FWDUVPUtils.xmlToJson(self.vastXHR.responseXML).VAST;
						
						if(!respObj["Ad"]){
							data.dispatchEvent(FWDUVPData.LOAD_ERROR, {text:"No <font color='#FF0000'> &lt;ad&gt; </font> tag was found in the VAST file. Invalid VAST file."});
							return;
						}else{
							if(!respObj["Ad"].length) respObj["Ad"] = [respObj["Ad"]];
							for(var i=0; i< respObj["Ad"].length; i++){
								tempObj = {};
								tempObj.id = respObj["Ad"][i]["@attributes"]["id"];
								tempObj.sequence = respObj["Ad"][i]["@attributes"]["sequence"];
								tempObj.startTime = respObj["Ad"][i]["@attributes"]["startTime"];
								if(!tempObj.sequence) tempObj.sequence = i;
								
								if(!respObj["Ad"][i]["InLine"]){
									data.dispatchEvent(FWDUVPData.LOAD_ERROR, {text:"No <font color='#FF0000'> &lt;InLine&gt; </font>tag was found in the VAST xml file."});
									return;
								}
								tempObj["InLine"] = {};
								
								//impression
								tempObj["InLine"]["Impression"] = undefined;
								if(respObj["Ad"][i]["InLine"]["Impression"]){
									if(respObj["Ad"][i]["InLine"]["Impression"]["#cdata-section"]){
										tempObj["InLine"]["Impression"] = respObj["Ad"][i]["InLine"]["Impression"]["#cdata-section"];
									}else{
										tempObj["InLine"]["Impression"] = respObj["Ad"][i]["InLine"]["Impression"]["#text"];
									}
								}
								
								if(!respObj["Ad"][i]["InLine"]["Creatives"]["Creative"].length){
									respObj["Ad"][i]["InLine"]["Creatives"]["Creative"] = [respObj["Ad"][i]["InLine"]["Creatives"]["Creative"]]
								}
								
							
								if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"].length){
									for(var j=0; j<respObj["Ad"][i]["InLine"]["Creatives"]["Creative"].length; j++){
										
										//non linear
										if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']){
											tempObj["InLine"]["NonLinear"] = {};
											tempObj["InLine"]['type'] = 'nonlinear';
											tempObj["InLine"]["NonLinear"]['width'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['@attributes']['width'];
											tempObj["InLine"]["NonLinear"]['height'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['@attributes']['height'];
											tempObj["InLine"]["NonLinear"]['duration'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['@attributes']['minSuggestedDuration'];
											if(!tempObj["InLine"]["NonLinear"]['duration']) tempObj["InLine"]["NonLinear"]['duration'] = '00:00:05';
											try{
												tempObj["InLine"]["NonLinear"]['width'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['@attributes']['width'];
												tempObj["InLine"]["NonLinear"]['height'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['@attributes']['height'];
											}catch(e){}
											
											try{
												tempObj["InLine"]["NonLinear"]['ClickThroug'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['NonLinearClickThrough']['#cdata-section'];
												tempObj["InLine"]["NonLinear"]['ClickTracking'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['NonLinearClickTracking']['#cdata-section'];
											}catch(e){}
											if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['StaticResource']){
												tempObj["InLine"]["NonLinear"]['source'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['StaticResource']['#cdata-section'];
											}else if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['IFrameResource']){
												tempObj["InLine"]["NonLinear"]['source'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['IFrameResource']['#cdata-section'];
											}
											try{
												tempObj["InLine"]["NonLinear"]['type'] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]['NonLinearAds']['NonLinear']['StaticResource']['@attributes']['creativeType'];
											}catch(e){}
											
											nonLinearVast_ar.push(tempObj);
										}
									
										//linear ads
										if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]){
											
											if(!respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["MediaFiles"]["MediaFile"].length){
												respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["MediaFiles"]["MediaFile"] = [respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["MediaFiles"]["MediaFile"]];
											}
											
											tempObj["InLine"]["Linear"] = {};
											tempObj["InLine"]['type'] = 'linear';
										
											//video source
											var allVideosObj = [];
										
											for(var k = 0; k<respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["MediaFiles"]["MediaFile"].length; k++){
												var vid = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["MediaFiles"]["MediaFile"][k];
												if(vid['#cdata-section']){
													if(vid['#cdata-section'].match(/\.mp3|\.mp4/ig)){
														allVideosObj.push(vid);
													}
												}else{
													if(vid['#text'].match(/\.mp3|\.mp4/ig)){
														allVideosObj.push(vid);
													}
												}
											}
											var videoSource;
											var correctIndex = 0;
											
											prop:for(var m=0;  m<allVideosObj.length; m++){
												if(window["innerWidth"] >= allVideosObj[m]["@attributes"]["width"]){
													correctIndex = m;
													break prop;
												}
											}
											
											if(allVideosObj[correctIndex]["#cdata-section"]){
												tempObj["InLine"]["Linear"]["videoSource"]  = allVideosObj[correctIndex]["#cdata-section"];
											}else{
												tempObj["InLine"]["Linear"]["videoSource"] = allVideosObj[correctIndex]["#text"];
											}
											
											//duration
											if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["Duration"]){
												if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["Duration"]["#cdata-section"]){
													tempObj["InLine"]["Linear"]["Duration"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["Duration"]["#cdata-section"];
												}else if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["Duration"]["#text"]){
													tempObj["InLine"]["Linear"]["Duration"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["Duration"]["#text"];
												}
											}
											
											//skip offset
											tempObj["InLine"]["Linear"]["skipoffset"] = undefined;
											if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["@attributes"]
											   && respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["@attributes"]["skipoffset"]
											){
												tempObj["InLine"]["Linear"]["skipoffset"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["@attributes"]["skipoffset"];
											}
											
											
											if(tempObj["InLine"]["Linear"]["skipoffset"]){
												tempObj["InLine"]["Linear"]["skipoffset"] = tempObj["InLine"]["Linear"]["skipoffset"].substr(0, 8);
												if(tempObj["InLine"]["Linear"]["Duration"] && tempObj["InLine"]["Linear"]["skipoffset"].indexOf("%") != -1){
													var tempSkipOffset =  Math.round(FWDUVPUtils.getSecondsFromString(tempObj["InLine"]["Linear"]["Duration"]) * (tempObj["InLine"]["Linear"]["skipoffset"].substr(0, tempObj["InLine"]["Linear"]["skipoffset"].length -1)/100));
													tempObj["InLine"]["Linear"]["skipoffset"] = FWDUVPUtils.formatTime(tempSkipOffset, true);
												}
												
											}
											
											if(tempObj["InLine"]["Linear"]["skipoffset"]){
												tempObj["InLine"]["Linear"]["skipoffset"] = FWDUVPUtils.getSecondsFromString(tempObj["InLine"]["Linear"]["skipoffset"]);
												if(tempObj["InLine"]["Linear"]["Duration"] && FWDUVPUtils.getSecondsFromString(tempObj["InLine"]["Linear"]["Duration"]) <= tempObj["InLine"]["Linear"]["skipoffset"]){
													tempObj["InLine"]["Linear"]["skipoffset"] = undefined;
												}
											}
											
											//tracking events
											tempObj["InLine"]["Linear"]["TrackingEvents"] = undefined;
											if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["TrackingEvents"]["Tracking"]){
												tempObj["InLine"]["Linear"]["TrackingEvents"] = [];
												for(var p=0; p<respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["TrackingEvents"]["Tracking"].length; p++){
													tempObj["InLine"]["Linear"]["TrackingEvents"].push({
														event:respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["TrackingEvents"]["Tracking"][p]["@attributes"]["event"]
													})
													
													if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["TrackingEvents"]["Tracking"][p]["#cdata-section"]){
														tempObj["InLine"]["Linear"]["TrackingEvents"][p].URI = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["TrackingEvents"]["Tracking"][p]["#cdata-section"];
													}else{
														tempObj["InLine"]["Linear"]["TrackingEvents"][p].URI = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["TrackingEvents"]["Tracking"][p]["#text"];
													}
												}
											}
											
											//video clicks
											if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]){
												if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickThrough"]){
													if(tempObj["InLine"]["Linear"]["ClickThrough"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickThrough"]["#cdata-section"]){
														tempObj["InLine"]["Linear"]["ClickThrough"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickThrough"]["#cdata-section"];
													}else{
														tempObj["InLine"]["Linear"]["ClickThrough"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickThrough"]["#text"]
													}
												}
											
												if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickTracking"]){
													
													if(respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickTracking"]["#cdata-section"]){
														tempObj["InLine"]["Linear"]["ClickTracking"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickTracking"]["#cdata-section"];
													}else{
														tempObj["InLine"]["Linear"]["ClickTracking"] = respObj["Ad"][i]["InLine"]["Creatives"]["Creative"][j]["Linear"]["VideoClicks"]["ClickTracking"]["#text"];
													}
												}
											}	
											linearVast_ar.push(tempObj);	
										}	
									}
								}
							}
						}
						
						FWDUVPUtils.storArrayBasedOnObjectValue(linearVast_ar, "sequence");
						FWDUVPUtils.storArrayBasedOnObjectValue(nonLinearVast_ar, "sequence");
						
						
						//create non linear ads object
						for(var i=0; i<nonLinearVast_ar.length; i++){
							var adsObj = {};
							if(!adsObj.timeStart) adsObj.timeStart = 0;
							if(nonLinearVast_ar[i].startTime) adsObj.timeStart = FWDUVPUtils.getSecondsFromString(nonLinearVast_ar[i].startTime);
							
							if(self.vastData_ar.length && self.vastData_ar[self.countVastDataLoaded]['timeOffset']){
								var vmapTimeStart = self.vastData_ar[self.countVastDataLoaded]['timeOffset'];
								if(vmapTimeStart){
									if(vmapTimeStart.toLowerCase() == 'start'){
										adsObj.timeStart = 0;
									}else if(vmapTimeStart.toLowerCase() == 'end'){
										adsObj.timeStart = 'end';
									}else if(vmapTimeStart.toLowerCase().indexOf('%') != -1){
										adsObj.timeStart = vmapTimeStart.toLowerCase();
									}else{
										adsObj.timeStart = FWDUVPUtils.getSecondsFromString(vmapTimeStart.toLowerCase());
									}
								}
							}
							
							adsObj.source = nonLinearVast_ar[i]['InLine']['NonLinear']['source'];
							if((nonLinearVast_ar[i]['InLine']['NonLinear']['type'] && nonLinearVast_ar[i]['InLine']['NonLinear']['type'].indexOf('image') != -1)
								|| adsObj.source.match(/jpg|jpeg|png/ig)){
								if(adsObj.source.indexOf('?') != -1){
									adsObj.source = adsObj.source + '&vast-type=.png';
								}else{
									adsObj.source = adsObj.source + '?vast-type=.png';
								}
							}
							adsObj.duration = nonLinearVast_ar[i]['InLine']['NonLinear']['duration'];
							adsObj.google_ad_width = nonLinearVast_ar[i]['InLine']['NonLinear']['width'] || 600;
							adsObj.google_ad_height = nonLinearVast_ar[i]['InLine']['NonLinear']['height'] || 200;
							
							if(nonLinearVast_ar[i]['InLine']['NonLinear']['ClickThroug']) adsObj.link = nonLinearVast_ar[i]['InLine']['NonLinear']['ClickThroug'];
							adsObj.target = '_blank';
							if(nonLinearVast_ar[i]['InLine']['NonLinear']['ClickTracking']) adsObj.tracking = nonLinearVast_ar[i]['InLine']['NonLinear']['ClickTracking'];
							self.parsedNonLienarVastAds_ar.push(adsObj);
						}

						data.popupAds_ar = self.parsedNonLienarVastAds_ar;
						
						//create linear ads object
						for(var i=0; i<linearVast_ar.length; i++){
							var adsObj = {};
					
							adsObj.source = linearVast_ar[i]["InLine"]["Linear"]["videoSource"];
							adsObj.source = adsObj.source;
							adsObj.timeStart = FWDUVPUtils.getSecondsFromString(data.vastLinearStartTime);
							if(linearVast_ar[i].startTime) adsObj.timeStart = FWDUVPUtils.getSecondsFromString(linearVast_ar[i].startTime);
							if(!adsObj.timeStart) adsObj.timeStart = 0;
							
							if(self.vastData_ar.length && self.vastData_ar[self.countVastDataLoaded]['timeOffset']){
								var vmapTimeStart = self.vastData_ar[self.countVastDataLoaded]['timeOffset'];
								if(vmapTimeStart){
									if(vmapTimeStart.toLowerCase() == 'start'){
										adsObj.timeStart = 0;
									}else if(vmapTimeStart.toLowerCase() == 'end'){
										adsObj.timeStart = 'end';
									}else if(vmapTimeStart.toLowerCase().indexOf('%') != -1){
										adsObj.timeStart = vmapTimeStart.toLowerCase();
									}else{
										adsObj.timeStart = FWDUVPUtils.getSecondsFromString(vmapTimeStart.toLowerCase());
									}
								}
							}
							
							if(linearVast_ar[i]["InLine"]["Linear"]["skipoffset"]) adsObj.timeToHoldAds = linearVast_ar[i]["InLine"]["Linear"]["skipoffset"];
							adsObj.link = linearVast_ar[i]["InLine"]["Linear"]["ClickThrough"];
							if(linearVast_ar[i]["InLine"]["Linear"]["ClickTracking"]) adsObj.ClickTracking = linearVast_ar[i]["InLine"]["Linear"]["ClickTracking"];
							adsObj.target = self.vastClickTroughTarget;
							if(linearVast_ar[i]["InLine"]["Impression"]) adsObj.Impression = linearVast_ar[i]["InLine"]["Impression"];
							if(linearVast_ar[i]["InLine"]["Linear"]["TrackingEvents"]) adsObj.TrackingEvents = linearVast_ar[i]["InLine"]["Linear"]["TrackingEvents"];
						
							self.parsedLienarVastAds_ar.push(adsObj);
						}
						
						data.adsSource_ar = self.parsedLienarVastAds_ar;

						data.isVastXMLParsed_bl = true;
						self.countVastDataLoaded ++;
						if(self.countVastDataLoaded == self.totalDataVastToLoad){
							data.dispatchEvent(FWDUVPData.VAST_LOADED, {ads:data.adsSource_ar, popupAds:data.popupAds_ar});
						}else{
							self.load(self.vastData_ar[self.countVastDataLoaded]['source']);
						}
					}else{
						data.dispatchEvent(FWDUVPData.LOAD_ERROR, {text:"vast XML file can't be loaded " +  self.vastXHR.statusText});
					}
				}
			};
			
			self.vastXHR.onerror = function(e){
				try{
					if(window.console) console.log(e);
					if(window.console) console.log(e.message);
				}catch(e){};
			};
			
			//if(source.indexOf("http") != -1 || source.indexOf("https") != -1){
				//source = "https://cors-anywhere.herokuapp.com/" + source;
			//}
			
			self.vastXHR.open("get", source, true);
			self.vastXHR.setRequestHeader('Content-Type',  'text/xml');
			self.vastXHR.send();
		}
		
		this.fixVmapTimes = function(duration, curAddData, curPopupAdsData, id){
			if(!duration || (self.id == id)) return;
			
			self.id = id;
			var ad;
			var timeStart;
			//console.log('#############################fixed ' + duration + ' - ' + id);
			
			if(curPopupAdsData){
				for(var i=0; i<curPopupAdsData.length; i++){
					ad = curPopupAdsData[i];
					
					if(String(ad['timeStart']).match(/%/ig)){
						timeStart = String(ad['timeStart']);
						timeStart = timeStart.substr(0, timeStart.length -1);
						timeStart = Math.round(Number(timeStart)/100 * duration);
						ad['timeStart'] = timeStart;
					}
					
					ad['timeEnd'] = ad['timeStart'] + FWDUVPUtils.getSecondsFromString(ad['duration']);
					if(i > 0){
						if(curPopupAdsData[i - 1]['timeStart'] == curPopupAdsData['timeStart']){
							curPopupAdsData[i]['timeStart'] = Number(curPopupAdsData[i - 1]['timeEnd']) + 1;
							curPopupAdsData[i]['timeEnd'] = curPopupAdsData[i]['timeStart'] + (curPopupAdsData[0]['timeEnd'] - data.popupAds_ar[0]['timeStart'])
						}
					}		
				}
			}
			if(curAddData){
				for(var i=0; i<curAddData.length; i++){
					ad = curAddData[i];
					if(String(ad['timeStart']).match(/%/ig)){
						timeStart = String(ad['timeStart']);
						timeStart = timeStart.substr(0, timeStart.length -1);
						timeStart = Math.round(Number(timeStart)/100 * duration);
						curAddData[i]['timeStart'] = timeStart;
					}else if(String(ad['timeStart']).toLowerCase() == 'end'){
						curAddData[i]['timeStart'] = duration - 1;
					}
				}
			}
			
			self.isVmapTimesFixed = true;
		}
		
		self.init();
	};
	
	/* set prototype */
	FWDUVPVast.setPrototype = function(){
		FWDUVPVast.prototype = null;
		FWDUVPVast.prototype = new FWDUVPEventDispatcher("div");
	};
	FWDUVPVast.prototype = null;
	window.FWDUVPVast = FWDUVPVast;
}(window));