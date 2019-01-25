<?php
session_start();

$username = "";
$password = "";
$error    = "<p>There is an error with your login.</p>";
$errorflag = 0;
$timeOfLogin = 0;
$validUsername = "";
$validPassword = "";

//SQL variables
require('dbinfo.php');
$query = "";

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//check to make sure connection works
if( mysqli_connect_errno() != 0 ){
	$errorflag++;
        $error = $error . "<p>Could not connect to database.</p>";	
}
//create a query


if(isset($_POST['username'])&& isset($_POST['password'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $query = "SELECT * FROM users WHERE BINARY username='$username' AND BINARY password='$password'";
    $result = $mysqli->query($query);
    $numberOfRows = $result->num_rows;
    echo $result->num_rows;
    if($numberOfRows == 0){
        $errorflag++;
        $error = $error . "<p>Invalid username or password.</p>";
        $_SESSION['errormessage'] = $error;
        $_SESSION['username'] = "";
    }else{
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
    }
 

    if($errorflag > 0){
        header("location: login.php"); 
        die();
    }else{
        $_SESSION['logintime'] = time();
        $_SESSION['timeLastActive'] = time();
        $secureLogIn = "jadaskdj323898923$&&$" .  $_SERVER["HTTP_USER_AGENT"] . session_id() . "abracdabra1234ABC";
        $_SESSION['securename'] = $secureLogIn;
        header("location: page01.php");
        die();
    }


}else{
    $error = $error . "<p>The form fields don't exist. Yikes!</p>";
    $_SESSION['errormessage'] = $error;
    header("location: login.php"); 
    die();
}


?>

