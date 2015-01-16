var player = null;
var flashvars = {};
var params = {};
var attributes = {};
var swf_url = "";
function playSWF(swf_file){
    var base_url = swf_file.replace(/\/[^/]*$/, '/')
                                               
    params.allowfullscreen = "true";
    params.wmode = "transparent";                                        
    params.allowscriptaccess = "always";
    params.scale = "exactfit"
    params.base = base_url
    flashvars.width = 710
    flashvars.height = 532
                        
    attributes.id = "myDynamicContent";
    attributes.name = "myDynamicContent";
        
    swfobject.embedSWF(swf_file, "flash_viewer", "256", "193", "9.0.0","expressInstall.swf", flashvars, params, attributes);
}


function playVideo(video_file){
                    
    flashvars.file = video_file;
    flashvars.autostart = true;
    //flashvars.image = "/video/start.jpg";
    
    params.menu = "false";
    params.allowfullscreen = "true";
    params.wmode = "transparent";                                        
    params.allowscriptaccess = "always";
                        
    attributes.id = "myDynamicContent";
    attributes.name = "myDynamicContent";
    
    swfobject.embedSWF("player.swf", "wp-video", "265", "226", "9.0.0","expressInstall.swf", flashvars, params, attributes);
}

function playerReady(obj) {
    player = document.getElementById(obj.id);  
    addListeners();  
}  

function addListeners() {  
    if (player) {   
        player.addModelListener("STATE", "stateListener");  
    } else {  
        setTimeout("addListeners()",100);  
    }  
}  

function stateListener(obj) { 
    var mask = document.getElementById("video-stop");
    currentState = obj.newstate;   
    previousState = obj.oldstate;  
    
    if (currentState == "PLAYING" && previousState == "BUFFERING"){  
        if (mask) {
            mask.style.visibility = "hidden";
        }
    }else if(currentState == "PAUSED"){
            }else if(currentState == "COMPLETED"){
                if (mask) {
            mask.style.visibility = "visible";
        }
    }
}    
                                            
function doPlay() {
    player.sendEvent('play');
} 
