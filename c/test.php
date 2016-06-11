<?php
$usr_id = $_GET['u'];
$group_id = $_GET['g'];
$name = $_GET['n']
?>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Remote share</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<script type="text/javascript" src="jquery-1.11.3.min.js"></script>
<script src='bootstrap/js/bootstrap.min.js'></script>
<script type="text/javascript" src="variable.js"></script>
<script type="text/javascript" src="Queue.js"></script>
<script type="text/javascript" src="taffy-min.js"></script>
</head>
<body>
<input type="hidden" value="<?php echo $usr_id; ?>" id="usrid" />
<input type="hidden" value="<?php echo $group_id; ?>" id="groupid" />
<input type="hidden" value="<?php echo $name; ?>" id="usrname" />
<div class="container-fluid">
<div class="row">
  <div  style="width:85%">
  	</div><div id="cantv" style="width:80%"> <canvas id="remoteconnect" style="width:100%"> </canvas></div>
  	<div class="video-chat">
  		<div class="video-left">
  			<p>Product name</p>
  		</div>
  		<div class="video-right">
  			<img src="images/logo.jpg">
  		</div>
  	</div>
  </div>
  <div style="width:15%">
	  <div class="chat">
	  	<div class="chat-history">
	  		<h3 class="chat-head">Live Chat</h3>
	  		<div class="recev-chat" id='chat-rec'>
	  			<img src="images/images.png">
	  			
	  		</div>
	  		<div class="from-chat" id='chat-sent'>
	  			<img src="images/images.png">
	  			
	  		</div>
	  		

	  	</div>
	  	
	  		<select id="user-select">
	  		</select>
	  		<textarea id="chat-text"></textarea>
	  		<button value="send" id="send-chat" onclick="chatsnd()">
	  	

	  </div>
  </div>
</div>
</div>

<script>

var uid = document.getElementById('usrid').value;
var bid = document.getElementById('groupid').value;
var uname = document.getElementById('usrname').value;

var atv = document.getElementById('cantv');
var tv = document.getElementById('remoteconnect');
var queue = new Queue();
var uqueue = new Queue();
var lchat = new Queue();
var reader = new FileReader();
var rend = true;
var ureader = new FileReader();
var urend = true;
var can_tv = tv.getContext('2d');
var im = new Image();
var h = 0;
var w = 0;
var usoc_stat = false;
var bsoc_stat = false;
var usock;
var bsock;
var chatdb = TAFFY();
chatdb.store("chatdb");
var usrdb = TAFFY();
//usrdb.store("usrdb");

var mouseEventFlag=0;
var select = document.getElementById("user-select");
select.options[select.options.length] = new Option('ALL',bid);

function message(msg){
           $('#log').append(msg+'</p>');
         }

function ucon(){

 try{

 	var host = "ws://192.168.1.123:8080/SUB"+uid+"-"+uid;
   	usock = new WebSocket(host);


    usock.onopen = function()
    {

         console.log('Unicast Socket :'+usock.readyState);
    }


    usock.onmessage = function(msg)
    {
    	
   	    uqueue.enqueue(msg.data);
    }

    usock.onclose = function()
    {

       console.log('Unicast Socket Closed');
       usoc_stat = false;
    }

    usock.onerror = function(e)
    {
  	  console.log('Unicast Socket Error');
  	  usoc_stat = false;

    }

    }
 catch(exception)
 {
     console.log('Unicast Socket Exception'+exception);
     usoc_stat = false;
     usock.close();
 }
}


function bcon()
{
   try
   {

      var bsock = new WebSocket("ws://192.168.1.123:8080/SUB"+bid+"-"+uid);
      bsock.binaryType = 'blob';
      //ws.binaryType = "arraybuffer";

      bsock.onopen = function()
      {
    	 con1 = true
         var toId='1000000000000005';
		 var requestUserData=toId;
		 usock.send(requestUserData+'NIMG');
         console.log("Broadcast Socket:"+bsock.readyState);
      };


      bsock.onmessage = function (evt)
      {
      //console.log(evt.data);
   	  queue.enqueue(evt.data);
   	  //console.log(queue.getLength());

      };

      bsock.onclose = function()
      {
         console.log("Broadcast Socket Closed");
         bsoc_stat = false;

      };

      bsock.onerror = function(e)
      {
    	  console.log('Broadcast Socket Error'+e)
    	  bsoc_stat = false;

      }

   }

   catch(exception)
   {
	   console.log('Broadcast Socket Exception:'+exception);
       bsoc_stat = false;
       bsock.close();
   }
}





function udesk()
{
			if (urend === true && uqueue.isEmpty() === false)
			{
			  
   			  ureader.onload = function(e)
           	  {
           	    
           	    var d = e.target.result;
           	    var a = d.slice(0,16);
           	    var f = d.slice(16,20);
           	    console.log(a+f);

           	    if ( f === 'BEAT')
           	    {
					var dd = d.slice(20);
					console.log(dd)
					var usr_id = d.slice(0,16);
					dd = dd.split('|');
					//IF RECEIVED A BEAT THEN SEND REPLAY BEAT TO ALL USERS
					if (dd[0] === 'HAII')
					{

						var usr_stat = JSON.parse(dd[1]);
						var usri = usrdb({'uid':usr_id}).last()
						if (usri === false )
						{
							//INSERTING NEW USER INTO DB
							console.log('NEW USER INSERTED IN USERDB HAI:'+usr_id)
							usrdb.insert({'uid':usr_id,'name':usr_stat['name'],'bchatid':0, 'uchatid':0});
							var select = document.getElementById("user-select");
							select.options[select.options.length] = new Option(usr_stat['name'],usr_id);
						}
						
						var uchatid = chatdb({'to':usr_id}).last()
						
						
						if (uchatid === false)
						{
							
							uchatid = '0'
						}else
						{
							uchatid = uchatid['cid']
						}
						
						
						bchatid = chatdb({'to':bid}).last()
						if (bchatid === false)
						{
							bchatid = '0'
						}else
						{
							bchatid = bchatid['cid']
						}
						
						var ustat = JSON.stringify({'name':uname,'bchatid':bchatid,'uchatid':uchatid});
						
						try{if(usock.readyState === 1){usock.send(usr_id+'BEATHELO|'+ustat);}}catch(err){}
					}
					
					//RECEIVING REPLAY BEAT SIGNAL
			 		else if (dd[0] == 'HELO')
			  		{
				 		var usr_stat = JSON.parse(dd[1]);
				 		usr_stat['name']
				 		usr_stat['bchatid']
				 		usr_stat['uchatid']
				 		var d = usrdb({'uid':usr_id}).last()
				 		if (d === false)
				 		{
				 			console.log('NEW USER INSERTED IN USERDB HELLO'+usr_id)
				 			usrdb.insert({'uid':usr_id,'name':usr_stat['name'],'bchatid':0, 'uchatid':0});
				 			var select = document.getElementById("user-select");
							select.options[select.options.length] = new Option(usr_stat['name'],usr_id);
				 		}
				 		else
				 		{
				 			if (+d['bchatid'] > +usr_stat['bchatid'] || +d['uchatid'] > +usr_stat['uchatid'])
					 		{
				 				// RECEIVING LESS CHAT ID COMPARE TO LOCAL DB
					 			// REMOTE USER RESET THE CHAT COUNT
				 				usrdb.insert({'uid':usr_id,'name':usr_stat['name'],'bchatid':0, 'uchatid':0});
				 				// VALUE RESETED IN DB
				 				d['bchatid'] = 0;
				 				d['uchatid'] = 0;
					 		}
				 			if (+d['bchatid'] != +usr_stat['bchatid'] && +usr_stat['bchatid'] != 0)
				 			{
				 				
				 				//BROADCAST CHAT NOT IN SYNC
				 				var nchat = +d['bchatid']+1
				 				try{if(usock.readyState === 1){usock.send(usr_id+'GCHTB|'+nchat);}}catch(err){}
				 			}
				 			if (+d['uchatid'] != +usr_stat['uchatid'] && +usr_stat['uchatid'] != 0)
				 			{
				 				//UNICAST CHAT NOT IN SYNC
				 				var nchat = +d['bchatid']+1
				 				try{if(usock.readyState === 1){usock.send(usr_id+'GCHTU|'+nchat);}}catch(err){}
				 			}
				 		}
				 
           	  		}
           	    }
					//console.log('Before CHAT')
			 		else if ( f == 'CHAT')
		           	 {
			 			
		           		var dd = d.slice(20);
						var usr_id = d.slice(0,16);
						
						var cc = dd.split('|',3);
						var chat =  dd.slice(cc.join().length+1)
						
						var chat_id = +cc[0];
						var unam = cc[1];
						var cht_typ = cc[2];
						
						var t1 = 0;
						var t2 = 0;
					    if (cht_typ == 'B')
					    {
					    	cht_typ = 'bchatid';
					    	t1 = chat_id;
					    	
					    }
					    else
					    {
					    	cht_typ = 'uchatid';
					    	t2 = chat_id
					    }
		                var d = usrdb({'uid':usr_id}).last()
		                
		                if (d == false && chat_id == 1)
		                {
		                	// RECEIVED MESSAGE IN SYNC
		                	//PUSH CHAT TO USER
		                	var pp = document.createElement('p');
		                    pp.textContent = unam+': '+chat;
		                	document.getElementById("chat-rec").appendChild(pp);
		              	    usrdb.insert({'uid':usr_id,'name':unam,'bchatid':t1, 'uchatid':t2});
		              		var select = document.getElementById("user-select");
							select.options[select.options.length] = new Option(uname,usr_id);
							console.log('U CHAT MSG IN SYNC ID:'+dd);
		              	  
		                }
		                else if(d != false && +d[cht_typ]+1 == chat_id)
		                {
		              	  // RECEIVED MESSAGE IN SYNC
		              	  var pp = document.createElement('p');
		                  pp.textContent = unam+': '+chat;
		                  document.getElementById("chat-rec").appendChild(pp);
		              	  console.log('U CHAT MSG IN SYNC ID:'+dd);
		              	  usrdb({'uid':usr_id}).update({[cht_typ]:chat_id});
		              	  
		              	  //PUSH CHAT TO USER
		              	  
		              	  
		                }
		                else
		                {
		                	console.log('U CHAT MSG NOT IN SYNC :'+dd);
		              	// RECEIVED MESSAGE NOT IN SYNC
		              	  if (d != false)
		              	  {
		              		  var nchat = +d[cht_typ]+1
		              		  try{if(usock.readyState === 1){usock.send(usr_id+'GCHT'+cc[2]+'|'+nchat);}}catch(err){}
		              	  }
		              	  else
		              	  {
		              		  
		              		  try{if(usock.readyState === 1){usock.send(usr_id+'GCHT'+cc[2]+'|'+1);}}catch(err){}
		              	  }
		              	  
		                }

					}
		           	  
			 	else if ( f === 'GCHT')
			 	{
			 		var dd = d.slice(20);
					var usr_id = d.slice(0,16);
					dd = dd.split('|');
					var ct = dd[0];
					var cid = +dd[1];
					console.log('U REC GCHT ID:'+dd)
					if (ct == 'U')
					{
						
						to = usr_id;
					}
					else
					{
						to = bid;
					}
					
					//RESEND UNICAST CHAT MESSAGE BASED ON ID AND TYPE
					console.log({'cid':cid,'to':to})
					var s = chatdb({'cid':cid,'to':to}).get()
					console.log(s)
					if (s.length > 0)
					{
					 	s = s[0]
						console.log('RECENDING: CHAT'+s['cid'])
						try{if(usock.readyState === 1){usock.send(usr_id+'CHAT'+s['cid']+'|'+uname+'|'+ct+'|'+s['chat']);}}catch(err){}
					}
								 	
			 	}
			 	

           	  urend = true
           	  };
           	 urend = false
           	 ureader.readAsText(uqueue.dequeue());
           	 }

}


function bdesk()
{
	           
			   if (rend === true && queue.isEmpty() === false)
			   {
   			  reader.onload = function(e)
           	  {
           	    
           	    var d = e.target.result;
           	    var a = d.slice(0,16);
           	    var f = d.slice(16,20);
           	    console.log(a+f);
           	 	
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
           	    	   //console.log(tv.width+'-'+tv.height)
           	       }
           	       
           	       im.onload = function(){can_tv.drawImage(im,xy[0],xy[1]);}
           	       
           	     }
           	    else if ( f === 'BEAT')
           	    {
           	    	
					var dd = d.slice(20);
					console.log(dd)
					var usr_id = d.slice(0,16);
					dd = dd.split('|');
					//IF RECEIVED A BEAT THEN SEND REPLAY BEAT TO ALL USERS
					if (dd[0] === 'HAII')
					{

						var usr_stat = JSON.parse(dd[1]);
						var usri = usrdb({'uid':usr_id}).last()
						if (usri === false )
						{
							//INSERTING NEW USER INTO DB
							//console.log({'uid':usr_id,'name':usr_stat['name'],'bchatid':0, 'uchatid':0})
							usrdb.insert({'uid':usr_id,'name':usr_stat['name'],'bchatid':0, 'uchatid':0});
							var select = document.getElementById("user-select");
							select.options[select.options.length] = new Option(usr_stat['name'],usr_id);
							
						}
						
						var uchatid = chatdb({'to':usr_id}).last()
						
						
						if (uchatid === false)
						{
							
							uchatid = 0;
						}else
						{
							uchatid = +uchatid['cid']
						}
						
						
						bchatid = chatdb({'to':bid}).last()
						if (bchatid === false)
						{
							bchatid = 0;
						}else
						{
							bchatid = +bchatid['cid']
						}
						
						var bstat = JSON.stringify({'name':uname,'bchatid':bchatid,'uchatid':uchatid});
						//console.log('>>>>'+bstat);
						try{if(usock.readyState === 1){usock.send(usr_id+'BEATHELO|'+bstat);}}catch(err){console.log('SEND HELLO ERROR'+err)}
					}
					
           	    }	
           	  else if ( f === 'CHAT')
           	  {
           		var dd = d.slice(20);
				var usr_id = d.slice(0,16);
				var cc = dd.split('|',3);
				var chat =  dd.slice(cc.join().length+1)
				var chat_id = +cc[0];
				var unam = cc[1];
				var cht_typ = cc[2];
				if (cht_typ != 'B')
				{
					console.log('WRONG CHAT TYPE RECEIVED (Expected : B):'+cht_typ)
					return;
				}
			
                var d = usrdb({'uid':usr_id}).last()
                if (d == false && chat_id == 1)
                {
                	// RECEIVED MESSAGE IN SYNC
                	//PUSH CHAT TO USER
                  
              	  usrdb.insert({'uid':usr_id,'name':unam,'bchatid':chat_id, 'uchatid':0});
              	  var select = document.getElementById("user-select");
				  select.options[select.options.length] = new Option(unam,usr_id);
				  console.log('B CHAT MSG IN SYNC ID:'+dd)
				  var pp = document.createElement('p');
                  pp.textContent = unam+': '+chat;
              	  document.getElementById("chat-rec").appendChild(pp);
              	  
                }
                else if(d != false && +d['bchatid']+1 == chat_id)
                {
              	  // RECEIVED MESSAGE IN SYNC
              	  console.log('B CHAT MSG IN SYNC ID:'+dd);
              	  usrdb({'uid':usr_id}).update({'bchatid':chat_id});
              	  var pp = document.createElement('p');
                  pp.textContent = unam+': '+chat;
            	  document.getElementById("chat-rec").appendChild(pp);
              	  //PUSH CHAT TO USER
              	  
              	  
                }
                else
                {
                	console.log('B CHAT MSG NOT IN SYNC ID:'+dd);
              	// RECEIVED MESSAGE NOT IN SYNC
              	  if (d != false)
              	  {
              		  var nchat = +d['bchatid']+1
              		  try{if(usock.readyState === 1){usock.send(usr_id+'GCHTB|'+nchat);}}catch(err){}
              	  }
              	  else
              	  {
              		  
              		  try{if(usock.readyState === 1){usock.send(usr_id+'GCHTB|'+1);}}catch(err){}
              	  }
              	  
                }

				}
           	  
				
           	  //}


           	  rend = true
           	  };
           	 rend = false
           	 reader.readAsText(queue.dequeue());
           	 }

}

function chatsnd()
{
	//PUT CHAT MESSAGE INTO QUEUE : a = {'to':'111111111','chat_type':'B','chat':'hai'}
	
	var select = document.getElementById("user-select");
	var val = select.value
	var a = document.getElementById("chat-text").value;
	
	document.getElementById("chat-text").value = '';
	if (val == bid)
	{
		a = {'to':val,'chat_type':'B','chat':a}
	}
	else
	{
		a = {'to':val,'chat_type':'U','chat':a}
	}
	console.log(a)
	lchat.enqueue(a);
}
//CHECK FOR NEW LOCAL CREATED CHAT AND SEND TO USERS
function chatchk()
{
	if (lchat.isEmpty() === false)
	{
		
	    if (usock.readyState === 1)
	    {
	    	var a = lchat.dequeue();
	    	var cid = chatdb({'to':a['to']}).last()['cid']
	    	
	    	try
	    	{
	    		if (cid == 'undefined' || cid == null)
	    		{
	    		 cid = 1;
	    		}
	    		else
	    		{
	    		 cid = +cid + 1;
	    		}
	    		usock.send(a['to']+'CHAT'+cid+'|'+uname+'|'+a['chat_type']+'|'+a['chat'])
	    		console.log('CHAT SENT :'+a['to']+'CHAT'+cid+'|'+uname+'|'+a['chat_type']+'|'+a['chat'])
	    		chatdb.insert({'cid':cid,'to':a['to'],'chat':a['chat'],'status':'0'});
	    		var pp = document.createElement('p');
                pp.textContent = 'Me: '+a['chat'];
          	    document.getElementById("chat-sent").appendChild(pp);
	    	}
	    	catch (err)
	    	{
	    		console.log(err)
	    		lchat.enqueue(a);
	    	}
	    	
	    }
		
	
	}

}

// SEND BEAT SIGNAL WITH LAST CHAT ID TO ALL USERS
function beat()
{
	
	bchatid = chatdb({'to':bid}).last()
	if (bchatid === false)
	{
		bchatid = '0'
	}else
	{
		bchatid = bchatid['cid']
	}
	
	ustat = JSON.stringify({'name':uname});
	try
	{
		if(usock.readyState === 1)
		{
			usock.send(bid+'BEATHAII|'+ustat);
			//console.log('BEAT SENT');
		}
	}
	catch(err)
	{
		console.log('BEAT ERROR :'+err);
	}
	
}


//CHECK UNICAST AND BROADCAST WEBSOCKET STATUS
//IF WEBSOCKET STATUS IS FALSE THE RECCONNECT IT
function sock_chk()
{
	if (usoc_stat === false)
	{
	 ucon();
     usoc_stat = true;
	}

	if (bsoc_stat === false)
	{
	 bcon();
	 bsoc_stat = true;
	}
}

setInterval(bdesk,0);
setInterval(udesk,0);
setInterval(sock_chk,3000);
setInterval(chatchk,500);
setInterval(beat,5000);





$(document).ready(function(){
 var clickMouseEvent='0',delay = 500,mouseEventUpDown = '0',userPremission="pending";
   var keyboardPressEvent='0',keyUpDown='0';
var brocastFlag='1';
var toId='1000000000000005';
var fromId= uid;
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
 usock.send(KeyboardEventText);
 //console.log(KeyboardEventText);
 return false;
 }
})
.on( "keyup", function(event) {
 if(mouseEventFlag==1){
   event.preventDefault();
   var text= arrayValue[event.which];
   var KeyboardEventText=requestUserData+'KCLKUP|'+text;
   usock.send(KeyboardEventText);
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

      //var nx = tv.width - X ;
      console.log('X = '+X+'-'+Y+'>'+atv.offsetWidth+'-'+atv.offsetHeight)

      X = X * (tv.width / atv.offsetWidth );
      Y = Y * (tv.height / atv.offsetHeight );
      //console.log('NewX = '+X)
      var XX=""+Math.round(X);
      var YY=""+Math.round(Y);
      var XXX=pad.substring(0,pad.length-XX.length)+XX;
      var YYY=pad.substring(0,pad.length-YY.length)+YY;
      if(XXX > -1 && YYY > -1){
         var mouseMoveEventText=requestUserData+'MOUS'+XXX+'|'+YYY;
         usock.send(mouseMoveEventText);
         //console.log(XX+" | "+YY);
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
                               X = X * (tv.width / atv.offsetWidth );
                               Y = Y * (tv.height / atv.offsetHeight );
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
                                 usock.send(mouseMoveEventText);
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
                               X = X * (tv.width / atv.offsetWidth );
                               Y = Y * (tv.height / atv.offsetHeight );
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
                                 usock.send(mouseMoveEventText);
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
                                 usock.send(mouseMoveEventText);
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
                               X = X * (tv.width / atv.offsetWidth );
                               Y = Y * (tv.height / atv.offsetHeight );
                               var XX=""+X;
                               var XXX=pad.substring(0,pad.length-XX.length)+XX;
                               var YY=""+Y;
                               var YYY=pad.substring(0,pad.length-YY.length)+YY;
                               var XXX=""+Math.floor(XXX);
                               var YYY=""+Math.floor(YYY);
                               if(XXX > -1 && YYY > -1){
                                 var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DCLK|'+keyEventModification(e.button);
                                 usock.send(mouseMoveEventText);
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
                               X = X * (tv.width / atv.offsetWidth );
                               Y = Y * (tv.height / atv.offsetHeight );
                               var XX=""+X;
                               var XXX=pad.substring(0,pad.length-XX.length)+XX;
                               var YY=""+Y;
                               var YYY=pad.substring(0,pad.length-YY.length)+YY;
                               var XXX=""+Math.floor(XXX);
                               var YYY=""+Math.floor(YYY);
                               if(XXX > -1 && YYY > -1){
                                var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|UP|4';
                                 usock.send(mouseMoveEventText);
                                 //console.log(mouseMoveEventText);
                               }
                     }
                     else{


                               var offset = $(this).offset();
                               var X = ((e.pageX*1) - offset.left);
                               var Y = ((e.pageY*1) - offset.top);
                               X = X * (tv.width / atv.offsetWidth );
                               Y = Y * (tv.height / atv.offsetHeight );
                               var XX=""+X;
                               var XXX=pad.substring(0,pad.length-XX.length)+XX;
                               var YY=""+Y;
                               var YYY=pad.substring(0,pad.length-YY.length)+YY;
                               var XXX=""+Math.floor(XXX);
                               var YYY=""+Math.floor(YYY);
                               if(XXX > -1 && YYY > -1){
                                var mouseMoveEventText=requestUserData+'MCLK'+XXX+'|'+YYY+'|DOWN|4';
                                 usock.send(mouseMoveEventText);
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
                       usock.send(requestUserData+'RCNT');
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





//reader.onloadstart = printEventType;
//reader.onprogress = printEventType;
//reader.onload = printEventType;
//reader.onloadend = printEventType;

function printEventType(event)
{
console.log('got event: ' + event.type);
}


</script>

</body>
</html>