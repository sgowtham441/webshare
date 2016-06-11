<html>
  <head>
	<script src="{{ URL::asset('screenshare/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('screenshare/variable.js') }}"></script>
  <script>
  var mouseEventFlag=0;
 var socket;
  var clickMouseEvent='0',delay = 500,mouseEventUpDown = '0',userPremission="pending";
    var keyboardPressEvent='0',keyUpDown='0';

var touserId='123456789012345A';

var fromUserId='{{ $onlineuser->metting_user_id }}';
var meetingId='{{ $onlineuser->metting_id }}000000';



function message(msg){
            $('#log').append(msg+'</p>');
          }

          function assicctoString(arrayBuffer){
            var i=0;
            var imageString='';
            var bytes=new Uint8Array(arrayBuffer);
          while(i<bytes.length){
               imageString+=String.fromCharCode(bytes[i++])
           }
           
           return imageString;
          }
  /*Unique Cast*/
 try{

  var host = "ws://192.168.43.62:443/SUB123456789012345B-123456789012345B";
    var socket = new WebSocket(host);
    socket.binaryType = "arraybuffer";
        message('<p class="event">Socket Status: '+socket.readyState);

        socket.onopen = function(){
       socket.send(brocatUserData+'BEAT|HAII');   
console.log('send'+brocatUserData+'BEAT|HAII');
           message('<p class="event">Socket Status: '+socket.readyState+' (open)');
        }

        socket.onmessage = function(msg){
          chatmessage = assicctoString(msg.data);
          var fromId = chatmessage.substring(0, 16);
          var message = chatmessage.substring(16);
          if(message=='HELO'){
           $("#chatuserlist").append("<p>"+fromId+"</p>");
          }else{
             $("#chathistory").append("<p>"+fromId+"--"+message+"</p>");
          }
           console.log('Uniq'+chatmessage);
        }

        socket.onclose = function(){
          //alert('Socket Status: '+socket.readyState+' (Closed)');
           message('<p class="event">Socket Status: '+socket.readyState+' (Closed)');
        }     

    } catch(exception){
      alert('Error'+exception);
    }


/*Brocat Cast*/
try{

  var BroadcastHost = "ws://192.168.43.62:443/SUB1234567890000000-123456789012345B";
    var BroadcastSocket = new WebSocket(BroadcastHost);
  BroadcastSocket.binaryType = "arraybuffer";
        message('<p class="event">Socket Status: '+BroadcastSocket.readyState);

        BroadcastSocket.onopen = function(){
            
           message('<p class="event">Socket Status: '+BroadcastSocket.readyState+' (open)');
        }

        BroadcastSocket.onmessage = function(msg){

          var chatmessage = assicctoString(msg.data);
          var fromId = chatmessage.substring(0, 16);
          var message = chatmessage.substring(16);
           console.log('Brod'+chatmessage);
          if(message=='BEAT|HAII'){
           socket.send(fromId+'HELO');
          }else{
             $("#chathistory").append("<p>"+fromId+"--"+message+"</p>");

          }
           
          
        }

        BroadcastSocket.onclose = function(){
          //alert('Socket Status: '+socket.readyState+' (Closed)');
           message('<p class="event">Socket Status: '+BroadcastSocket.readyState+' (Closed)');
        }  



    } catch(exception){
      alert('Error'+exception);
    }

   

  </script>
  <script type="text/javascript" src="{{ URL::asset('screenshare/socketjavascript.js') }}"></script>
  <style>
  .remoteclass{width:70%;border: 4px solid red;float: left}
  .remoteclasschat{width:20%;border: 4px solid red;float: left;height:100%;}
  #chatuserlist{height:40%;width:100%;border: 4px solid #FFF000;}
  #chathistory{height:40%;width:100%;border: 4px solid blue;}
  .chataction{height:10%;width:100%;}
  #chatenter{height:100%;width:70%;}
  #submitchat{height: 100%;
    width: 25%;
    padding: 0;
    float: right;}
  </style>
  </head>
  <body>
   <div class="log">
   </div>
  <div class="remoteclass">
  <img src="{{ URL::asset('img/college-reg.jpg') }}" id="remoteconnect" style="width: 1366px;zoom: 50%;height: 768px;border: 4px solid red;"/>
 </div>
 <div class="remoteclasschat">
 <div id="chatuserlist"></div>
 <div id="chathistory"></div>
 <div class="chataction">
 <textarea id="chatenter"></textarea>
 <button id="submitchat">Submit</button>
 </div>
 </div>
 <style>
.remoteclass{margin:38px;}
</style>
 <script>


 $(document).ready(function(){
      $('#submitchat').click(function () { 

      var chatboxText = $("#chatenter").val();
      $("#chatenter").val('');
     $("#chathistory").append("<p>"+requestUserData+"--"+chatboxText+"</p>");
          socket.send(requestUserData+''+chatboxText);
      });
});


 </script>

  </body>
 </html>