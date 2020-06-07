<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
<!--add database-->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-database.js"></script>
<link rel="stylesheet" type="text/css" href="index.css">
<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<div class="app">
    <div class="header" id="header"></div>
  <div class="message-list">
      <ul id="messages">
      </ul>
  </div>

      <!--form for message-->
      <form onsubmit="return sendMessage();" class="send-message-form">
        <input id="message" placeholder="Type your message here" autocomplete="off">
      </form>
    

    </form>
</div>
<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyDtDCu8h1EiDWlkicJwN0oANeKdvCr1958",
    authDomain: "chatsharath.firebaseapp.com",
    databaseURL: "https://chatsharath.firebaseio.com",
    projectId: "chatsharath",
    storageBucket: "chatsharath.appspot.com",
    messagingSenderId: "615586027163",
    appId: "1:615586027163:web:6b1178e85837d2fe92983e"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  var myName=prompt("Enter your name:");
  var myReciver=prompt("Enter the Reciver name");
  var code="<img src='photo.jpg' alt='photo loading...' /><h2>"+myReciver+"</h2>";
  document.getElementById("header").innerHTML=code;
  //Send message process
  function sendMessage(){
    //get message
    var message=document.getElementById("message").value;
    //save in data base
    var now= new Date();
    firebase.database().ref("messages/"+myName+"-"+myReciver).push().set(
    {
      "sender": myName,
      "message": message,
      "reciver":myReciver,
      "time":now.getHours()+":"+now.getMinutes()+":"+now.getSeconds()
    })
    //to prevent from from submitting
    return false;
  }
  //listen to incoming message
  firebase.database().ref("messages/"+myName+"-"+myReciver).on("child_added", function(short){
    var html="";
    html+="<li class='message' id='message-"+ short.key+"'>";
    
    html+="<span class='message-username'>"+short.val().sender+"</span><br><span class='message-text'>"+short.val().message
    html+="<span class='message-username'>"+short.val().time+"</span></span>";
    if(short.val().sender===myName){
      html+="    <button class='button_red' data-id='"+ short.key +"' onclick='deleteMessage(this);'>";
      html+="Delete";
      html+="</button>";
    }
    html+="</li>";
    document.getElementById("messages").innerHTML+=html;
  });
  firebase.database().ref("messages/"+myReciver+"-"+myName).on("child_added", function(short){
    var html="";
    html+="<li class='messagerec' id='message-"+ short.key+"'>";
    
    html+="<span class='messagerec-username'>"+short.val().sender+"</span><br><span class='messagerec-text'>"+short.val().message
  
    html+="<span class='messagerec-username'>"+short.val().time+"</span></span>";
    html+="</li>";
    document.getElementById("messages").innerHTML+=html;
  });
  //delete method
  function deleteMessage(self){
    //get id to delete
    var messageId=self.getAttribute("data-id");
    console.log(messageId)
    //deleting
    firebase.database().ref("messages/"+myName+"-"+myReciver).child(messageId).remove();
}
//refresh
firebase.database().ref("messages/"+myName+"-"+myReciver).on("child_removed", function(short){
  //remove message node
  document.getElementById("message-" + short.key).innerHTML="this message has been removed";
});


</script>


