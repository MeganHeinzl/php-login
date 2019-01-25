<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Student</title>
    <link rel="stylesheet" href="styles/styles.css">

</head>
<body>
    
<?php

require_once("dbinfo.php");
session_start();

//set variables
$error	= "";
$id = "";
$firstname = "";
$lastname = "";
$primarykey = "";

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//test connection to server
if(mysqli_connect_errno() != 0){
    $error = $error . "<p>There was an error connecting to the SQL server</p>";
    $_SESSION['errormessage'] = $error;
    header('location:index.php');
    die();
}
//display error message if exists
if( isset($_SESSION['errormessage']) ){
    $error = $_SESSION['errormessage'];
    echo $error;
    unset($_SESSION['errormessage']);
}

//ensure that the student ID was passed along in the URL
if( isset($_GET['id']) ){
    //ensure that it was not tampered with
    $id = $db->real_escape_string($_GET['id']);
    $query = "SELECT * FROM students WHERE id='".$id."';";
    $result = $db->query($query);

    while($record = $result->fetch_assoc()){
        //track the primary_key so that changes to student numbers can be made
        $_SESSION['primary_key'] = $record['primary_key'];

        $id = trim($db->real_escape_string($record['id']));
        //set the original id in case it is updated
        $_SESSION['originalid'] = $id;
        $firstname = trim($db->real_escape_string($record['firstname']));
        $lastname = trim($db->real_escape_string($record['lastname']));
    
    }
}
else{
    $error = $error . "<p>There was an error with the id field</p>";
    $_SESSION['errormessage'] = $error;
    header('location:index.php');
    die();
}

$db->close();

?>
<h1>Update Existing Student</h1>
<form action="update.php?id=<?php echo $id?>" method="POST">
<label for="id">Student ID: </label>
<input type="text" name="id" value="<?php echo "$id" ?>">
<br>
<label for="firstname">First Name: </label>
<input type="text" name="firstname" value ="<?php echo "$firstname" ?>"> 
<br>
<label for="lastname">Last Name: </label>
<input type="text" name="lastname" value="<?php echo "$lastname" ?>">
<input type="submit" value="Add User">

</form>
<a href="index.php">Go back to Student Table.</a>


</body>
</html>