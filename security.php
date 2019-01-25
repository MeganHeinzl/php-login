<?php


session_start();


const SESSION_TIME_OUT = 60*60;


$userinfo = "jadaskdj323898923$&&$" .  $_SERVER["HTTP_USER_AGENT"] . session_id() . "abracdabra1234ABC";
    if($_SESSION['securename'] != $userinfo){
        $_SESSION['errormessage'] = "<p>Log in to view these pages.</p>";
        header("location: login.php"); 
        die();
    }

if(isset($_SESSION['timeLastActive'])){
   $timeLastActive = $_SESSION['timeLastActive'];
   $currentTime = time();

   $timeSinceLastActive = $currentTime - $timeLastActive;

   if($timeSinceLastActive > SESSION_TIME_OUT){
       $_SESSION['errormessage'] = "<p>You've been logged out due to inactivity.</p>";
       header("location: logout.php");
       die();
   }

}
if( isset($_SESSION['username']) ){
	echo "<p>Hello, ".$_SESSION['username']."</p>";	
}else{
	$_SESSION['errormessage'] =  "<p>You have not logged in.</p>";
	header("Location: login.php");
	die(); //always run die() after forwarding with header()
}	

?>