<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    

    
</head>
<body>
<?php
session_start();
if(isset($_SESSION['errormessage'])){

    echo $_SESSION['errormessage'];
    unset($_SESSION['errormessage']);

}


setcookie(session_name(), "", time()-1);
session_destroy();



echo "<h1>Log Out</h1>";
if(isset($_SESSION['logintime'])){
    echo "<p>You logged in at time(): " . $_SESSION['logintime'] . "</p>";
    echo "<p>You logged out at time(): " . time() . "</p>";
    echo "<p>You were logged in for a total of: " . (time() - $_SESSION['logintime']) . " seconds.</p>";

}else{
    echo "<p>You were never logged in!</p>";
}

?>

<a href="login.php">Go log in!</a>

</body>
</html>