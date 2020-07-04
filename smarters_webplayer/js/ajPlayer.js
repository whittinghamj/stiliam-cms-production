function clearInt()
{
  clearInterval(inT);
clearInterval(init);
}
function setAJPlayer()
{
var vid = document.getElementById("AJplayer");

var vidTime = '';
var currentTime = '';


function getCurTime() { 
    return vid.currentTime;
}

function getDurTime() { 
    return vid.duration;
} 

function setCurTime() { 
    vid.currentTime=5;
} 

$(document).find('.fullscreen').click(function(){
  $(document).find('#AJplayer').css('position','fixed');
  $(document).find('#AJplayer').css('width','100%');
  $(document).find('#AJplayer').css('height','100%');
  $(document).find('.AJControls').css('left','0px');
  $(document).find('.AJControls').css('position','fixed');
  /*$('#AJplayer').parent('div').css('position','fixed');
  $('#AJplayer').parent('div').css('width','100%');
  $('#AJplayer').parent('div').css('height','100%');*/
  $(document).find('#AJplayer').css('top','0%');
  $(document).find('#AJplayer').css('left','0%');

$(document).find('video').removeAttr('controls');

$(document).find('.collapse').show();
$(document).find('.fullscreen').hide();
})

$(document).find('.collapse').click(function(){
  $(document).find('#AJplayer').css('position','relative');
  $(document).find('#AJplayer').css('width','100%');
  $(document).find('#AJplayer').css('height','auto');
  $(document).find('.AJControls').css('left','0px');
  $(document).find('.AJControls').css('position','absolute');

  /*$('#AJplayer').parent('div').css('width','100%');
  $('#AJplayer').parent('div').css('height','auto');*/
  $(document).find('#AJplayer').css('top','');
  $(document).find('#AJplayer').css('left','');

$(document).find('video').removeAttr('controls');

$(document).find('.collapse').hide();
$(document).find('.fullscreen').show();
})
var trySet = '0';
init = setInterval(function(){
trySet++;
if(trySet == 10)
{
  setPlayer($movieVideoLink);
  return;
}
  if(vid.readyState > 0) {

    $(document).find('.buttons').show();

    /*var minutes = parseInt(vid.duration / 60, 10);
    var seconds = vid.duration % 60;*/  
  var d, h, m, s;
  s = vid.duration
  m = Math.floor(s / 60);
  s = s % 60;
  h = Math.floor(m / 60);
  m = m % 60;
  d = Math.floor(h / 24);
  h = h % 24;
    vidTime = h+':'+m+':'+Math.floor(s);
    $(document).find('.totalTime').text(vidTime);
    clearInterval(init);
    inT = setInterval(function()
       {
  var percentage = ( vid.currentTime / vid.duration ) * 100;

  $(document).find("#custom-seekbar span").css("width", percentage+"%");

    var time = vid.currentTime;
    
  var d, h, m, s;
  s = time
  m = Math.floor(s / 60);
  s = s % 60;
  h = Math.floor(m / 60);
  m = m % 60;
  d = Math.floor(h / 24);
  h = h % 24;
  
  
  currentTime =h+':'+m+':'+Math.floor(s);

  $(document).find('.runTime').text(currentTime);

  if(vidTime == currentTime)
  {
     vid.currentTime = 0;
     $(document).find('#AJplayer').trigger('pause');
     $(document).find('.pause').hide(0);
     $(document).find('.play').show(0);
     $(document).find('.runTime').text(currentTime);
  }
  
},1000);
  }
},1000)

if(vid.duration == 'NaN')
{
  $(document).find('.totalTime').text('0');
  return;
}
    $(document).find('.play').click(function(){

       $(document).find('#AJplayer').trigger('play');
       $(this).hide(0);
       $(document).find('.pause').show(0);
    })

    $(document).find('.pause').click(function(){
      $(this).hide(0);
       $(document).find('.play').show(0);
       $(document).find('#AJplayer').trigger('pause');
    })

    $(document).find('.stop').click(function(){
      vid.currentTime = 0;
       $(document).find('#AJplayer').trigger('pause');
    })

$(document).find("#custom-seekbar").on("click", function(e){
    var offset = $(this).offset();
    var left = (e.pageX - offset.left);
    var totalWidth = $(document).find("#custom-seekbar").width();
    var percentage = ( left / totalWidth );
    var vidTime = vid.duration * percentage;
    vid.currentTime = vidTime;
});//click()
   
  }




