<html>
  <head>
  <script type="text/javascript" src="jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="variable.js"></script>
  <script type="text/javascript" src="Queue.js"></script>
  </head>
  <body>
  <canvas id="remoteconnect" style="width: 80%;"> </canvas>
 <a href="javascript:WebSocketTest()">VIEW SCREEN</a>
 <script>
 var mouseEventFlag=0;
 var socket;
function message(msg){
            $('#log').append(msg+'</p>');
          }

  try{

  var host = "ws://192.168.1.123:443/SUB1000000000000001-1000000000000001";
    var socket = new WebSocket(host);

        message('<p class="event">Socket Status: '+socket.readyState);

        socket.onopen = function(){

           message('<p class="event">Socket Status: '+socket.readyState+' (open)');
        }


        socket.onmessage = function(msg){
           message('<p class="message">Received: '+msg.data);
        }

        socket.onclose = function(){
          //alert('Socket Status: '+socket.readyState+' (Closed)');
           message('<p class="event">Socket Status: '+socket.readyState+' (Closed)');
        }

    } catch(exception){
      alert('Error'+exception);
    }


 $(document).ready(function(){
  var clickMouseEvent='0',delay = 500,mouseEventUpDown = '0',userPremission="pending";
    var keyboardPressEvent='0',keyUpDown='0';
var brocastFlag='1';
var toId='1000000000000002';
var fromId='1000000000000001';
var requestUserData=toId;

var pad="0000";
 $('#remoteconnect').on("mouseenter", function(e) {
      mouseEventFlag=1;
 });
$('#remoteconnect').on("mouseleave", function(e) {
      mouseEventFlag=0;
});

$(document).on( "keydown", function(event) {

  if(mouseEventFlag==1){
   event.preventDefault();
  var text=arrayValue[event.which];
  var KeyboardEventText=requestUserData+'KCLKDOWN|'+text;
  socket.send(KeyboardEventText);
  //console.log(KeyboardEventText);
  return false;
  }
})
.on( "keyup", function(event) {
  if(mouseEventFlag==1){
    event.preventDefault();
    var text= arrayValue[event.which];
    var KeyboardEventText=requestUserData+'KCLKUP|'+text;
    socket.send(KeyboardEventText);
    //console.log(KeyboardEventText);
    return false;
  }

});

		$('#remoteconnect').on("mousemove", function(e) {
       if(userPremission=='pending' || userPremission=='process'){
                            return true; }
       var offset = $(this).offset();
	   var X = ((e.clientX) - offset.left);
       var Y = ((e.clientY) - offset.top);
       //console.log(e.pageX+'-'+e.pageY+'>'+offset.left+'-'+offset.top);
       //console.log(e.pageY);
       //var tv = document.getElementById('remoteconnect');
       //var nx = tv.width - X ;
       console.log('X = '+X)
       X = ( X * 100) / 80 ;
       Y = ( Y * 100) / 80 ;
       console.log('NewX = '+X)
       var XX=""+Math.round(X);
       var YY=""+Math.round(Y);
       var XXX=pad.substring(0,pad.length-XX.length)+XX;
       var YYY=pad.substring(0,pad.length-YY.length)+YY;
       if(XXX > -1 && YYY > -1){
          var mouseMoveEventText=requestUserData+'MOUS'+XXX+'|'+YYY;
          socket.send(mouseMoveEventText);
          console.log(XX+" | "+YY);
      }else{
       console.log('Out Side Mouse Move: ' +mouseMoveEventText);
      }

});




function keyEventModification(keycodeValue){
 if(keycodeValue==0)
    return 1;
  else if(keycodeValue==1)
    return 3;
  else
    return 2;
}
$('#remoteconnect')
                   .mousedown(function (e) {
                       if(userPremission=='pending' || userPremission=='process'){
                            return true; }
                                var offset = $(this).offset();
                                var X = ((e.pageX*1) - offset.left);
                                var Y = ((e.pageY*1) - offset.top);
                                X = ( X * 100) / 80 ;
       							Y = ( Y * 100) / 80 ;
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                        setTimeout(function() {
                            if(clickMouseEvent=='0'){
                              mouseEventUpDown='1';
                              /*Mouse Down Event*/
                                if(XXX > -1 && YYY > -1){
                                  var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DOWN|'+keyEventModification(e.button);
                                  socket.send(mouseMoveEventText);
                                  //console.log(mouseMoveEventText);
                                }else{
                                   console.log('Out Side Mouse Move: ' +mouseMoveEventText);
                                }




                             }
                        }, delay);

                    })
                   .mouseup(function (e) {
                      if(userPremission=='pending' || userPremission=='process'){
                            return true;  }
                                var offset = $(this).offset();
                                var X = ((e.pageX*1) - offset.left);
                                var Y = ((e.pageY*1) - offset.top);
                                X = ( X * 100) / 80 ;
       							Y = ( Y * 100) / 80 ;
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                      setTimeout(function() {
                        if(mouseEventUpDown == '1' && clickMouseEvent=='0'){
                           mouseEventUpDown='0';
                           //console.log('Up');
                           /* Mouse Up Event*/
                               if(XXX > -1 && YYY > -1){
                                  var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|UP|'+keyEventModification(e.button);
                                  socket.send(mouseMoveEventText);
                                  //console.log(mouseMoveEventText);
                                }else{
                                   console.log('Out Side Mouse Move: ' +mouseMoveEventText);
                                }


                        }else{
                          if(clickMouseEvent!='2'){
                              clickMouseEvent='1';
                              /*Mouse Click Event*/
                                if(XXX > -1 && YYY > -1){
                                  var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|SCLK|'+keyEventModification(e.button);
                                  socket.send(mouseMoveEventText);
                                  //console.log(mouseMoveEventText);
                                }else{
                                   console.log('Out Side Mouse Move: ' +mouseMoveEventText);
                                }
                          }
                        }
                      }, 250);
                       setTimeout(function() {
                          clickMouseEvent='0';
                       }, 700);
                   })
                  .dblclick(function (e) {
                    if(userPremission=='pending' || userPremission=='process'){
                            return true; }
                     clickMouseEvent='2';
                     /*Mouse Double Click Event*/
                                var offset = $(this).offset();
                                var X = ((e.pageX*1) - offset.left);
                                var Y = ((e.pageY*1) - offset.top);
                                X = ( X * 100) / 80 ;
       							Y = ( Y * 100) / 80 ;
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                                if(XXX > -1 && YYY > -1){
                                  var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DCLK|'+keyEventModification(e.button);
                                  socket.send(mouseMoveEventText);
                                  //console.log(mouseMoveEventText);
                                }else{
                                   console.log('Out Side Mouse Move: ' +mouseMoveEventText);
                                }

                    });


                 $('#remoteconnect').bind('mousewheel', function(e){
                      if(e.originalEvent.wheelDelta /120 > 0) {


                                var offset = $(this).offset();
                                var X = ((e.pageX*1) - offset.left);
                                var Y = ((e.pageY*1) - offset.top);
                                X = ( X * 100) / 80 ;
       							Y = ( Y * 100) / 80 ;
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                                if(XXX > -1 && YYY > -1){
                                 var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|UP|4';
                                  socket.send(mouseMoveEventText);
                                  //console.log(mouseMoveEventText);
                                }
                      }
                      else{


                                var offset = $(this).offset();
                                var X = ((e.pageX*1) - offset.left);
                                var Y = ((e.pageY*1) - offset.top);
                                X = ( X * 100) / 80 ;
       							Y = ( Y * 100) / 80 ;
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                                if(XXX > -1 && YYY > -1){
                                 var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DOWN|4';
                                  socket.send(mouseMoveEventText);
                                  //console.log(mouseMoveEventText);
                                }

                      }
                      return false;
                  });

                  $('#remoteconnect').click(function () {

                    if(userPremission=='pending' && userPremission !='process'){
                      userPremission='Accepted';
                      var txt;
                      var r = confirm("Are you want to send the request?");
                      if (r == true) {
                        socket.send(requestUserData+'RCNT');
                            alert("You pressed OK!");
                        } else {
                            alert("Request Canceled");
                        }
                      return true;
                    }

                    });

       $('#remoteconnect').on("contextmenu",function(){
       return false;
       });

	});

setInterval(root,0)

var queue = new Queue();
var reader = new FileReader();
var rend = true;
var tv = document.getElementById('remoteconnect');
var can_tv = tv.getContext('2d');
var im = new Image();
var h = 0;
var w = 0;

//reader.onloadstart = printEventType;
//reader.onprogress = printEventType;
//reader.onload = printEventType;
//reader.onloadend = printEventType;

function printEventType(event)
{
console.log('got event: ' + event.type);
}


function root()
{
				if (rend === true & queue.isEmpty() === false)
				{
				  
    			  reader.onload = function(e)
            	  {
            	    //console.log('>>>>>>')
            	    var d = e.target.result;
            	    var a = d.slice(0,16);
            	    var f = d.slice(16,20);
            	    //console.log('ADDRESS:'+a+' FUNCTION:'+f);

            	    if (f === 'DESK')
            	     {

            	       d = d.slice(20);
            	       xy = d.split('|',2);
            	       l = xy[0].length+xy[1].length+2;
            	       d = d.slice(l);
            	       var j = "data:image/jpeg;base64,"+d;
            	       im.src = j;
            	       if (im.height > h | im.width > w )
            	       {
            	    	   h = im.height;
            	    	   w = im.width;
            	    	   tv.width = w;
            	    	   tv.height = h;
            	    	   console.log(tv.width+'-'+tv.height)
            	       }
            	       //console.log(xy[0]+'-'+xy[1]+'-'+im.width+'-'+im.height)
            	       im.onload = function(){can_tv.drawImage(im,xy[0],xy[1]);}
            	       //console.log('>>>>>>>>>>>>>>>>>>>>>')
            	     }
            	  rend = true
            	  };
            	 rend = false
            	 reader.readAsText(queue.dequeue());
            	 }

}





function WebSocketTest()
         {
            if ("WebSocket" in window)
            {
               //alert("WebSocket is supported by your Browser!");

               // Let us open a web socke
               var ws = new WebSocket("ws://192.168.1.123:443/SUB1000000000000000-1000000000000001");
               ws.binaryType = 'blob';
               //ws.binaryType = "arraybuffer";

               ws.onopen = function()
               {
                  // Web Socket is connected, send data using send()
                  //ws.send("Message to send");
                  var toId='1000000000000002';
				  var requestUserData=toId;
                  socket.send(requestUserData+'NIMG');
                  console.log("WS Connection Open Success");
               };


               ws.onmessage = function (evt)
               {
            	  
            	  queue.enqueue(evt.data);
            	  //console.log(queue.getLength());
            	  
               };

               ws.onclose = function()
               {
            	  //ws.close();
                  // websocket is closed.
                  console.log("Connection is closed...");

               };
            }

            else
            {
               // The browser doesn't support WebSocket
               alert("WebSocket NOT supported by your Browser!");
            }
         }

 </script>
  </body>
 </html>