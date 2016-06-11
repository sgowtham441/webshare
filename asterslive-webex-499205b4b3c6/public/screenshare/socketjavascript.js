

 $(document).ready(function(){
 

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
  console.log(KeyboardEventText);
  return false;
  }
})
.on( "keyup", function(event) {
  if(mouseEventFlag==1){   
    event.preventDefault();
    var text= arrayValue[event.which];
    var KeyboardEventText=requestUserData+'KCLKUP|'+text;
    socket.send(KeyboardEventText);
    console.log(KeyboardEventText);
    return false;
  }

});

		$('#remoteconnect').on("mousemove", function(e) {
       if(userPremission=='pending' || userPremission=='process'){
                            return true; }
       var offset = $(this).offset();
			 var X = ((e.pageX*1.958) - offset.left);
       var Y = ((e.pageY*2) - offset.top);
       var XX=""+Math.floor(X);
       var YY=""+Math.floor(Y);
       var XXX=pad.substring(0,pad.length-XX.length)+XX;
       var YYY=pad.substring(0,pad.length-YY.length)+YY;
       if(XXX > -1 && YYY > -1){
          var mouseMoveEventText=requestUserData+'MOUS'+XXX+'|'+YYY;
          socket.send(mouseMoveEventText);
        console.log(mouseMoveEventText);
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
                                var X = ((e.pageX*1.958) - offset.left);
                                var Y = ((e.pageY*2) - offset.top);
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
                                  console.log(mouseMoveEventText);
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
                                var X = ((e.pageX*1.958) - offset.left);
                                var Y = ((e.pageY*2) - offset.top);
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                      setTimeout(function() {
                        if(mouseEventUpDown == '1' && clickMouseEvent=='0'){
                           mouseEventUpDown='0';
                           console.log('Up');
                           /* Mouse Up Event*/
                               if(XXX > -1 && YYY > -1){
                                  var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|UP|'+keyEventModification(e.button);
                                  socket.send(mouseMoveEventText);
                                  console.log(mouseMoveEventText);
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
                                  console.log(mouseMoveEventText);
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
                                var X = ((e.pageX*1.958) - offset.left);
                                var Y = ((e.pageY*2) - offset.top);
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                                if(XXX > -1 && YYY > -1){
                                  var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DCLK|'+keyEventModification(e.button);
                                  socket.send(mouseMoveEventText);
                                  console.log(mouseMoveEventText);
                                }else{
                                   console.log('Out Side Mouse Move: ' +mouseMoveEventText);
                                }   
                         
                    });


                 $('#remoteconnect').bind('mousewheel', function(e){
                      if(e.originalEvent.wheelDelta /120 > 0) {


                                var offset = $(this).offset();
                                var X = ((e.pageX*1.958) - offset.left);
                                var Y = ((e.pageY*2) - offset.top);
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                                if(XXX > -1 && YYY > -1){
                                 var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|UP|4';
                                  socket.send(mouseMoveEventText);
                                  console.log(mouseMoveEventText);
                                }
                      }
                      else{
                          

                                var offset = $(this).offset();
                                var X = ((e.pageX*1.958) - offset.left);
                                var Y = ((e.pageY*2) - offset.top);
                                var XX=""+X;
                                var XXX=pad.substring(0,pad.length-XX.length)+XX;
                                var YY=""+Y;
                                var YYY=pad.substring(0,pad.length-YY.length)+YY;
                                var XXX=""+Math.floor(XXX);
                                var YYY=""+Math.floor(YYY);
                                if(XXX > -1 && YYY > -1){
                                 var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DOWN|4';
                                  socket.send(mouseMoveEventText);
                                  console.log(mouseMoveEventText);
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