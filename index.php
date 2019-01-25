<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Students</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<?php
require_once("dbinfo.php");
session_start();

$error	= "";

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$query = "SELECT * FROM students;";
$result = $db->query($query);



if(mysqli_connect_errno() != 0){
    $error = $error . "<p>There was an error connecting to the SQL server</p>";
    $_SESSION['errormessage'] = $error;
    echo $error;
    unset($_SESSION['errormessage']);
    die();
}



echo "<div class='wrapper'>";

//if a change was made, display here
if(isset($_SESSION['update'])){
    echo $_SESSION['update'];
    unset($_SESSION['update']);
}
//if an error occured, display here
if( isset($_SESSION['errormessage']) ){
    $error = $_SESSION['errormessage'];
    echo $error;
    unset($_SESSION['errormessage']);
}
$db->close();


echo "<h1>Students</h1>";
echo "<a href='adduserform.php'>Add a student</a>";
echo "<br><br>";
echo "<table>";
while($studentInfo = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$studentInfo['id']."</td>";
    echo "<td>".$studentInfo['firstname']."</td>";
    echo "<td>".$studentInfo['lastname']."</td>";
    echo "<td><a href='are_you_sure.php?id=".$studentInfo['id']."'>delete</a></td>";
    echo "<td><a href='updateform.php?id=".$studentInfo['id']."'>update</a></td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";
?>
</body>
</html>